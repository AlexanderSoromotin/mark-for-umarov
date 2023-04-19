<?php

include_once "info.php";
include_once 'db.php';
$lang = 'rus';

function deleteZeroes ($text) {
	if ($text[0] == '0') {
		return $text[1];
	}
	return $text;
}

function addZeroes ($text) {
	if (strlen($text) == 1) {
		return '0' . $text;
	}
	return $text;
}
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
function calcTime ($date) {
	// Разница между часовыми поясами сервера и пользователя
	global $timezone;

	// Имеем client_current - время на данный момент по часовому поясу клиента
	global $client_current_year;
	global $client_current_month;
	global $client_current_day;
	global $client_current_minutes;

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

	if ($client_current_year != $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		// return $client_current_year;
		return addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}

	if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
		// return $client_current_minutes . ' ' . $client_last_online_minutes;
		return 'Только что';

	}

	if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
		return caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

	}
	if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return 'Вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return 'Сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
}


if ($_POST['type'] == 'add-interest') {
	$user_id = decodeSecretID($_POST['secret_id'], 'addInterest');

	if ($user_id) {
		$group = $_POST['group'];
		$title = $_POST['title'];
		$body = $_POST['body'];
		$loc = $_POST['loc'];
		$repost = $_POST['repost'];

		if ($group == '') {
			echo 'The group name is empty';
			exit();
		}
		if ($title == '') {
			echo 'The title is empty';
			exit();
		}
		if ($body == '') {
			echo 'The main message is empty';
			exit();
		}
		if ($loc != 1) {
			$loc = 0;
		}
		if ($repost != 1) {
			$repost = 0;
		}

		$group_flag = 0;
		$groups_from_db = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
		while ($g = mysqli_fetch_assoc($groups_from_db)) {
			if (strpos($g[$lang.'_title'], $group) !== false) {
				$group_flag = 1;
				$group_id = $g['id'];
				break;
			}
		}

		if ($group_flag == 0) {
			echo 'Invalid group. А group with this name was not found';
			exit();
		}
		if (mb_strlen($title) < 2) {
			echo 'Title is too short';
			exit();
		}
		if (mb_strlen($body) < 2) {
			echo 'Main message is too short';
			exit();
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$user_city_id = $user_data['city_id'];

		if ($repost == 1) {
			$friends = $user_data['friends'];
			if ($friends != '') {
				$friends = unserialize($friends);

				foreach ($friends as $local_user_id) {
					$body = serialize(array("outgoing_id" => $user_id, "subsection_id" => $group_id));
					mysqli_query($connection, "INSERT INTO `notifications` (`incoming_id`, `body`, `type`) VALUES ('$local_user_id', '$body', 'new-interest') ");
				}
			}
		}

		mysqli_query($connection, "INSERT INTO `interests_records` (`user_id`, `title`, `body`, `loc`, `city_id`, `repost`, `group_id`) VALUES ('$user_id', '$title', '$body', '$loc', '$user_city_id', '$repost', '$group_id') ");
		echo 'record added';

	} else {
		echo $apiErrorCodes['1.1'];
	}
}







if ($_POST['type'] == 'get-last-interests') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getLastInterests');
	$loc = $_POST['loc'];

	if ($user_id) {
		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));

		$interests = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
		$interests_array = array();

		while ($i = mysqli_fetch_assoc($interests)) {
			$interests_array[$i['id']] = array('rus_title' => $i['rus_title'], 'eng_title' => $i['eng_title'], 'icon' => $link . $i['icon']);
		}

		if ($loc == 1) {
			$user_city_id = $user_data['city_id'];
			$records = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `city_id` = '$user_city_id' and `status` == '' ORDER BY `id` DESC");
		} else {
			$records = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `status` == '' ORDER BY `id` DESC");
		}

		if ($records -> num_rows == 0) {
			echo '<div class="empty">Тут пусто :с</div>';
			exit();
		}

		$output = '';

		while ($r = mysqli_fetch_assoc($records)) {
			$local_user_id = $r['user_id'];
			$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));

			if (mb_strlen($r['title']) > 60) {
				$r['title'] = mb_substr($r['title'], 0, 56) . '...';
			}
			$output .= '
			<a href="' . $link . '/interest-groups/record/?id=' . $r['id'] . '">
				<div class="interests-block">
					<div class="row-1">
						<div class="photo">
							<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
						</div>
					</div>
					<div class="row-2">
							<p>' . $r['title'] . '</p>
							<div class="info">
								<div class="subinfo">
									<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
									<div class="bullet-point"></div> ' . calcTime($r['date']) . '
								</div>
								<div class="group">
									<img src="' . $interests_array[$r['group_id']]['icon'] . '">
									' . $interests_array[$r['group_id']][$lang . '_title'] . '
								</div>
							</div>

					</div>
				</div>
			</a>';
		}

		echo $output;
		// var_dump($interests_array); 


	} else {
		echo $apiErrorCodes['1.1'];
	}
}





