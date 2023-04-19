<?php

include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

$user_timezone = $_POST['timezone'];
$_SESSION['user_timezone'] = $user_timezone;

$timezone = $_SESSION['user_timezone'] - $_SESSION['server_timezone'];

// Текущая дата по часовому поясу сервера
$server_current_year = (int) date('Y');
$server_current_month = (int) date('m');
$server_current_day = (int) date('d');

$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

// Текущая дата по часовому поясу клиента
$client_current_year = $server_current_year;
$client_current_month = $server_current_month;
$client_current_day = $server_current_day;

$client_current_minutes = $server_current_minutes;

// Если в один момент на сервере и у клиенка разные дни, то высчитываем настоящее время у клиента
if ($client_current_minutes >= 1440) {

	$client_current_day++;
	$client_current_minutes -= 1440;

	if (cal_days_in_month(CAL_GREGORIAN, $server_current_month, $server_current_year) < $client_current_day) {
		$client_current_month++;
		$client_current_day = 1;

		if ($client_current_month > 12) {
			$client_current_year++;
			$client_current_month = 1;
		}
	}
}

// Подсчёт последнего онлайна пользователя
function calcTime ($date, $func) {
	// Разница между часовыми поясами сервера и пользователя
	global $timezone;

	// Имеем client_last_online - время последнего посещения по часовому поясу клиента
	// global $client_last_online_year;
	// global $client_last_online_month;
	// global $client_last_online_day;
	// global $client_last_online_minutes;

	// Имеем client_current - время на данный момент по часовому поясу клиента
	global $client_current_year;
	global $client_current_month;
	global $client_current_day;
	global $client_current_minutes;

	// global $user_data;

	global $months_accusative;

	// Дата последнего посещения по часовому поясу сервера
	$server_last_online_year = (int) mb_substr($date, 0, 4);
	$server_last_online_month = (int) mb_substr($date, 5, 2);
	$server_last_online_day = (int) mb_substr($date, 8, 2);

	$server_last_online_hour = (int) mb_substr($date, 11, 2);
	$server_last_online_minute = (int) mb_substr($date, 14, 2);

	$server_minutes = $server_last_online_hour * 60 + $server_last_online_minute + $timezone;

	// Дата последнего посещения по часовому поясу клиента
	$client_last_online_year = $server_last_online_year;
	$client_last_online_month = $server_last_online_month;
	$client_last_online_day = $server_last_online_day;
	$client_last_online_minutes = $server_minutes;

	// Если в на сервере и у клиента разные дни, то высчитываем последнее время посещения от лица клиента
	if ($client_last_online_minutes >= 1440) {

		$client_last_online_day++;
		$client_last_online_minutes -= 1440;

		if (cal_days_in_month(CAL_GREGORIAN, $server_last_online_month, $server_last_online_year) < $client_last_online_day) {
			$client_last_online_month++;
			$client_last_online_day = 1;

			if ($client_last_online_month > 12) {
				$client_last_online_year++;
				$client_last_online_month = 1;
			}
		}
	}
	
	if ($func == 'user_date_presence_archive') {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return addZeroes($hour) . ':' . addZeroes($minute);
	}
}

