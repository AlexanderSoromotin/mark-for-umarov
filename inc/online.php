<?php 
include_once "info.php";
include_once "db.php";


if ($_POST['type'] == 'set-last-online') {
	$timezone = $_POST['user_timezone'];
	$hash_token = $_POST['hash_token'];

	if ($hash_token != md5('')) {
		$email = $_COOKIE['email'];

		$auto_inc = mysqli_fetch_assoc(mysqli_query($connection, "SHOW TABLE STATUS WHERE `name` LIKE 'users'"))['Auto_increment'];
		$users = mysqli_query($connection, 'SELECT * FROM `users`');
		$flag = 0;
		while ($u = mysqli_fetch_assoc($users)) {
			if (md5($u['token']) == $hash_token) {
				$flag++;
			}
		}
		if ($flag == 0) {
			echo "reload!";
		}

		$current_date = date('Y-m-d G:i:s');

		$result = mysqli_query($connection, "UPDATE `users` SET `last_online` = '$current_date' WHERE `email` = '$email' and `status` != 'deleted' ");
	}
	$_SESSION['user_timezone'] = $timezone;
}

if ($_POST['type'] == 'get-online') {
	$users = $_POST['users'];

	if ($users == '') {
		echo 'users array is empty';
		exit();
	} else {
		$users = json_decode($users);
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

	$output = array();

	foreach ($users as $user_id) {
		$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `last_online` FROM `users` WHERE `id` = '$user_id'"));

		$ending = '';
		if ($local_user_data['sex'] == 'Женский') {
			$ending = 'а';
		}

		// Дата последнего посещения по часовому поясу сервера
		$server_last_online_year = (int) mb_substr($local_user_data['last_online'], 0, 4);
		$server_last_online_month = (int) mb_substr($local_user_data['last_online'], 5, 2);
		$server_last_online_day = (int) mb_substr($local_user_data['last_online'], 8, 2);

		$server_last_online_hour = (int) mb_substr($local_user_data['last_online'], 11, 2);
		$server_last_online_minute = (int) mb_substr($local_user_data['last_online'], 14, 2);

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

		$date = '';
		if ($client_current_year != $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			$date = 'Был' . $ending . ' в сети ' . addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}

		else if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
			$date = 'Онлайн';

		}

		else if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
			$date = 'Был' . $ending . ' в сети ' . caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

		}
		else if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			$date = 'Был' . $ending . ' в сети вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			$date = 'Был' . $ending . ' в сети сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			$date = 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			$date = 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		$output[$user_id] = $date;

	}
	echo json_encode($output);

}





// echo $current_date;
// echo $current_date . '/ CD: ' . mysqli_fetch_assoc( mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$email' ") )['last_online'];
// echo $timezone;