if ($_POST['type'] == 'search-interests') {
	$user_id = decodeSecretID($_POST['secret_id'], 'searchInterests');

	if ($user_id) {
		$interests = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
		$interests_array = array();

		while ($i = mysqli_fetch_assoc($interests)) {
			$interests_array[$i['id']] = array('rus_title' => $i['rus_title'], 'eng_title' => $i['eng_title'], 'icon' => $link . $i['icon']);
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$city_id = $user_data['city_id'];

		$limitTo = $_POST['limitTo'];
		$limitFrom = $_POST['limitFrom'];

		$subsections = $_POST['subsections']; // '1,2,3,4'
		$search_text = mb_strtolower($_POST['search_text']);
		$sort_text = $_POST['sort'];
		$loc = $_POST['loc'];

		$search_query_text = '';

		if ($subsections != '') {
			$search_query_text .= " and `group_id` IN (" . $subsections . ") ";
		}
		if ($loc == 1) {
			// if ($subsections != '') {
			// 	$search_query_text .= ' and ';
			// } 
			if ($search_query_text == '') {
				$search_query_text .= ' and ';
			}
			$search_query_text .= ' `city_id` = ' . $city_id . ' ';
		}

		if ($search_text != '') {
			// if ($subsections != '' or $loc == 1) {
			// 	$search_query_text .= ' and ';
			// }
			if ($search_query_text == '') {
				$search_query_text .= ' and ';
			}
			$search_query_text .= " (LOWER(`title`) LIKE '%" . $search_text . "%' OR LOWER(`body`) LIKE '%" . $search_text . "%') ";
		} else {
			// $search_query_text .= ' 1 ';
		}

		if ($sort_text == 'Сначала последние') {
			$search_query_text .= " ORDER BY `id` DESC ";
		}
		else if ($sort_text == 'Сначала старые') {
			$search_query_text .= " ORDER BY `id` ";
		}
		else if ($sort_text == 'По количеству ответов') {
			$search_query_text .= " ORDER BY `replies_count` DESC ";
		} 
		else {
			$search_query_text .= " ORDER BY `id` DESC ";
		}

		if ($limitTo != false) {
			$search_query_text .= " LIMIT " . $limitFrom . ", " . $limitTo;
		}

		// echo $search_query_text;
		$result = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `status` = 'standart' " . $search_query_text);
		$output = '';

		if ($result -> num_rows == 0) {
			echo '<div class="empty">Тут пусто :с</div>';
			// echo $search_query_text;
		} else {
			while ($r = mysqli_fetch_assoc($result)) {
				$local_user_id = $r['user_id'];
				$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));

				if (mb_strlen($r['title']) > 60) {
					// $r['title'] = mb_substr($r['title'], 0, 56) . '...';
				}
				$output .= '
				<a href="' . $link . '/interest-groups/record/?id=' . $r['id'] . '">
					<div class="interests-block">
						<div class="row-1">
							<div class="photo">
								<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
							</div>
						</div>
						<div class="row-2">
								<p>' . $r['title'] . '</p>
								<div class="info">
									<div class="subinfo">
										<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
										<div class="bullet-point"></div> ' . calcTime($r['date']) . '

										<div class="replies">
											<img src="' . $link . '/assets/img/icons/message.svg">
											' . $r['replies_count'] . '
										</div>
									</div>
									

									<div class="group">
										<img src="' . $interests_array[$r['group_id']]['icon'] . '">
										' . $interests_array[$r['group_id']][$lang . '_title'] . '
									</div>
								</div>

						</div>
					</div>
				</a>';
			}
			echo $output;
		}	
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}