if ($_POST['type'] == 'get-short-info') {
	exitIfTokenIsNull($user_token);
	$group_id = $_POST['group_id'];

	$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'");
	$output = array();

	if ($group_data -> num_rows != 0) {
		$group_data = mysqli_fetch_assoc($group_data);


		$group_data['students'] = json_decode($group_data['students']);
		$group_data['robots'] = json_decode($group_data['robots']);

		$output['success'] = true;

		$output['response']['title'] = $group_data['title'];
		$output['response']['short_title'] = $group_data['short_title'];
		$output['response']['students'] = $group_data['students'];
		$output['response']['specialization_id'] = $group_data['specialization_id'];
		$output['response']['head_student'] = $group_data['head_student'];
		$output['response']['deputy_head_student'] = $group_data['deputy_head_student'];
		$output['response']['year_of_admission'] = $group_data['year_of_admission'];
		$output['response']['admission_class'] = $group_data['admission_class'];

		$head_student_id = $group_data['head_student'];
		$head_student_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$head_student_id'"));

		$output['response']['head_student_name'] = $head_student_data['last_name'] . ' ' . mb_substr($head_student_data['first_name'], 0, 1) . '.';

		$output['response']['admission_class'] = 'request';

		$button_html = '<button class="button-2">Отменить запрос</button>';

		if ($group_data['students'] == null) {
			$group_data['students'] = array();
		}

		if ($group_data['robots'] == null) {
			$group_data['robots'] = array();
		}

		if (in_array($user_id, $group_data['students'])) {
			$output['response']['admission_class'] = 'in_the_group';
			$button_html = '<button class="button-2">Покинуть группу</button>';
		}

		// echo 2;
		// <div class="bullet_point"></div>
		$students_info_icon = '<div class="bullet_point"></div>';
		// $students_info_icon = '<img src="' . $link . '/assets/img/icons/users.svg">';

		if ($_POST['get_html'] == true) {
			$output['response']['html'] = '
			<div class="group" id="group_' . $group_id . '">
				<div class="avatar">
					<img src="' . $link . '/assets/img/findstudents.jpg" style="transform: scale(1.2);">
				</div>
				<div class="info">
					<div class="group_name">' . $group_data['title'] . '</div>
					<div class="students_info">
						' . $students_info_icon . '
						' . count(array_merge($group_data['students'], $group_data['robots'])) . ' ' . caseOfWords(count(array_merge($group_data['students'], $group_data['robots'])), 'студенты') . '
					</div>
					<div class="head_of_group_info">
						<img src="' . $link . '/assets/img/icons/crown.svg">
						' . $output['response']['head_student_name'] . '
					</div>
					' . $button_html . '
				</div>
			</div>
			';
		}
		
		

	} else {
		$output = array('success' => false, 'response' => 'group undefined');
	}
	echoJSON($output);
}




