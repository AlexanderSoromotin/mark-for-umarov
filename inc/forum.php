<?php

include_once "info.php";
include_once "db.php";
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

// Если в один момент на сервере и у клиента разные дни, то высчитываем настоящее время у клиента
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



if ($_POST['type'] == 'search-topics') {
	$user_id = decodeSecretID($_POST['secret_id'], 'searchTopics');

	if ($user_id) {
		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$city_id = $user_data['city_id'];
		$education_id = $user_data['education_id'];
		$friends = $user_data['friends'];

		if ($friends == '') {
			$friends = array();
		} else {
			$friends = unserialize($friends);
		}

		if (count($friends) != 0) {
			$friends = implode(',', $friends);
		}

		$limitTo = $_POST['limitTo'];
		$limitFrom = $_POST['limitFrom'];

		
		$search_text = mb_strtolower($_POST['search_text']);
		$sort_text = $_POST['sort'];

		$loc = $_POST['loc'];
		$education = $_POST['education'];
		$topics = $_POST['topics'];
		$articles = $_POST['articles'];
		$onlyFromFriends = $_POST['onlyFromFriends'];

		$search_query_text = '';

		if ($loc == 1) {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= ' and `city_id` = ' . $city_id . ' ';
		}

		if ($education == 1) {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= ' and `education_id` = ' . $education_id . ' ';
		}

		if ($topics == 1 and $articles == 0) {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= " and `type` = 'topic' ";
		}

		if ($topics == 0 and $articles == 1) {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= " and `type` = 'article' ";
		}

		if ($search_text != '') {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= " and (LOWER(`title`) LIKE '%" . $search_text . "%' OR LOWER(`body`) LIKE '%" . $search_text . "%') ";
		}

		if ($onlyFromFriends == 1) {
			// if ($search_query_text == '') {
			// 	$search_query_text .= ' and ';
			// }
			$search_query_text .= ' and `user_id` IN (' . $friends . ') ';
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
		$result = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `status` = 'standart' " . $search_query_text);
		$output = '';

		if ($result -> num_rows == 0) {
			echo '<div class="empty">Тут пусто :с</div>';
			// echo $search_query_text;
			// echo $search_query_text;
		} else {
			while ($t = mysqli_fetch_assoc($result)) {
				$local_user_id = $t['user_id'];
				$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));

				if (mb_strlen($t['title']) > 60) {
					// $t['title'] = mb_substr($r['title'], 0, 56) . '...';
				}

				if ($r['type'] == 'article') {
					$group = '
					<div class="group">
						<img src="http://frmjdg.com/assets/img/icons/news.svg">Статья
					</div>';
				} else {
					$group = '
					<div class="group">
						<img src="http://frmjdg.com/assets/img/icons/users.svg">
						Тема
					</div>
					';
					$group = '
					<div class="group">
					</div>
					';
					$group = '';
				}
				$output .= '
				<a href="' . $link . '/forum/topic/?id=' . $t['id'] . '">
					<div class="topic-block">
						<div class="background">
							<img src="">
						</div>

						<div class="body">
							<div class="row-1">
							<div class="photo">
								<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
							</div>
						</div>

						<div class="row-2">
								<p>' . $t['title'] . '</p>
								<div class="info">
									<div class="subinfo">
										<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
										<div class="bullet-point"></div> ' . calcTime($t['date']) . '

										<div class="replies">
											<img src="' . $link . '/assets/img/icons/message.svg">
											' . $t['replies_count'] . '
										</div>
									</div>
									
								' . $group . '
								</div>

						</div>
						</div>
					</div>
				</a>
				';

			}
			echo $output;
		}
	}
	else {
		echo $apiErrorCodes["1.1"];
	}
}




if ($_POST['type'] == 'add-topic') {
	$user_id = decodeSecretID($_POST['secret_id'], 'addTopic');

	if ($user_id) {
		$title = $_POST['title'];
		$body = $_POST['body'];

		$loc = $_POST['loc'];
		$education = $_POST['education'];

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
		if ($education != 1) {
			$education = 0;
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));

		$user_city_id = $user_data['city_id'];
		$user_education_id = $user_data['education_id'];
		
		if (mb_strlen($title) < 2) {
			echo 'Title is too short';
			exit();
		}
		if (mb_strlen($body) < 2) {
			echo 'Main message is too short';
			exit();
		}

		mysqli_query($connection, "INSERT INTO `forum_topics` (`user_id`, `title`, `body`, `loc`, `city_id`, `education`, `education_id`) VALUES ('$user_id', '$title', '$body', '$loc', '$user_city_id', '$education', '$user_education_id') ");

		echo 'topic added';
	} 
	else {
		echo $apiErrorCodes["1.1"];
	}
}