if ($_POST['type'] == 'reply') {
	$user_id = decodeSecretID($_POST['secret_id'], 'reply');
	$message = strip_tags($_POST['message']);
	$reply_to = $_POST['reply_to'];
	$record_id = $_POST['record_id'];

	echo $message;

	if ($user_id) {
		
		if (str_replace(' ', '', $message) == '') {
			echo 'Invalid message';
			exit();
		}

		$replies = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `replies` FROM `interests_records` WHERE `id` = '$record_id'"))['replies'];

		if ($replies != '') {
			$replies = unserialize($replies); 
		} else {
			$replies = array();
		}

		if (count($replies) == 0) {
			$reply_to = '';
		}

		$replies_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `replies_count` FROM `interests_records` WHERE `id` = '$record_id' "))['replies_count'];

		if ($reply_to == '') {

			$replies[$replies_count + 1] = array(
				"user_id" => $user_id,
				"date" => date('Y-m-d H:i:s'),
				"message" => $message,
				"replies" => array()
			);
		} else {
			$reply_to = explode('&&', $reply_to);

			if (count($reply_to) != 2) {
				echo 'reply_to error';
				exit();
			}

			$reply_to_user_id = str_replace('user_', '', $reply_to[1]); // Прислать уведомление о том, что пользователю ответили
			$reply_to_reply_id = str_replace('reply_', '', $reply_to[0]);

			$replies[$reply_to_reply_id]['replies'][$replies_count + 1] = array(
				"user_id" => $user_id,
				"date" => date('Y-m-d H:i:s'),
				"message" => $message,
				"replies" => array()
			);
		}

		$replies = serialize($replies);
		mysqli_query($connection, "UPDATE `interests_records` SET `replies_count` = `replies_count` + 1, `replies` = '$replies' WHERE `id` = '$record_id' ");

		echo 'success';
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}





if ($_POST['type'] == 'get-replies') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getReplies');

	$replies = $_POST['replies'];
	if ($replies != '') {
		$replies = json_decode($replies);
	}
	$record_id = $_POST['record_id'];

	if ($user_id) {
		$record_data = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `id` = '$record_id' ");
		

		if ($record_data -> num_rows == 0) {
			echo 'Invalid record_id';
			exit();
		} else {
			$record_data = mysqli_fetch_assoc($record_data);
			$replies_from_db = $record_data['replies'];
			
			if ($replies_from_db == '' or count(unserialize($replies_from_db)) == 0) {
				// echo 'empty';
				exit();
			} else {
				$replies_from_db = unserialize($replies_from_db);
				// $replies = explode('_', $replies);

				$output_1 = '';
				$output_2 = array();

				foreach ($replies_from_db as $reply_id => $reply_data) {
					if (!in_array($reply_id, $replies)) {

						$reply_user_id = $reply_data['user_id'];

						$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$reply_user_id;'"));

						$author = '';
						if ($record_data['user_id'] == $reply_data['user_id']) {
							$author = '<div class="author">
							Автор темы
						</div>';
						}
						$output_1 .= '<div alt="reply_' . $reply_id . '" class="reply reply_user_' . $reply_data['user_id'] . '">

						<div id="reply_' . $reply_id . '" alt="user_' . $reply_data['user_id'] . '" class="post post-hidden">
							<div class="functions">
								<img class="add-reply-img" src="' . $link . '/assets/img/icons/message-plus.svg">
								<img class="add-reply-img" src="' . $link . '/assets/img/icons/heart.svg">
							</div>
							<div class="col-1">
							<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
								<div class="image">
									
									<img src="' . $local_user_data['photo'] . '" style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $local_user_data['photo'] . '">
									

								</div>
								</a>
							</div>
							<div class="col-2">
								<div class="name">
									<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
										<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
									</a>

									' . $author . '

										<div class="bullet-point"></div><div class="date">' . calcTime($reply_data['date']) . '</div>
								</div>

								<div class="body">
									' . $reply_data['message'] . '
								</div>
							</div>
						</div>
						</div>
						';
					}
				}

				foreach ($replies_from_db as $parent_reply_id => $reply_data) {
					foreach ($reply_data['replies'] as $reply_id => $reply_data) {
						$output_2_text = '';
						if (!in_array($reply_id, $replies)) {

							$reply_user_id = $reply_data['user_id'];

							$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$reply_user_id;'"));

							$author = '';
							if ($record_data['user_id'] == $reply_data['user_id']) {
								$author = '<div class="author">
								Автор темы
							</div>';
							}
							$output_2_text .= '

							<div id="reply_' . $reply_id . '" alt="user_' . $reply_data['user_id'] . '" class="post post-hidden">
								<div class="functions">
									<img class="add-reply-img" src="' . $link . '/assets/img/icons/message-plus.svg">
									<img class="add-reply-img" src="' . $link . '/assets/img/icons/heart.svg">
								</div>
								<div class="col-1">
								<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
									<div class="image">
										
										<img src="' . $local_user_data['photo'] . '" style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $local_user_data['photo'] . '">
										

									</div>
									</a>
								</div>
								<div class="col-2">
									<div class="name">
										<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
											<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
										</a>

										' . $author . '

											<div class="bullet-point"></div><div class="date">' . calcTime($reply_data['date']) . '</div>
									</div>

									<div class="body">
										' . $reply_data['message'] . '
									</div>
								</div>
							</div>
							</div>
							';

							array_push($output_2, array(
								'reply_id' => $reply_id,
								'parent_reply_id' => $parent_reply_id,
								'body' => $output_2_text
							));
							// $output_2[$reply_id]['body'] = $output_2_text;
							// $output_2[$reply_id]['parent_reply_id'] = $parent_reply_id;
						}
					}
				}
			}
		}
		
		echo json_encode(array($output_1, $output_2));
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}




if ($_POST['type'] == 'get-replies-date') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getRepliesDate');

	if ($user_id) {
		$replies = json_decode($_POST['replies']);
		$record_id = $_POST['record_id'];

		$record_data = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `id` = '$record_id'");

		if ($record_data -> num_rows == 0) {
			echo 'Invalid record_id';
			exit();
		}

		if (count($replies) == 0) {
			echo 'replies array is empty';
			exit();
		}

		$output = array();

		$record_data = mysqli_fetch_assoc($record_data);

		foreach ($replies as $incoming_reply_id) {
			foreach (unserialize($record_data['replies']) as $reply_id => $reply_data) {
				if ($incoming_reply_id == $reply_id) {
					// $output[$reply_id] = calcTime($reply_data['date']);
					array_push($output, array('reply_id' => $reply_id, 'date' => calcTime($reply_data['date'])));
				} else {
					foreach ($reply_data['replies'] as $reply_id => $reply_data) {
						if ($incoming_reply_id == $reply_id) {
							// $output[$reply_id] = calcTime($reply_data['date']);
							array_push($output, array('reply_id' => $reply_id, 'date' => calcTime($reply_data['date'])));
						}
					}
				}
			}
		}

		echo json_encode($output);
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}





if ($_POST['type'] == 'get-user-interests') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getUserInterests');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id) {
		$interests = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
		$interests_array = array();

		while ($i = mysqli_fetch_assoc($interests)) {
			$interests_array[$i['id']] = array('rus_title' => $i['rus_title'], 'eng_title' => $i['eng_title'], 'icon' => $link . $i['icon']);
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));
		
		$result = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `user_id` = '$local_user_id' ORDER BY `id` DESC");
		$output = '';

		if ($result -> num_rows == 0) {
			echo '<div class="empty">Тут пусто :с</div>';
		} else {
			while ($r = mysqli_fetch_assoc($result)) {

				if (mb_strlen($r['title']) > 60) {
					// $r['title'] = mb_substr($r['title'], 0, 56) . '...';
				}
				$buttons = '';
				if ($local_user_id == $user_id or $user_status == 'Admin') {

					$button_edit = '<button class="edit-interest">
								<img src="' . $link . '/assets/img/icons/edit.svg">
							</button>';
					$button_delete = '<button class="delete-interest">
								<img src="' . $link . '/assets/img/icons/trash.svg">
							</button>';
					$button_hide = '<button class="hide-interest">
								<img src="' . $link . '/assets/img/icons/eye.svg">
							</button>';
					if ($r['status'] == 'hidden') {
						$button_hide = '<button class="show-interest">
								<img src="' . $link . '/assets/img/icons/eye-off.svg">
							</button>';
					}
				}
				$output .= '
					<div class="interests-block ' . $r['status'] . '" id="interest_' . $r['id'] . '">
					<a href="' . $link . '/interest-groups/record/?id=' . $r['id'] . '">
						<div class="col-1">
							<div class="row-1">
								<div class="photo">
									<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" src="' . $user_data['photo'] . '">
								</div>
							</div>
							<div class="row-2">
									<p>' . $r['title'] . '</p>
									<div class="info">
										<div class="subinfo">
											<b>' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</b> 
											<div class="bullet-point"></div> ' . calcTime($r['date']) . '
										</div>
										<div class="group">
											<img src="' . $interests_array[$r['group_id']]['icon'] . '">
											' . $interests_array[$r['group_id']][$lang . '_title'] . '
										</div>
									</div>
							</div>
						</div>
						</a>
						<div class="col-2">
						' . $button_edit . $button_delete . $button_hide . '
						</div>
						
					</div>';
			}
			echo $output;
		}


		
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}








