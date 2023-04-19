<?php

include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

if ($_POST['type'] == 'get-cities-list') {
	exitIfTokenIsNull($_POST['token']);

	$country_id = $_POST['country_id'];

	$result = mysqli_query($connection, "SELECT * FROM `cities` WHERE `country_id` = '$country_id'");
	
	if ($result -> num_rows != 0) {
		$output = array();
		while ($item = mysqli_fetch_assoc($result)) {
			$output[$c['id']]['id'] = $item['id'];
			$output[$c['id']]['title'] = $item['rus_title'];
			$output[$c['id']]['image'] = $item['image'];

			if ($_POST['html']) {
				$output[$item['id']]['html'] = '
					<div class="element" city_id="' . $item['id'] . '">
						<div class="avatar">
							<img src="' . $item['image'] . '">
						</div>
						<div class="title">
							' . $item['rus_title'] . '
						</div>
						<div class="button">
							<button class="button-3">Выбрать</button>
						</div>
					</div>';
			}
		}
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	}	
}

if ($_POST['type'] == 'get-education-institution-list') {
	exitIfTokenIsNull($_POST['token']);
	$city_id = $_POST['city_id'];

	$result = mysqli_query($connection, "SELECT * FROM `education` WHERE `city_id` = '$city_id'");

	if ($result -> num_rows != 0) {
		$output = array();
		while ($item = mysqli_fetch_assoc($result)) {
			$item['status'] = 'hei';
			
			$output[$item['id']]['id'] = $item['id'];
			$output[$item['id']]['title'] = $item['title'];
			$output[$item['id']]['short_title'] = $item['short_title'];
			$output[$item['id']]['image'] = $item['image'];
			$output[$item['id']]['status'] = $item['status'];



			if ($_POST['html']) {
				$output[$item['id']]['html'] = '
					<div class="element" status="' . $item['status'] . '" education_institution_id="' . $item['id'] . '">
						<div class="avatar">
							<img src="' . $item['image'] . '">
						</div>
						<div class="title">
							<p>' . $item['short_title'] . '</p>
							<p>' . $item['title'] . '</p>
						</div>
						<div class="button">
							<button class="button-3">Выбрать</button>
						</div>
					</div>';
			}
		}
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	}
}

if ($_POST['type'] == 'get-faculties-list') {
	exitIfTokenIsNull($_POST['token']);
	$education_institution_id = $_POST['education_institution_id'];

	$result = mysqli_query($connection, "SELECT * FROM `faculties` WHERE `education_id` = '$education_institution_id'");

	if ($result -> num_rows != 0) {
		$output = array();
		while ($item = mysqli_fetch_assoc($result)) {
			$output[$item['id']]['id'] = $item['id'];
			$output[$item['id']]['title'] = $item['title'];
			$output[$item['id']]['short_title'] = $item['short_title'];
			$output[$item['id']]['image'] = $item['image'];
			$output[$item['id']]['status'] = $item['status'];

			if ($_POST['html']) {
				$output[$item['id']]['html'] = '
					<div class="element" status="' . $item['status'] . '" faculty_id="' . $item['id'] . '">
						<div class="avatar">
							<img src="' . $item['image'] . '">
						</div>
						<div class="title">
							<p>' . $item['title'] . '</p>
						</div>
						<div class="button">
							<button class="button-3">Выбрать</button>
						</div>
					</div>';
			}
		}
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	}

}

if ($_POST['type'] == 'get-specializations-list') {
	exitIfTokenIsNull($_POST['token']);
	$faculty_id = $_POST['faculty_id'];

	$result = mysqli_query($connection, "SELECT * FROM `specializations` WHERE `faculty_id` = '$faculty_id'");

	if ($result -> num_rows != 0) {
		$output = array();
		while ($item = mysqli_fetch_assoc($result)) {
			$output[$item['id']]['id'] = $item['id'];
			$output[$item['id']]['title'] = $item['title'];
			$output[$item['id']]['short_title'] = $item['short_title'];
			$output[$item['id']]['image'] = $item['image'];
			$output[$item['id']]['status'] = $item['status'];

			if ($_POST['html']) {
				$output[$item['id']]['html'] = '
					<div class="element" status="' . $item['status'] . '" specialization_id="' . $item['id'] . '">
						<div class="avatar">
							<img src="' . $item['image'] . '">
						</div>
						<div class="title">
							<p>' . $item['short_title'] . '</p>
							<p>' . $item['title'] . '</p>
						</div>
						<div class="button">
							<button class="button-3">Выбрать</button>
						</div>
					</div>';
			}
		}
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	}
}