if ($_POST['type'] == 'get-user-topics') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getUserTopics');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id) {
		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));
		
		$result = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `user_id` = '$local_user_id' ORDER BY `id` DESC");
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

					$button_edit = '<button class="edit-topic">
								<img src="' . $link . '/assets/img/icons/edit.svg">
							</button>';
					$button_delete = '<button class="delete-topic">
								<img src="' . $link . '/assets/img/icons/trash.svg">
							</button>';
					$button_hide = '<button class="hide-topic">
								<img src="' . $link . '/assets/img/icons/eye.svg">
							</button>';
					if ($r['status'] == 'hidden') {
						$button_hide = '<button class="show-topic">
								<img src="' . $link . '/assets/img/icons/eye-off.svg">
							</button>';
					}
				}
				$output .= '
					<div class="topic-block ' . $r['status'] . '" id="topic_' . $r['id'] . '">
					<a href="' . $link . '/forum/topic/?id=' . $r['id'] . '">
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




if ($_POST['type'] == 'hide-topic') {
	$user_id = decodeSecretID($_POST['secret_id'], 'hideTopic');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$topic_id = $_POST['topic_id'];

		mysqli_query($connection, "UPDATE `forum_topics` SET `status` = 'hidden' WHERE `id` = '$topic_id'");
		echo $topic_id;
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}




if ($_POST['type'] == 'show-topic') {
	$user_id = decodeSecretID($_POST['secret_id'], 'showTopic');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$topic_id = $_POST['topic_id'];

		mysqli_query($connection, "UPDATE `forum_topics` SET `status` = 'standart' WHERE `id` = '$topic_id'");
		echo $topic_id;
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}




if ($_POST['type'] == 'delete-topic') {
	$user_id = decodeSecretID($_POST['secret_id'], 'deleteTopic');
	$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];
	$local_user_id = $_POST['user_id'];

	if ($user_id and ($local_user_id == $user_id or $user_status == 'Admin')) {
		$topic_id = $_POST['topic_id'];

		mysqli_query($connection, "DELETE FROM `forum_topics` WHERE `id` = '$topic_id'");

	} 
	else {
		echo $apiErrorCodes['1.1'];
	}

}



if ($_POST['type'] == 'get-topic-data') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getTopicData');

	if ($user_id) {
		$topic_id = $_POST['topic_id'];

		$topic_data = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `id` = '$topic_id'");

		if ($topic_data -> num_rows == 0) {
			echo 'Invalid topic_id';
		} else {
			$topic_data = mysqli_fetch_assoc($topic_data);

			$education = $topic_data['education'];

			$city_id = $topic_data['city_id'];
			$education_id = $topic_data['education_id'];

			$city_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$city_id'"))['rus_title'];
			$education_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"))['short_title'];

			$output = array(
				"id" => $topic_data['id'],
				"user_id" => $topic_data['user_id'],
				"title" => $topic_data['title'],
				"body" => $topic_data['body'],
				"location" => $topic_data['loc'],
				"city_id" => $topic_data['city_id'],
				"city_title" => $city_title,
				"date" => $topic_data['date'],
				"education_id" => $topic_data['group_id'],
				"education_title" => $group_title,
				"replies" => $topic_data['replies'],
				"replies_count" => $topic_data['replies_count'],
				"status" => $topic_data['status'],
				"edited" => $topic_data['edited']
			);

			echo json_encode($output);
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
	$topic_id = $_POST['topic_id'];

	// echo $message;

	if ($user_id) {
		
		if (str_replace(' ', '', $message) == '') {
			echo 'Invalid message';
			exit();
		}

		$replies = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `replies` FROM `forum_topics` WHERE `id` = '$topic_id'"))['replies'];

		if ($replies != '') {
			$replies = unserialize($replies); 
		} else {
			$replies = array();
		}

		if (count($replies) == 0) {
			$reply_to = '';
		}

		$replies_count = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `replies_count` FROM `forum_topics` WHERE `id` = '$topic_id' "))['replies_count'];

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
		mysqli_query($connection, "UPDATE `forum_topics` SET `replies_count` = `replies_count` + 1, `replies` = '$replies' WHERE `id` = '$topic_id' ");

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
	$topic_id = $_POST['topic_id'];

	if ($user_id) {
		$topic_data = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `id` = '$topic_id' ");
		

		if ($topic_data -> num_rows == 0) {
			echo 'Invalid topic_id';
			exit();
		} else {
			$topic_data = mysqli_fetch_assoc($topic_data);
			$replies_from_db = $topic_data['replies'];
			
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
						if ($topic_data['user_id'] == $reply_data['user_id']) {
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
							if ($topic_data['user_id'] == $reply_data['user_id']) {
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
		$topic_id = $_POST['topic_id'];

		$topic_data = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `id` = '$topic_id'");

		if ($topic_data -> num_rows == 0) {
			echo 'Invalid topic_id';
			exit();
		}

		if (count($replies) == 0) {
			echo 'replies array is empty';
			exit();
		}

		$output = array();

		$topic_data = mysqli_fetch_assoc($topic_data);

		foreach ($replies as $incoming_reply_id) {
			foreach (unserialize($topic_data['replies']) as $reply_id => $reply_data) {
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