if ($_POST['type'] == 'hide-interest') {
	$user_id = decodeSecretID($_POST['secret_id'], 'hideInterest');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$interest_id = $_POST['interest_id'];

		mysqli_query($connection, "UPDATE `interests_records` SET `status` = 'hidden' WHERE `id` = '$interest_id'");
		echo $interest_id;
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}

if ($_POST['type'] == 'show-interest') {
	$user_id = decodeSecretID($_POST['secret_id'], 'showInterest');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$interest_id = $_POST['interest_id'];

		mysqli_query($connection, "UPDATE `interests_records` SET `status` = 'standart' WHERE `id` = '$interest_id'");
		echo $interest_id;
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}

if ($_POST['type'] == 'delete-interest') {
	$user_id = decodeSecretID($_POST['secret_id'], 'deleteInterest');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$interest_id = $_POST['interest_id'];

		mysqli_query($connection, "DELETE FROM `interests_records` WHERE `id` = '$interest_id'");

	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}





if ($_POST['type'] == 'get-record-data') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getRecordData');

	if ($user_id) {
		$interest_id = $_POST['interest_id'];
		$record_id = $_POST['record_id'];

		$record_data = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `id` = '$record_id'");

		if ($record_data -> num_rows == 0) {
			echo 'Invalid record_id';
		} else {
			$record_data = mysqli_fetch_assoc($record_data);

			$city_id = $record_data['city_id'];
			$group_id = $record_data['group_id'];

			$city_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$city_id'"))['rus_title'];
			$group_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `interests_subsections` WHERE `id` = '$group_id'"))['rus_title'];

			$output = array(
				"id" => $record_data['id'],
				"user_id" => $record_data['user_id'],
				"title" => $record_data['title'],
				"body" => $record_data['body'],
				"location" => $record_data['loc'],
				"city_id" => $record_data['city_id'],
				"city_title" => $city_title,
				"repost" => $record_data['repost'],
				"date" => $record_data['date'],
				"group_id" => $record_data['group_id'],
				"group_title" => $group_title,
				"replies" => $record_data['replies'],
				"replies_count" => $record_data['replies_count'],
				"status" => $record_data['status'],
				"edited" => $record_data['edited']
			);

			echo json_encode($output);
		}
		
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}