if ($_POST['type'] == 'get-groups-list') {
	$specialization_id = $_POST['specialization_id'];

	$result = mysqli_query($connection, "SELECT * FROM `groups` WHERE `specialization_id` = '$specialization_id' ORDER BY `title`");

	$output = array();
	while ($s = mysqli_fetch_assoc($result)) {
		// $output[$s['id']] = $s['title'] . ' (' . $s['short_title'] . ')';
		if ($s['head_student'] != '') {
			$head_student = mysqli_fetch_assoc(mysqli_query($connection, "SELECT CONCAT(`last_name`, '-delemiter-', `first_name`) FROM `users` WHERE `id` = {$s['head_student']}"))["CONCAT(`last_name`, '-delemiter-', `first_name`)"];
			$head_student_name = explode('-delemiter-', $head_student);
			$head_student_name = $head_student_name[0] . ' ' . mb_substr($head_student_name[1], 0, 1) . '.';
			$head_student_name_link = '<a target="_blink" href="' . $link . '/profile?id=' . $s['head_student'] . '">' . $head_student_name . '</a>';

		} else {
			$head_student = '';
			$head_student_name = 'Нет';
			$head_student_name_link = 'Нет';
		}
		

		if ($s['students'] == null or $s['students'] == '') {
			$s['students'] = '[]';
		}

		if ($s['robots'] != null) {
			$s['robots'] = json_decode($s['robots'], 1);
		} else {
			$s['robots'] = array();
		}

		if ($s['image'] == '') {
			$image = '<img style="transform: scale(.5); opacity: .3;" src="' . $link . '/assets/img/icons/users.svg">';
		} else {
			$image = '<img src="' . $s['image'] . '">';
		}

		$html = '
			<div class="group" id="group_' . $s['id'] . '">
				<div class="avatar">
					' . $image . '
				</div>
				<div class="info">
					<div class="group_name">' . $s['title'] . '</div>
					<div class="students_info">
						<div class="bullet_point"></div>
						' . count(array_merge(json_decode($s['students']), $s['robots'])) . ' ' . caseOfWords(count(array_merge(json_decode($s['students']), $s['robots'])), 'студенты') . '
					</div>
					<div class="head_of_group_info">
						<img src="' . $link . '/assets/img/icons/crown.svg">
						' . $head_student_name_link . '
					</div>
					<button class="button-2">Подать заявку</button>
				</div>
			</div>
		';

		$output[$s['id']]['group_id'] = $s['id'];
		$output[$s['id']]['members'] = count(json_decode($s['students']));
		$output[$s['id']]['head_student'] = $head_student_name;

		if ($_POST['html']) {
			$output[$s['id']]['html'] = $html;
		}
	}
	echo json_encode($output, JSON_UNESCAPED_UNICODE);

}

