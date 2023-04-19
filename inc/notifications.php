<?
if (isset($_COOKIE['token'])) {
	include_once 'info.php';
	include_once 'db.php';

	// Для повышения безопасности можно подключить проверку прихоядещего id из секретного id. Сравнивать его с настоящим айди пользователя

	// Можно подключить только при отображении всех увеослений (все уведомления загружаются при загрузке страницы) Мб этот файл уже подключен далее по скрипту (get-all)
	// include_once 'userData.php';


	if ($_POST['secret_id'] == '') {
		exit();
	}

	// Если приходит запрос на изменение статуса всех уведомлений на "просмотрено", то выполняем запрос и завершаем скрипт
	if ($_POST['type'] == 'viewed-notifications') {

		$user_id = decodeSecretID($_POST['secret_id'], 'viewedNotifications');

		if ($user_id) {
			mysqli_query($connection, "UPDATE `notifications` SET `status` = 2 WHERE `incoming_id` = '$user_id'");	
		} else {
			echo $apiErrorCodes['1.1'];
		}
		exit();
	}


	// Разница между часовыми поясами
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

	function calcTime () {
		// Имеем client_last_online - время последнего посещения по часовому поясу клиента
		global $client_year;
		global $client_month;
		global $client_day;
		global $client_minutes;

		// Имеем client_current - время на данный момент по часовому поясу клиента
		global $client_current_year;
		global $client_current_month;
		global $client_current_day;
		global $client_current_minutes;

		global $months_accusative;

		if ($client_current_year != $client_year) {
			$hour = intdiv($client_minutes, 60);
			$minute = $client_minutes - $hour * 60;
			return '' . addZeroes($client_day) . ' ' . mb_strtolower($months_accusative[$client_month]) . ' ' . $client_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}

		if ($client_current_day == $client_day and $client_current_year == $client_year and $client_current_minutes - $client_minutes == 0) {
			return '' . 'Только что';

		}

		else if ($client_current_day == $client_day and $client_current_year == $client_year and $client_current_minutes - $client_minutes < 60) {
			return '' . caseOfMinutes($client_current_minutes - $client_minutes) . ' назад';

		}
		else if ($client_current_day - $client_day == 1 and $client_current_year == $client_year) {
			$hour = intdiv($client_minutes, 60);
			$minute = $client_minutes - $hour * 60;
			return '' . 'Вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_day - $client_day == 0 and $client_current_year == $client_year) {
			$hour = intdiv($client_minutes, 60);
			$minute = $client_minutes - $hour * 60;
			return '' . 'Сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_day - $client_day > 1 and $client_current_year == $client_year and $client_current_month == $client_month) {
			$hour = intdiv($client_minutes, 60);
			$minute = $client_minutes - $hour * 60;
			return '' . $client_day . ' ' . mb_strtolower($months_accusative[$client_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else {
			$hour = intdiv($client_minutes, 60);
			$minute = $client_minutes - $hour * 60;
			return '' . $client_day . ' ' . mb_strtolower($months_accusative[$client_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
	}


	$user_id = decodeSecretID($_POST['secret_id'], 'getNotifications');

	// Айди пользователя расшифровано
	if ($user_id) {
		$output = '';

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$user_first_name = $user_data['first_name'];

		

		if ($_POST['type'] == 'get-new') {
			$result = mysqli_query($connection, "SELECT * FROM `notifications` WHERE `incoming_id` = '$user_id' and `status` = 0");
		}

		else if ($_POST['type'] == 'get-all') {
			$limit = $_POST['limit'];
			$result = mysqli_query($connection, "SELECT * FROM `notifications` WHERE `incoming_id` = '$user_id' and `status` != 0 ORDER BY `id` DESC LIMIT 0, 30 ");
		} 

		else {
			exit();
		}


		while ($n = mysqli_fetch_assoc($result)) {
			$body = unserialize($n['body']);
			$n_id = $n['id'];

			// Создано, но ещё не прислано
			if ($n['status'] == 0) {
				$notification_classname = 'not-viewed hidden';
				mysqli_query($connection, "UPDATE `notifications` SET `status` = 1 WHERE `id` = '$n_id'");
			}
			// Создано и прислано, но не просмотрено
			if ($n['status'] == 1) {
				$notification_classname = 'not-viewed';
			}
			// Просмотрено
			if ($n['status'] == 2) {
				$notification_classname = '';
			}


			// Дата уведмления по часовому поясу сервера
			$server_year = (int) mb_substr($n['date'], 0, 4);
			$server_month = (int) mb_substr($n['date'], 5, 2);
			$server_day = (int) mb_substr($n['date'], 8, 2);

			$server_hour = (int) mb_substr($n['date'], 11, 2);
			$server_minute = (int) mb_substr($n['date'], 14, 2);

			// $server_minutes = $server_hour * 60 + $server_minute + $timezone;
			$server_minutes = $server_hour * 60 + $server_minute;

			// Дата последнего посещения по часовому поясу клиента
			$client_year = $server_year;
			$client_month = $server_month;
			$client_day = $server_day;
			$client_minutes = $server_minutes;

			// Если в на сервере и у клиента разные дни, то высчитываем последнее время посещения от лица клиента
			if ($client_minutes >= 1440) {

				$client_day++;
				$client_minutes -= 1440;

				if (cal_days_in_month(CAL_GREGORIAN, $server_month, $server_year) < $client_day) {
					$client_month++;
					$client_day = 1;

					if ($client_month > 12) {
						$client_year++;
						$client_month = 1;
					}
				}
			}

			if ($n['type'] == 'add-friend') {
				$outgoing_id = $body['outgoing_id'];
				$outgoing_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$outgoing_id'"));


				$output .= '
					<li id="notification_' . $n['id'] . '" class="' . $notification_classname . '">
						<a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_id . '">
							<div draggable="false" class="image">
								<img style="' . unserialize($outgoing_user['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($outgoing_user['photo_style'])['scale'] . ');" src="' . $outgoing_user['photo'] . '">
							</div>
						</a>
						<div class="body">
							<p><a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_user['id'] . '"><b>' . $outgoing_user['last_name'] . ' ' . $outgoing_user['first_name'] . '</b></a> хочет добавить вас в друзья</p>
							<p class="date">' . calcTime() . '</p>
							<p>
								<a href="' . $link . '/profile/friends/?act=incoming-requests">
									<button class="button-3">Посмотреть заявки</button>
								</a>
							</p>
						</div>
					</li>';
			}

			else if ($n['type'] == 'friend-added') {
				$outgoing_id = $body['outgoing_id'];
				$outgoing_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$outgoing_id'"));

				$output .= '
					<li id="notification_' . $n['id'] . '" class="' . $notification_classname . '">
						<a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_id . '">
							<div draggable="false" class="image">
								<img style="' . unserialize($outgoing_user['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($outgoing_user['photo_style'])['scale'] . ');" src="' . $outgoing_user['photo'] . '">
							</div>
						</a>
						<div class="body">
							<p><a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_user['id'] . '"><b>' . $outgoing_user['last_name'] . ' ' . $outgoing_user['first_name'] . '</b></a> добавил Вас в свой список друзей.</p>
							<p class="date">' . calcTime() . '</p>
						</div>
					</li>';
			}

			else if ($n['type'] == 'support-response') {
				$ticket_id = $body['ticket_id'];
				$ticket = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `support_tickets` WHERE `id` = '$ticket_id'"));

				if (strlen($ticket['answer']) > 90) {
					$ticket['answer'] = mb_substr($ticket['answer'], 0, 90) . '...';
				}

				$output .= '
					<li id="notification_' . $n['id'] . '" class="' . $notification_classname . '">
						<a target="_blink" href="' . $link . '/support/tickets">
							<div draggable="false" class="image">
								<img style="transform: scale(.7);opacity: .8;" src="' . $link . '/assets/img/icons/headset.svg">
							</div>
						</a>
						<div class="body">
							<p>' . $user_first_name . ', пришёл ответ из службы поддержки:</p>
							<p class="support-response">"' . $ticket['answer'] . '"</p>
							<p class="date">' . calcTime() . '</p>
							<p>
								<a href="' . $link . '/support/tickets">
									<button class="button-3">Посмотреть</button>
								</a>
							</p>
						</div>
					</li>';
			}

			else if ($n['type'] == 'education-added') {
				$education_id = $body['id'];
				$education = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));

				$output .= '
					<li id="notification_' . $n['id'] . '" class="' . $notification_classname . '">
						<a target="_blink" href="' . $link . '/profile">
							<div draggable="false" class="image">
								<img style="transform: scale(.7);opacity: .8;" src="' . $link . '/assets/img/icons/school.svg">
							</div>
						</a>
						<div class="body">
							<p>' . $user_first_name . ', Ваша заявка на добавление учебного заведения была одобрена.</p>
							<p class="support-response">Полное название: "' . $education['title'] . '"</p>
							<p class="support-response">короткое название: "' . $education['short_title'] . '"</p>
							<p class="date">' . calcTime() . '</p>
							<p>
								<a href="' . $link . '/profile">
									<button class="button-3">Перейти в профиль</button>
								</a>
							</p>
						</div>
					</li>';
			}
			// статус - 1, если прислано, 2 - если просмотрено

			else if ($n['type'] == 'new-interest') {
				$outgoing_id = $body['outgoing_id'];
				$outgoing_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$outgoing_id'"));

				$subsection_id = $body['subsection_id'];
				$subsection_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `interests_subsections` WHERE `id` = '$subsection_id'"));

				$output .= '
					<li id="notification_' . $n['id'] . '" class="' . $notification_classname . '">
						<a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_id . '">
							<div draggable="false" class="image">
								<img style="' . unserialize($outgoing_user['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($outgoing_user['photo_style'])['scale'] . ');" src="' . $outgoing_user['photo'] . '">
							</div>
						</a>
						<div class="body">
							<p><a target="_blink" href="' . $link . '/profile/?id=' . $outgoing_user['id'] . '"><b>' . $outgoing_user['last_name'] . ' ' . $outgoing_user['first_name'] . '</b></a> добавил в свой список интересов запись с категорией "' . $subsection_data['rus_title'] . '"</p>

							<a href="' . $link . '/interest-groups/user/?id=' . $outgoing_id . '">
								<button class="button-3">Посмотреть</button>
							</a>
							<p class="date">' . calcTime() . '</p>
						</div>
					</li>';
			}
			
		}

		echo $output;
	} else {
		echo $apErrorCodes['1.1'];
	}
}