if ($_POST['type'] == 'edit-interest') {
	$user_id = decodeSecretID($_POST['secret_id'], 'editInterest');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$record_id = $_POST['record_id'];

	$record_data = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `id` = '$record_id'");

	if ($record_data -> num_rows == 0) {
		echo 'Record not found';
		exit();
	} 

	$record_owner_id = mysqli_fetch_assoc($record_data)['user_id'];

	if ($user_id == $record_owner_id or $user_status == 'Admin') {
		$group = $_POST['group'];
		$title = $_POST['title'];
		$body = $_POST['body'];
		$loc = $_POST['loc'];
		$repost = $_POST['repost'];


		if ($group == '') {
			echo 'The group name is empty';
			exit();
		}
		if ($title == '') {
			echo 'The title is empty';
			exit();
		}
		if ($body == '') {
			echo 'The main message is empty';
			exit();
		}
		if ($loc != 1) {
			$loc = 0;
		}
		if ($repost != 1) {
			$repost = 0;
		}

		$group_flag = 0;
		$groups_from_db = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
		while ($g = mysqli_fetch_assoc($groups_from_db)) {
			if (strpos($g[$lang.'_title'], $group) !== false) {
				$group_flag = 1;
				$group_id = $g['id'];
				break;
			}
		}

		if ($group_flag == 0) {
			echo 'Invalid group. А group with this name was not found';
			exit();
		}
		if (mb_strlen($title) < 2) {
			echo 'Title is too short';
			exit();
		}
		if (mb_strlen($body) < 2) {
			echo 'Main message is too short';
			exit();
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$user_city_id = $user_data['city_id'];

		if ($repost == 1) {
			$user_friends = $user_data['friends'];
			if ($user_friends != '') {
				$user_friends = unserialize($user_friends);
				if (count($user_friends) > 0) {
					// делать рассылку...
				}
			}
		}

		mysqli_query($connection, "UPDATE `interests_records` SET `title` = '$title', `body` = '$body', `loc` = '$loc', `city_id` = '$user_city_id', `repost` = '$repost', `group_id` = '$group_id', `last_redactor` = '$user_id' WHERE `id` = '$record_id' ");
		
		echo 'record edited';
	} else {
		echo $apiErrorCodes['1.1'];
	}
}









if ($_POST['type'] == 'get-user-records-from-profile') {
	// $user_id = decodeSecretID($_POST['secret_id'], 'getUserRecordsFromProfile');

	// if ($user_id) {
		$local_user_id = $_POST['local_user_id'];

		$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user_data -> num_rows == 0) {
			echo 'Invalid local_user_id';
			exit();
		}
		$local_user_data = mysqli_fetch_assoc($local_user_data);

		$local_user_records = mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `user_id` = '$local_user_id' and `status` = 'standart' LIMIT 0, 5");
		$local_user_records_count = mysqli_query($connection, "SELECT COUNT(*) FROM `interests_records` WHERE `user_id` = '$local_user_id' and `status` = 'standart'");

		if ($local_user_records -> num_rows == 0) {
			echo '';
			echo json_encode(array("type" => 'empty', 'html' => '<div class="empty">Тут пусто :с</div>'));
		} else {
			if ($_POST['data_type'] == 'get-count') {
				echo $local_user_records -> num_rows;
				exit();
			}

			$interests = mysqli_query($connection, "SELECT * FROM `interests_subsections`");
			$interests_array = array();

			while ($i = mysqli_fetch_assoc($interests)) {
				$interests_array[$i['id']] = array('rus_title' => $i['rus_title'], 'eng_title' => $i['eng_title'], 'icon' => $link . $i['icon']);
			}

			$backgrounds = array(
				"игры games" => array(
					"url" => $link . "/assets/img/subsections/games.jpg",
					"styles" => array(
						"top" => '133'
					)
				),
				"фильмы сериалы movies" => array(
					"url" => $link . "/assets/img/subsections/movies.jpg",
					"styles" => array(
						"top" => '-85'
					)
				),
				"спорт sport" => array(
					"url" => $link . "/assets/img/subsections/sport.jpg",
					"styles" => array(
						"top" => '244'
					)
				),
				"общение chating общения communication " => array(
					"url" => $link . "/assets/img/subsections/communication.jpg",
					"styles" => array(
						"top" => '131'
					)
				),
				"музыка music" => array(
					"url" => $link . "/assets/img/subsections/music.jpg",
					"styles" => array(
						"top" => '-13'
					)
				),
				"искусство art" => array(
					"url" => $link . "/assets/img/subsections/art.webp",
					"styles" => array(
						"top" => '55'
					)
				),
				"программирование programming coding" => array(
					"url" => $link . "/assets/img/subsections/coding.jpg",
					"styles" => array(
						"top" => '114'
					)
				),
				"дизайн design" => array(
					"url" => $link . "/assets/img/subsections/design.png",
					"styles" => array(
						"top" => '-21'
					)
				),
				"кулинария cooking" => array(
					"url" => $link . "/assets/img/subsections/cooking.jpg",
					"styles" => array(
						"top" => '0'
					)
				),
				"технологии tech technologies" => array(
					"url" => $link . "/assets/img/subsections/tech.jpg",
					"styles" => array(
						"top" => '-58'
					)
				),
				"биология biology" => array(
					"url" => $link . "/assets/img/subsections/biology.jpg",
					"styles" => array(
						"top" => '-50'
					)
				),
				"физика physics" => array(
					"url" => $link . "/assets/img/subsections/physics.jpg",
					"styles" => array(
						"top" => '-164'
					)
				),
				"математика mathematics" => array(
					"url" => $link . "/assets/img/subsections/math.jpg",
					"styles" => array(
						"top" => '-45'
					)
				),
				"химия chemistry химии химией" => array(
					"url" => $link . "/assets/img/subsections/chimestry.jpg",
					"styles" => array(
						"top" => '235'
					)
				),
				"иностранные языки" => array(
					"url" => $link . "/assets/img/subsections/languages.png",
					"styles" => array(
						"top" => '76'
					)
				)
			);

			$backgrounds_tags = array(
				"minecraft майнкрафт майн вайм ваймворлд vime vimeworld lastcraft" => array(
					"url" => $link . "/assets/img/subsections/games_minecraft.png",
					"styles" => array(
						"top" => '-18'
					)
				),
				"кс ксг ксго cs csgo counter strike csg" => array(
					"url" => $link . "/assets/img/subsections/games_csgo.jpg",
					"styles" => array(
						"top" => '100'
					)
				),
				"гта gta gtav gta5 гташка гташку" => array(
					"url" => $link . "/assets/img/subsections/games_gta.jpg",
					"styles" => array(
						"top" => '160'
					)
				),
				"дота доту dota dota2 дотка доточка дотку доточку dote" => array(
					"url" => $link . "/assets/img/subsections/games_dota2.jpg",
					"styles" => array(
						"top" => '7'
					)
				),
				"скайрим skyrim elders scrolls" => array(
					"url" => $link . "/assets/img/subsections/games_skyrim.jpg",
					"styles" => array(
						"top" => '85'
					)
				),
				"among us amogus амогус амонг амонгас" => array(
					"url" => $link . "/assets/img/subsections/games_among_us.jpg",
					"styles" => array(
						"top" => '-40'
					)
				),"wot world of tanks танки blitz" => array(
					"url" => $link . "/assets/img/subsections/games_wot.jpg",
					"styles" => array(
						"top" => '-40'
					)
				),
				"apex апекс арех" => array(
					"url" => $link . "/assets/img/subsections/games_apex.jpg",
					"styles" => array(
						"top" => '169'
					)
				),"pubg пубг unknown grounds" => array(
					"url" => $link . "/assets/img/subsections/games_pubg.jpg",
					"styles" => array(
						"top" => '21'
					)
				),
				"roblox роблокс роблок роблох" => array(
					"url" => $link . "/assets/img/subsections/games_roblox.webp",
					"styles" => array(
						"top" => '68'
					)
				),"мк мортал комбат mk mortal kombat" => array(
					"url" => $link . "/assets/img/subsections/games_mk.webp",
					"styles" => array(
						"top" => '115'
					)
				),"рояль royale" => array(
					
				),
				"бравл брав brawl stars старс" => array(
					"url" => $link . "/assets/img/subsections/games_brawl_stars.jpg",
					"styles" => array(
						"top" => '-27'
					)
				)
			);

			function getBackgroundImage ($title, $body, $subsection_title) {
				global $backgrounds_tags;
				global $backgrounds;

				// echo $subsection_title . ' -- ';

				$title = strtolower($title);
				$body = strtolower($body);
				$subsection_title = strtolower($subsection_title);

				$text = $title . ' ' . $body;

				foreach ($backgrounds_tags as $tags => $data) {
					$tags_array = explode(' ', $tags);
					foreach ($tags_array as $tag) {
						if (strpos($text, strtolower($tag)) !== false) {
							return array("url" => $data["url"], "styles" => $data["styles"]);
						}
					}	
				}

				foreach ($backgrounds as $subsections => $data) {
					$subsections_array = explode(' ', $subsections);
					foreach ($subsections_array as $subsection) {
						if (!empty($subsection_title) and !empty($subsection)) {
							if (strpos($subsection_title, $subsection) !== false) {
							
								return array("url" => $data["url"], "styles" => $data["styles"]);
							}
						}
						
					}
				}
			};

			$output = '';

			while ($r = mysqli_fetch_assoc($local_user_records)) {
				$backgroundImage = getBackgroundImage($r['title'], $r['body'], ' - ' . $interests_array[$r['group_id']]['rus_title'] . ' ' . $interests_array[$r['group_id']]['eng_title']);

				// echo var_dump($backgroundImage) . '<br><br>';
				
				$local_user_records_count++;

				$title = $r['title'];
				$body = $r['body'];
				if (mb_strlen($r['title']) > 175) {
					$title = substr($r['title'], 0, 170) . '...';
				}

				if (mb_strlen($r['body']) > 250) {
					$body = substr($r['body'], 0, 240) . '...';
				}

				$output .= '
				<a href="' . $link . '/interest-groups/record/?id=' . $r['id'] . '">
					<div class="interests-block">
								
							<div class="background">
								<img style="top: ' . $backgroundImage['styles']['top'] . 'px;" src="' . $backgroundImage['url'] . '">
							</div>
							<div class="row-1">
									<div class="photo">
									<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
								</div>
								</div>
							<div class="row-2">
									<p data="' . $r['title'] . '">' . $title . '</p>
									<p data="' . $r['body'] . '">' . $body . '</p>
									<div class="info">
										<div class="subinfo">
											<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
											<div class="bullet-point"></div> ' . calcTime($r['date']) . '
										</div>
										<div class="group">
											<img src="' . $interests_array[$r['group_id']]['icon'] . '">
											' . $interests_array[$r['group_id']][$lang . '_title'] . '
										</div>
									</div>

							</div>
						</div>
				</a>';
				
				
			}
			

			

			echo json_encode(array("type" => 'records', 'html' => $output, 'count' => mysqli_fetch_assoc($local_user_records_count)['COUNT(*)']));
			// echo json_encode($output);
		}
		
	// } 
	// else {
	// 	echo $apiErrorCodes['1.1'];
	// }
}