if ($_POST['type'] == 'save') {
	exitIfTokenIsNull($user_token);

	$group_id = $_POST['group_id'];
	$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'");
	$output = array();

	if ($group_data -> num_rows != 0) {
		$group_data = mysqli_fetch_assoc($group_data);
		$user_id = (int) $user_id;

		// mysqli_query($connection, "UPDATE `users` SET `group_id` = '$group_id', `specialization_id` = '$specialization_id', `faculty_id` = '$faculty_id', `education_id` = '$education_id' WHERE `id` = '$user_id'");
		if ($user_status == 'Admin') {
			if ($group_data['students'] == '') {
				$students_list = array();
			} else {
				$students_list = json_decode($group_data['students']);
			}

			if (!in_array($user_id, $students_list)) {
				array_push($students_list, $user_id);
			$students_list = json_encode($students_list);
			mysqli_query($connection, "UPDATE `groups` SET `students` = '$students_list' WHERE `id` = '$group_id'");
			mysqli_query($connection, "UPDATE `users` SET `group_id` = '$group_id' WHERE `id` = '$user_id'");
			}

			$output['success'] = true;
			$output['response'] = 'user is new head student'; // Условно
			echoJSON($output);
			exit();
		}

		if ($group_data['head_student'] == null) {
			if ($group_data['students'] == '') {
				$students_list = array();
			} else {
				$students_list = json_decode($group_data['students']);
			}

			if (!in_array($user_id, $students_list)) {
				array_push($students_list, $user_id);
			}
				
			$students_list = json_encode($students_list);
			mysqli_query($connection, "UPDATE `groups` SET `students` = '$students_list', `head_student` = '$user_id' WHERE `id` = '$group_id'");
			mysqli_query($connection, "UPDATE `users` SET `group_id` = '$group_id' WHERE `id` = '$user_id'");

			$output['success'] = true;
			$output['response'] = 'user is new head student';
			echoJSON($output);
			exit();
		} else {
			if (mysqli_query($connection, "SELECT `id` FROM `group_membership_requests` WHERE `user_id` = '$user_id' and `group_id` = '$group_id'") -> num_rows == 0) {
				mysqli_query($connection, "INSERT INTO `group_membership_requests` (`user_id`, `group_id`) VALUES ('$user_id', '$group_id')");
				$output['success'] = true;
				$output['response'] = '';
				echoJSON($output);
				exit();
			}
		}
			
	} else {
		$output['success'] = false;
		$output['response'] = 'group undefined';
		echoJSON($output);
		exit();
	}
}

if ($_POST['type'] == 'accept-invite') {
	exitIfTokenIsNull($user_token);
	$output = array();

	$invite_text_id = $_POST['invite_id'];
	$invite_data = mysqli_query($connection, "SELECT * FROM `invites` WHERE `text_id` = '$invite_text_id'");

	if ($invite_data -> num_rows != 0) {
		$invite_data = mysqli_fetch_assoc($invite_data);
		$group_id = $invite_data['group_id'];
		$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'");

		if ($group_data -> num_rows != 0) {
			$group_data = mysqli_fetch_assoc($group_data);
			if ($group_data['students'] == '') {
				$students_list = array();
			} else {
				$students_list = json_decode($group_data['students']);
			}

			if ($group_data['head_student'] == null) {

				if (!in_array($user_id, $students_list)) {
					array_push($students_list, $user_id);
				}
				
				// echo 'pushed1 ' . $user_id;
				$new_students_list = array();
				foreach ($students_list as $key => $value) {
					if ($value != null) {
						array_push($new_students_list, $value);
					}
				}

				$students_list = json_encode($new_students_list);
				mysqli_query($connection, "UPDATE `groups` SET `students` = '$students_list', `head_student` = '$user_id' WHERE `id` = '$group_id'");
				mysqli_query($connection, "UPDATE `users` SET `group_id` = '$group_id' WHERE `id` = '$user_id'");
				mysqli_query($connection, "DELETE FROM `group_membership_requests`  WHERE `user_id` = '$user_id' and `group_id` = '$group_id'");

				$output['success'] = true;
				$output['response'] = 'user is new head student';
				echoJSON($output);
			} else {

				if (!in_array($user_id, $students_list)) {
					array_push($students_list, $user_id);
					// echo 'pushed2 ' . $user_id;
					$new_students_list = array();
					foreach ($students_list as $key => $value) {
						if ($value != null) {
							array_push($new_students_list, $value);
						}
					}

					$students_list = json_encode($new_students_list);

					// $students_list = json_encode($students_list);
					mysqli_query($connection, "UPDATE `groups` SET `students` = '$students_list' WHERE `id` = '$group_id'");
					mysqli_query($connection, "UPDATE `users` SET `group_id` = '$group_id' WHERE `id` = '$user_id'");
					mysqli_query($connection, "DELETE FROM `group_membership_requests`  WHERE `user_id` = '$user_id' and `group_id` = '$group_id'");
				}
				$output['success'] = true;
				$output['response'] = '';
				echoJSON($output);
			}
		} else {
			$output['success'] = false;
			$output['response'] = 'group does not exist';
			echoJSON($output);
		}
	} else {
		$output['success'] = false;
		$output['response'] = 'invitation is invalid';
		echoJSON($output);
	}
}