if ($_POST['type'] == 'get-students-list') {
	exitIfTokenIsNull($user_token);
	$group_id = $_POST['group_id'];

	if ($group_id == $user_group_id) {
		$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id' ");

		if ($group_data -> num_rows != 0) {
			$group_data = mysqli_fetch_assoc($group_data);
			if ($group_data['robots'] != null) {
				$group_data['robots'] = json_decode($group_data['robots'], 1);
			} else {
				$group_data['robots'] = array();
			}

			$group_students_ids = json_decode($group_data['students']);
			$group_students_ids_text = '(' . implode(',', $group_students_ids) . ')';
			$group_students_data_from_db = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` IN $group_students_ids_text ORDER BY `last_name`");

			$group_students_data = array();
			while ($s = mysqli_fetch_assoc($group_students_data_from_db)) {
				$group_students_data[$s['id']] = $s;
			}

			foreach ($group_data['robots'] as $key => $value) {
				$group_students_data[$value['robot_id']] = array(
					"id" => $value['robot_id'],
					"first_name" => $value['robot_name'],
					"last_name" => $value['robot_surname'],
					"status" => 'robot',
					"photo" => $link . '/assets/img/icons/robot.svg',
					"photo_style" => 'a:2:{s:5:"ox_oy";s:0:"";s:5:"scale";s:4:"0.80";}'
				);
			}

			$output = array();
			$output['success'] = true;
			$output['response']['present_students'] = array();
			$output['response']['missing_students'] = array();

			$date = date('d.m.Y');
			$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$group_id' and `date` = '$date'");

			$archive_array = array();
			if ($archive -> num_rows != 0) {
				$archive_data = mysqli_fetch_assoc($archive);

				if ($archive_data['students'] == null or $archive_data['students'] == '') {
					$archive_array = array();
				} else {
					$archive_array = json_decode($archive_data['students'], 1);
				}
			}

			foreach ($group_students_data as $student_id => $student_data) {
				$student_photo_style = unserialize($student_data['photo_style']);
				$crown = '';
				$student_is_head_student = false;

				if ($student_id == $group_data['head_student'] or $student_id == $group_data['deputy_head_student']) {
					$crown = '<img src="' . $link . '/assets/img/icons/crown.svg">';
					$student_is_head_student = true;
				}

				if ($student_data['status'] == 'Admin') {
					// $crown .= '<img src="' . $link . '/assets/img/icons/mood-crazy-happy.svg">';
				}

				if (mb_strpos($student_id, 'robot') !== false) {
					$crown = '<img src="' . $link . '/assets/img/icons/robot.svg">';
				}

				if (gettype($archive_array[$student_id]) != 'array') {
					// Студент не появлялся в этот день
					if ($_POST['get_html']) {
						$output_html = '
						<li>
							<a href="' . $link . '/profile?id=' . $student_id . '">
								<div class="avatar">
									<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
								</div>
							</a>
							<div class="user_info">
								<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
								<p class="status">Явка не подтверждена</p>
							</div>
						</li>
						';
					}

					array_push($output['response']['missing_students'], array(
						"student_id" => $student_id,
						"first_name" => $student_data['first_name'],
						"last_name" => $student_data['last_name'],
						"avatar" => $student_data['photo'],
						"is_head_student" => $student_is_head_student,
						"presence_status" => "presence not confirmed",
						"html" => $output_html

					));

					
					
					// echo $student_id;
				} else {
					if ($archive_array[$student_id]['history'][count($archive_array[$student_id]['history'])-1]['activity'] == 'forcibly-join') {
						$activity = 'Явка подтверждена старостой <img src="' . $link . '/assets/img/icons/circle-check.svg">';
						$activity_eng_text = 'presence confirmed';
					} else {
						$activity = 'Явка подтверждена <img src="' . $link . '/assets/img/icons/circle-check.svg">';
						$activity_eng_text = 'presence confirmed by head student';
					}
					if ($archive_array[$student_id]['active'] == true) {
						if ($_POST['get_html']) {
							$output_html = '
							<li>
								<a href="' . $link . '/profile?id=' . $student_id . '">
									<div class="avatar">
										<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
									</div>
								</a>
								<div class="user_info">
									<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
									<p class="status">' . $activity . '</p>
								</div>
							</li>
							';
						} 

						array_push($output['response']['present_students'], array(
							"student_id" => $student_id,
							"first_name" => $student_data['first_name'],
							"last_name" => $student_data['last_name'],
							"avatar" => $student_data['photo'],
							"is_head_student" => $student_is_head_student,
							"presence_status" => $activity_eng_text,
							"html" => $output_html
						));

						
					} else {
						$last_active_date = end($archive_array[$student_id]['history'])['time'];

						if ($archive_array[$student_id]['history'][count($archive_array[$student_id]['history'])-1]['activity'] == 'forcibly-leave') {
							$activity = 'Явка была снята старостой';
							$activity_eng_text = 'presence was removed by head student';
						} else {
							$activity = 'Явка была активна до ' . calcTime($last_active_date, 'user_date_presence_archive');
							$activity_eng_text = 'presence was confirmed until';
						}

						if ($_POST['get_html']) {
							$output_html = '
							<li>
								<a href="' . $link . '/profile?id=' . $student_id . '">
									<div class="avatar">
										<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
									</div>
								</a>
								<div class="user_info">
									<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
									<p class="status">' . $activity . '</p>
								</div>
							</li>
							';
						}

						if ($activity_eng_text == 'presence was confirmed until') {
							array_push($output['response']['missing_students'], array(
							"student_id" => $student_id,
							"first_name" => $student_data['first_name'],
							"last_name" => $student_data['last_name'],
							"avatar" => $student_data['photo'],
							"is_head_student" => $student_is_head_student,
							"presence_status" => $activity_eng_text,
							"html" => $output_html,
							"presence_before_date" => $last_active_date
						));

						} else {
							array_push($output['response']['missing_students'], array(
							"student_id" => $student_id,
							"first_name" => $student_data['first_name'],
							"last_name" => $student_data['last_name'],
							"avatar" => $student_data['photo'],
							"is_head_student" => $student_is_head_student,
							"presence_status" => $activity_eng_text,
							"html" => $output_html
						));
						}
					}
				}
			}
		}

		echoJSON($output);
	} else {
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
	}
}










if ($_POST['type'] == 'get-users-data-for-head-student') {
	exitIfTokenIsNull($user_token);
	$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$user_group_id' ");

	if ($group_data -> num_rows != 0) {
		$group_data = mysqli_fetch_assoc($group_data);
		if ($group_data['robots'] != null) {
			$group_data['robots'] = json_decode($group_data['robots'], 1);
		} else {
			$group_data['robots'] = array();
		}


		if ($group_data['head_student'] != $user_id and $group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
			$output = array();
			$output['success'] = false;
			$output['response'] = 'access denied';
			echoJSON($output);
			exit();
		}

		$group_students_ids = json_decode($group_data['students']);
		$group_students_ids_text = '(' . implode(',', $group_students_ids) . ')';
		$group_students_data_from_db = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` IN $group_students_ids_text ORDER BY `last_name`");

		$group_students_data = array();
		while ($s = mysqli_fetch_assoc($group_students_data_from_db)) {
			$group_students_data[$s['id']] = $s;
		}

		foreach ($group_data['robots'] as $key => $value) {
			$group_students_data[$value['robot_id']] = array(
				"id" => $value['robot_id'],
				"first_name" => $value['robot_name'],
				"last_name" => $value['robot_surname'],
				"status" => 'robot',
				"photo" => $link . '/assets/img/icons/robot.svg',
				"photo_style" => 'a:2:{s:5:"ox_oy";s:0:"";s:5:"scale";s:4:"0.80";}'
			);
		}

		$output = array();
		$output['success'] = true;
		$output['response']['present_students'] = '';
		$output['response']['missing_students'] = '';
		$output['response']['requests'] = '';

		$date = date('d.m.Y');
		$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		$archive_array = array();
		if ($archive -> num_rows != 0) {
			$archive_data = mysqli_fetch_assoc($archive);

			if ($archive_data['students'] == null or $archive_data['students'] == '') {
				$archive_array = array();
			} else {
				$archive_array = json_decode($archive_data['students'], 1);
			}
			
			// $archive_array = json_decode(mysqli_fetch_assoc($archive)['students'], 1);
			// echo 12;
		}

		foreach ($group_students_data as $student_id => $student_data) {
			$student_photo_style = unserialize($student_data['photo_style']);
			$crown = '';
			$group_status = 'student';
			if ($student_id == $group_data['head_student'] or $student_id == $group_data['deputy_head_student']) {
				$crown = '<img src="' . $link . '/assets/img/icons/crown.svg">';
				$group_status = true;
			}
			if ($student_id == $group_data['deputy_head_student']) {
				$group_status = 'deputy_head_student';
			}
			if ($student_id == $group_data['head_student']) {
				$group_status = 'head_student';
			}

			if ($student_data['status'] == 'Admin') {
				$crown .= '<img src="' . $link . '/assets/img/icons/database.svg">';
			}
			if (mb_strpos($student_id, 'robot') !== false) {
					$crown = '<img src="' . $link . '/assets/img/icons/robot.svg">';
					$group_status = 'robot';
				}

			if (gettype($archive_array[$student_id]) != 'array') {
				// Студент не появлялся в этот день
				$output['response']['missing_students'] .= '
				<li group_status="' . $group_status . '" user_status="' . $student_data['status'] . '" class="missing_student" id="missing_user_' . $student_id . '">
					<a href="' . $link . '/profile?id=' . $student_id . '">
						<div class="avatar">
							<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
						</div>
					</a>
					<div class="user_info">
						<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
						<p class="status">Явка не подтверждена</p>
					</div>
					<div class="buttons">
						<button class="add_presence">
							<img src="' . $link . '/assets/img/icons/square-plus.svg">
						</button>
						<button class="user_management">
							<img src="' . $link . '/assets/img/icons/settings.svg">
						</button>
					</div>
				</li>
				';
				// echo $student_id;
			} else {
				if ($archive_array[$student_id]['active'] == true) {
					if ($archive_array[$student_id]['history'][count($archive_array[$student_id]['history'])-1]['activity'] == 'forcibly-join') {
						$activity = 'Явка подтверждена старостой <img src="' . $link . '/assets/img/icons/circle-check.svg">';
					} else {
						$activity = 'Явка подтверждена <img src="' . $link . '/assets/img/icons/circle-check.svg">';
					}
					$output['response']['present_students'] .= '
					<li group_status="' . $group_status . '" user_status="' . $student_data['status'] . '" id="present_user_' . $student_id . '">
						<a href="' . $link . '/profile?id=' . $student_id . '">
							<div class="avatar">
								<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
							</div>
						</a>
						<div class="user_info">
							<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
							<p class="status">' . $activity . '</p>
						</div>
						<div class="buttons">
							<button class="remove_presence">
								<img src="' . $link . '/assets/img/icons/square-minus.svg">
							</button>
							<button class="user_management">
								<img src="' . $link . '/assets/img/icons/settings.svg">
							</button>
						</div>
					</li>
					';
				} else {
					$last_active_date = end($archive_array[$student_id]['history'])['time'];

					if ($archive_array[$student_id]['history'][count($archive_array[$student_id]['history'])-1]['activity'] == 'forcibly-leave') {
						$activity = 'Явка была снята старостой <img src="' . $link . '/assets/img/icons/circle-check.svg">';
					} else {
						$activity = 'Явка была активна до ' . calcTime($last_active_date, 'user_date_presence_archive');
					}

					// echo '--' . $last_active_date . '--';
					$output['response']['missing_students'] .= '
					<li group_status="' . $group_status . '" user_status="' . $student_data['status'] . '" class="missing_student" id="missing_user_' . $student_id . '">
					<a href="' . $link . '/profile?id=' . $student_id . '">
						<div class="avatar">
							<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
						</div>
					</a>
					<div class="user_info">
						<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . $crown . '</p>
						<p class="status">' . $activity . '</p>
					</div>
					<div class="buttons">
						<button class="add_presence">
							<img src="' . $link . '/assets/img/icons/square-plus.svg">
						</button>
						<button class="user_management">
							<img src="' . $link . '/assets/img/icons/settings.svg">
						</button>
					</div>
				</li>
					';
				}
			}
		}


		// foreach ($students as $index => $student_id) {
		// 	// $students_data = mysqli_fetch_assoc( mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$student_id'"));
		// 	$student_data = $students_data[$student_id];
		// 	$student_photo_style = unserialize($student_data['photo_style']);

			

				
		// }
		$requests = mysqli_query($connection, "SELECT * FROM `group_membership_requests` WHERE `group_id` = '$user_group_id'");

		if ($requests -> num_rows != 0) {
			while ($r = mysqli_fetch_assoc($requests)) {
				$student_id = $r['user_id'];

				$student_data = mysqli_fetch_assoc( mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$student_id'"));
				// $student_data = $students_data[$student_id];

				$student_photo_style = unserialize($student_data['photo_style']);

				$output['response']['requests'] .= '
				<li group_status="' . $student_is_head_student . '" user_status="' . $student_data['status'] . '" id="request_user_' . $student_id . '">
					<a href="' . $link . '/profile?id=' . $student_id . '">
						<div class="avatar">
							<img draggable="false" style="' . $student_photo_style['ox_oy'] . 'transform: scale(' . $student_photo_style['scale'] . ');" src="' . $student_data['photo'] . '">
						</div>
					</a>
					<div class="user_info">
						<p class="username">' . $student_data['last_name'] . ' ' . $student_data['first_name'] . '</p>
						<p class="status">Запрашивает разрешение</p>
					</div>
					<div class="buttons">
						<button class="accept_request">
							<img src="' . $link . '/assets/img/icons/circle-plus.svg">
						</button>
						<button class="reject_request">
							<img src="' . $link . '/assets/img/icons/circle-minus.svg">
						</button>
					</div>
				</li>';
			};
		}
	}

	echoJSON($output);
}


if ($_POST['type'] == 'create-report') {
	exitIfTokenIsNull($user_token);

	if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
		$output = array();
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}
	
	$date = date('d.m.Y');
	$user_group_id = $user_group_id;
	$archive_data = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	// echo $date;
	// echo $user_group_id;
	$group_students = json_decode($user_group_data['students']);

	if ($user_group_data['robots'] != null) {
		$group_robots = json_decode($user_group_data['robots'], 1);
	} else {
		$user_group_data['robots'] = array();
	}

	if ($archive_data -> num_rows == 0) {
		$archive = array();
		// echo 1;
	} else {
		$archive_data = mysqli_fetch_assoc($archive_data);
		if ($archive_data['students'] == '' or $archive_data['students'] == '[]') {
			$archive = array();
		} else {
			$archive = json_decode($archive_data['students'], 1);
		}
	}

	$present_students_data = array();
	$missing_students_data = array();

	// Перебор истории посещений
	foreach ($archive as $student_id => $student_history) {
		if (isset($student_history['student_name'])) {
			$student_id = array(
				'student_id' => $student_id,
				'student_name' => $student_history['student_name'],
				'student_surname' => $student_history['student_surname'],
			);
			if ($student_history['active']) {
				array_push($present_students_data, $student_id);
			} else {
				array_push($missing_students_data, $student_id);
			}
		}
	}

	// Перебор всех "роботов"
	foreach ($group_robots as $index => $robot_data) {
		$flag = 0;
		foreach ($present_students_data as $key => $value) {
			if ($value['student_id'] == $robot_data['robot_id']) {
				$flag = 1;
			}
		}
		foreach ($missing_students_data as $key => $value) {
			if ($value['student_id'] == $robot_data['robot_id']) {
				$flag = 1;
			}
		}
		if ($flag == 0) {
			$robot_info = array(
				"student_id" => $robot_data['robot_id'],
				"student_name" => $robot_data['robot_name'],
				"student_surname" => $robot_data['robot_surname']
			);
			array_push($missing_students_data, $robot_info);
		}
	}
	$output['response']['check_missing'] = json_encode($missing_students_data, JSON_UNESCAPED_UNICODE);
	$output['response']['check_present'] = json_encode($present_students_data, JSON_UNESCAPED_UNICODE);


	// Проверка наличия неотметившихся студентов в списках
	$tmp_missing_students_ids = array();
	foreach ($group_students as $student_id) {
		$flag = 0;
		foreach ($present_students_data as $key => $value) {
			if ($value['student_id'] == $student_id) {
				$flag = 1;
			}
		}
		foreach ($missing_students_data as $key => $value) {
			if ($value['student_id'] == $student_id) {
				$flag = 1;
			}
		}
		if ($flag == 0) {
			array_push($tmp_missing_students_ids, $student_id);
		}
	}

	$tmp_missing_students_ids = implode(',', $tmp_missing_students_ids);
	$students_without_presence_history = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` IN (" . $tmp_missing_students_ids . ")");

	while ($s = mysqli_fetch_assoc($students_without_presence_history)) {
		$student_data = array(
			'student_id' => $s['id'],
			'student_name' => $s['first_name'],
			'student_surname' => $s['last_name'],
		);
		
		array_push($missing_students_data, $student_data);
	}

	$report = array();
	$report['present_students'] = $present_students_data;
	$report['missing_students'] = $missing_students_data;

	$report = json_encode($report, JSON_UNESCAPED_UNICODE);

	$date = date('d.m.Y H:i:s');

	$text_id = md5($date . date('.u'));

	mysqli_query($connection, "INSERT INTO `visits_reports` (`text_id`, `group_id`, `user_id`, `date`, `archive`) VALUES ('$text_id', '$user_group_id', '$user_id', '$date', '$report')");

	$output['success'] = true;
	$output['response']['report_id'] = $text_id;

	echoJSON($output);
}




if ($_POST['type'] == 'get-report') {
	// ВОЗМОЖНА НЕКОРРЕКТНАЯ РАБОТА
	$text_id = $_POST['report_id'];

	if ($group_data['head_student'] != $user_id and $group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
		$output = array();
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}

	$report_data = mysqli_query($connection, "SELECT * FROM `visits_reports` WHERE `text_id` = '$text_id'");

	if ($report_data -> num_rows != 0) {
		$report_data = mysqli_fetch_assoc($report_data);

		$report_archive = json_decode($report_data['archive'], 1);

		$students_list = array_merge($report_archive['present_students'], $report_archive['missing_students']);

		$students_list_text = '(' . implode(',', $students_list) . ')';
		$query_text = "SELECT CONCAT(`id`, '-=delemiter=-', `last_name`, '-=delemiter=-', `first_name`) FROM `users` WHERE `id` IN " . $students_list_text . " ORDER BY `last_name`";
		$students_data = mysqli_query($connection, $query_text);

		while ($s = mysqli_fetch_assoc($student_data)["CONCAT(`id`, '-=delemiter=-', `last_name`, '-=delemiter=-', `first_name`)"]) {
			$local_student_data = explode('-=delemiter=-', $s);
			$local_student_id = $local_student_data[0];
			$local_student_first_name = $local_student_data[2];
			$local_student_last_name = $local_student_data[1];	
		}


	} else {
		$output['success'] = false;
		$output['response']['report_id'] = 'Invalid report_id';

		echoJSON($output);
	}
}





if ($_POST['type'] == 'get-reports-list') {
	exitIfTokenIsNull($_POST['token']);

	$token = $_POST['token'];
	$html = $_POST['html'];

	$group_id = mysqli_query($connection, "SELECT `group_id` FROM `users` WHERE `token` = '$token'");

	if ($group_id -> num_rows != 0) {
		$group_id = mysqli_fetch_assoc($group_id)['group_id'];
		$reports = mysqli_query($connection, "SELECT * FROM `visits_reports` WHERE `group_id` = '$group_id' ORDER BY `id` DESC LIMIT 0, 10");

		$users_of_group = mysqli_query($connection, "SELECT * FROM `users` WHERE `group_id` = '$group_id'");

		$users_of_group_array = array();
		while ($user = mysqli_fetch_assoc($users_of_group)) {
			$users_of_group_array[$user['id']] = array();
			$users_of_group_array[$user['id']]['first_name'] = $user['first_name'];
			$users_of_group_array[$user['id']]['last_name'] = $user['last_name'];
			$users_of_group_array[$user['id']]['id'] = $user['id'];
		}

		$output = array();
		$output['success'] = true; 
		$output['response'] = array();
		$output['response']['html'] = '';

		while ($item = mysqli_fetch_assoc($reports)) {
			$owner_id = $item['user_id'];
			$owner_name = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `last_name` FROM `users` WHERE `id` = '$owner_id'"))['last_name'];

			$archive = json_decode($item['archive'], 1);

			$present_students = '';
			foreach ($archive['present_students'] as $key) {
				$present_students .= '<li>' . $users_of_group_array[$key['student_id']]['first_name'] . ' ' . $users_of_group_array[$key['student_id']]['last_name'] . '</li>';
			}

			$missing_students = '';
			foreach ($archive['missing_students'] as $key) {
				$missing_students .= '<li>' . $users_of_group_array[$key['student_id']]['first_name'] . ' ' . $users_of_group_array[$key['student_id']]['last_name'] . '</li>';
			}

			$output['response']['html'] .= '
			<div class="report" id="report_' . $item['id'] . '">
				<div class="info">
					<div class="date">
						<img src="' . $link . '/assets/img/icons/file-text.svg">
						' . mb_substr($item['date'], 0, 2) . ' ' . $months_short[mb_substr($item['date'], 3, 2)] . ' ' . mb_substr($item['date'], 11, 8) . ' (мск)
					</div>
					<div class="arrow_down">
						<img src="' . $link . '/assets/img/icons/chevron-down.svg">
					</div>
				</div>
				<div class="details">
					<div class="about_report">
						<div class="father">
							Сформировал(а): <b>' . $owner_name . '</b>
						</div>
						<div class="controls">
							<a target="_blink" href="' . $link . '/visit-report?id=' . $item['text_id'] . '"><button class="open_report button-1">
								Посмотреть
							</button></a>
							<button class="update_students_list button-1">
								Обновить список
							</button>
							<button link="' . $link . '/visit-report?id=' . $item['text_id'] . '" class="copy_link button-3">
								Копировать ссылку
							</button>
						</div>
					</div>
					<div class="students">
						<div class="present">
							<div class="title">Присутствующие</div>
							<ol>
								' . $present_students . '
							</ol>
						</div>
						<div class="missing">
							<div class="title">Отсутствующие</div>
							<ol>
								' . $missing_students . '
							</ol>
						</div>
					</div>
				</div>
			</div>';
		}
	} else {
		$output = array();
		$output['success'] = false; 
		$output['response'] = 'group not found';
	}
	echoJSON($output);
}






if ($_POST['type'] == 'update-report') {
	exitIfTokenIsNull($user_token);

	if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
		$output = array();
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}
	
	$date = date('d.m.Y');
	$user_group_id = $user_group_id;
	$archive_data = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	// echo $date;
	// echo $user_group_id;
	$group_students = json_decode($user_group_data['students']);

	if ($user_group_data['robots'] != null) {
		$group_robots = json_decode($user_group_data['robots'], 1);
	} else {
		$user_group_data['robots'] = array();
	}

	if ($archive_data -> num_rows == 0) {
		$archive = array();
		// echo 1;
	} else {
		$archive_data = mysqli_fetch_assoc($archive_data);
		if ($archive_data['students'] == '' or $archive_data['students'] == '[]') {
			$archive = array();
		} else {
			$archive = json_decode($archive_data['students'], 1);
		}
	}

	$present_students_data = array();
	$missing_students_data = array();

	// Перебор истории посещений
	foreach ($archive as $student_id => $student_history) {
		if (isset($student_history['student_name'])) {
			$student_id = array(
				'student_id' => $student_id,
				'student_name' => $student_history['student_name'],
				'student_surname' => $student_history['student_surname'],
			);
			if ($student_history['active']) {
				array_push($present_students_data, $student_id);
			} else {
				array_push($missing_students_data, $student_id);
			}
		}
	}

	// Перебор всех "роботов"
	foreach ($group_robots as $index => $robot_data) {
		$flag = 0;
		foreach ($present_students_data as $key => $value) {
			if ($value['student_id'] == $robot_data['robot_id']) {
				$flag = 1;
			}
		}
		foreach ($missing_students_data as $key => $value) {
			if ($value['student_id'] == $robot_data['robot_id']) {
				$flag = 1;
			}
		}
		if ($flag == 0) {
			$robot_info = array(
				"student_id" => $robot_data['robot_id'],
				"student_name" => $robot_data['robot_name'],
				"student_surname" => $robot_data['robot_surname']
			);
			array_push($missing_students_data, $robot_info);
		}
	}
	$output['response']['check_missing'] = json_encode($missing_students_data, JSON_UNESCAPED_UNICODE);
	$output['response']['check_present'] = json_encode($present_students_data, JSON_UNESCAPED_UNICODE);


	// Проверка наличия неотметившихся студентов в списках
	$tmp_missing_students_ids = array();
	foreach ($group_students as $student_id) {
		$flag = 0;
		foreach ($present_students_data as $key => $value) {
			if ($value['student_id'] == $student_id) {
				$flag = 1;
			}
		}
		foreach ($missing_students_data as $key => $value) {
			if ($value['student_id'] == $student_id) {
				$flag = 1;
			}
		}
		if ($flag == 0) {
			array_push($tmp_missing_students_ids, $student_id);
		}
	}

	$tmp_missing_students_ids = implode(',', $tmp_missing_students_ids);
	$students_without_presence_history = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` IN (" . $tmp_missing_students_ids . ")");

	while ($s = mysqli_fetch_assoc($students_without_presence_history)) {
		$student_data = array(
			'student_id' => $s['id'],
			'student_name' => $s['first_name'],
			'student_surname' => $s['last_name'],
		);
		
		array_push($missing_students_data, $student_data);
	}

	$report = array();
	$report['present_students'] = $present_students_data;
	$report['missing_students'] = $missing_students_data;

	$report = json_encode($report, JSON_UNESCAPED_UNICODE);

	$report_id = $_POST['report_id'];
	mysqli_query($connection, "UPDATE `visits_reports` SET `archive` = '$report' WHERE `id` = '$report_id'");

	// mysqli_query($connection, "INSERT INTO `visits_reports` (`text_id`, `group_id`, `user_id`, `date`, `archive`) VALUES ('$text_id', '$user_group_id', '$user_id', '$date', '$report')");

	$output['success'] = true;
	// $output['response']['report_id'] = $text_id;

	echoJSON($output);
}


