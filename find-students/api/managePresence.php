<?php

include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

// echo $user_token;

$user_timezone = $_POST['timezone'];
$_SESSION['user_timezone'] = $user_timezone;

$timezone = $_SESSION['user_timezone'] - $_SESSION['server_timezone'];

// echo 'ut:' . $_SESSION['user_timezone'] . '. st:' . $_SESSION['server_timezone'] . '. tt: ' . $timezone;

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
		// echo '--' . $date . '--';
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

if ($_POST['type'] == 'get-user-presence') {
	exitIfTokenIsNull($user_token);

	// echo $user_id;

	$date = date('d.m.Y');
	$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date' ORDER BY `id` LIMIT 0, 1");

	if ($archive -> num_rows == 0) {
		mysqli_query($connection, "INSERT INTO `visits_archive` (`group_id`, `date`) VALUES ('$user_group_id', '$date')");

		$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date' ORDER BY `id` LIMIT 0, 1");

		$students = array();
	} else {
		$archive = mysqli_fetch_assoc($archive);
		$students = json_decode($archive['students'], 1);

		if ($students == '') {
			$students = array();
		}
	}

	if (gettype($students[$user_id]) == 'array') {

		foreach ($students[$user_id]['history'] as $key => $value) {

			$students[$user_id]['history'][$key]['time'] = calcTime(date('Y-m-d').' '.mb_substr($value['time'], 11, 5).':00', 'user_date_presence_archive');

			// echo '==' . date('Y-d-m').' '.mb_substr($value['time'], 11, 5).':00' . '==';
		}
		$students[$user_id]['date'] = date('d ') . $months_short[date('m')] . date(' Y');
		$output['success'] = true;
		$output['response'] = $students[$user_id];
		echoJSON($output);
	} else {
		// $output = 1;
		echo 1;
	}
}

if ($_POST['type'] == 'add-user-presence') {
	exitIfTokenIsNull($user_token);
	$output = array();

	$date = date('d.m.Y');
	$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	if ($archive -> num_rows == 0) {
		mysqli_query($connection, "INSERT INTO `visits_archive` (`group_id`, `date`) VALUES ('$user_group_id', '$date')");
		$students = array();
	} else {
		$archive = mysqli_fetch_assoc($archive);
		$students = json_decode($archive['students'], 1);
	}

	if ($archive['students'] == '' or $archive['students'] == null) {
		$students = array();
	}

	if (gettype($students[$user_id]) == 'array') {
		// $students[$user_id] = array();
		if (!$user_is_head_student) {
			if (count($students[$user_id]) != 0) {
				if ($students[$user_id]['history'][count($students[$user_id]['history'])-1]['activity'] == 'forcibly-leave') {
					
					$output['success'] = false;
					$output['response'] = 'head student removed presence mark';
					echoJSON($output);
					exit();
				}
			}
		}

		$students[$user_id]['active'] = true;
		$students[$user_id]['student_name'] = $user_first_name;
		$students[$user_id]['student_surname'] = $user_last_name;

		if (gettype($students[$user_id]['history']) != 'array') {
			$students[$user_id]['history'] = array();
		}
		
		array_push($students[$user_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "join"
		));
	} else {
		$students[$user_id] = array();
		$students[$user_id]['active'] = true;
		$students[$user_id]['student_name'] = $user_first_name;
		$students[$user_id]['student_surname'] = $user_last_name;

		if (gettype($students[$user_id]['history']) != 'array') {
			$students[$user_id]['history'] = array();
		}

		array_push($students[$user_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "join"
		));

		// $students[$user_id]['history']
	}

	$output['success'] = true;
	$output['response'] = $students[$user_id];
	$students = json_encode($students, JSON_UNESCAPED_UNICODE);

	echoJSON($output);

	mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");
}






if ($_POST['type'] == 'remove-user-presence') {
	exitIfTokenIsNull($user_token);
	$timezone = $_POST['timezone'];
	$_SESSION['user_timezone'] = $timezone;
	$output = array();

	$date = date('d.m.Y');
	$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	if ($archive -> num_rows == 0) {
		mysqli_query($connection, "INSERT INTO `visits_archive` (`group_id`, `date`) VALUES ('$user_group_id', '$date')");
		$students = array();
	} else {
		$archive = mysqli_fetch_assoc($archive);
		$students = json_decode($archive['students'], 1);
	}

	if ($archive['students'] == '' or $archive['students'] == null) {
		$students = array();
	}

	if (gettype($students[$user_id]) == 'array') {
		// $students[$user_id] = array();

		$students[$user_id]['active'] = false;
		$students[$user_id]['student_name'] = $user_first_name;
		$students[$user_id]['student_surname'] = $user_last_name;

		if (gettype($students[$user_id]['history']) != 'array') {
			$students[$user_id]['history'] = array();
		}
		
		array_push($students[$user_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "leave"
		));

		$output['success'] = true;
		$output['response'] = $students[$user_id];
		echoJSON($output);

		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	} else {
		$students[$user_id] = array();
		$students[$user_id]['active'] = false;
		$students[$user_id]['student_name'] = $user_first_name;
		$students[$user_id]['student_surname'] = $user_last_name;

		if (gettype($students[$user_id]['history']) != 'array') {
			$students[$user_id]['history'] = array();
		}

		array_push($students[$user_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "leave"
		));

		// $students[$user_id]['history'] 

		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		// echo $students;
		$output['success'] = true;
		$output['response'] = $students[$user_id];
		echoJSON($output);
	}
}







if ($_POST['type'] == 'forcibly-add-user-presence') {
	exitIfTokenIsNull($user_token);
	$output = array();
	$student_id = $_POST['student_id'];

	if (mb_strpos($student_id, 'robot') !== false) {
		if ($user_group_data['robots'] != null) {
			$user_group_data['robots'] = json_decode($user_group_data['robots'], 1);
		}
		foreach ($user_group_data['robots'] as $key => $value) {
			if ($value['robot_id'] == $student_id) {
				$student_name = $value['robot_name'];
				$student_surname = $value['robot_surname'];
			}
		}
		
	} else {
		$student_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$student_id'");

		if ($student_data -> num_rows == 0) {
			$output['success'] = false;
			$output['response'] = 'student not found';
			echoJSON($output);
			exit();
		}
		$student_data = mysqli_fetch_assoc($student_data);
		$student_name = $student_data['first_name'];
		$student_surname = $student_data['last_name'];
	}

	if (!$user_is_head_student and $user_status != 'Admin') {
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}

	$date = date('d.m.Y');
	$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	if ($archive -> num_rows == 0) {
		mysqli_query($connection, "INSERT INTO `visits_archive` (`group_id`, `date`) VALUES ('$user_group_id', '$date')");
		$students = array();
	} else {
		$archive = mysqli_fetch_assoc($archive);
		$students = json_decode($archive['students'], 1);
	}

	if ($archive['students'] == '' or $archive['students'] == null) {
		$students = array();
	}

	if (gettype($students[$student_id]) == 'array') {
		// $students[$student_id] = array();

		$students[$student_id]['active'] = true;
		$students[$student_id]['student_name'] = $student_name;
		$students[$student_id]['student_surname'] = $student_surname;

		if (gettype($students[$student_id]['history']) != 'array') {
			$students[$student_id]['history'] = array();
		}
		
		array_push($students[$student_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "forcibly-join"
		));

		// $output = json_encode(, JSON_UNESCAPED_UNICODE);
		// $output = json_encode($output, JSON_UNESCAPED_UNICODE);
		$output['success'] = true;
		$output['response'] = $students[$student_id];
		echoJSON($output);

		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		// echo $output;
	} else {
		$students[$student_id] = array();
		$students[$student_id]['active'] = true;
		$students[$student_id]['student_name'] = $student_name;
		$students[$student_id]['student_surname'] = $student_surname;

		if (gettype($students[$student_id]['history']) != 'array') {
			$students[$student_id]['history'] = array();
		}

		array_push($students[$student_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "forcibly-join"
		));

		// $students[$student_id]['history'] 
		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		$output['success'] = true;
		$output['response'] = $students[$student_id];
		echoJSON($output);
	}
}






if ($_POST['type'] == 'forcibly-remove-user-presence') {
	exitIfTokenIsNull($user_token);

	if (!$user_is_head_student and $user_status != 'Admin') {
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}

	$timezone = $_POST['timezone'];
	$_SESSION['user_timezone'] = $timezone;
	$output = array();
	$student_id = $_POST['student_id'];

	if (mb_strpos($student_id, 'robot') !== false) {
		if ($user_group_data['robots'] != null) {
			$user_group_data['robots'] = json_decode($user_group_data['robots'], 1);
		}
		foreach ($user_group_data['robots'] as $key => $value) {
			if ($value['robot_id'] == $student_id) {
				$student_name = $value['robot_name'];
				$student_surname = $value['robot_surname'];
			}
		}
	} else {
		$student_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$student_id'");

		if ($student_data -> num_rows == 0) {
			$output['success'] = false;
			$output['response'] = 'student not found';
			echoJSON($output);
			exit();
		}
		$student_data = mysqli_fetch_assoc($student_data);
		$student_name = $student_data['first_name'];
		$student_surname = $student_data['last_name'];
	}

	$date = date('d.m.Y');
	$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	if ($archive -> num_rows == 0) {
		mysqli_query($connection, "INSERT INTO `visits_archive` (`group_id`, `date`) VALUES ('$user_group_id', '$date')");
		$students = array();
	} else {
		$archive = mysqli_fetch_assoc($archive);
		$students = json_decode($archive['students'], 1);
	}

	if ($archive['students'] == '' or $archive['students'] == null) {
		$students = array();
	}

	if (gettype($students[$student_id]) == 'array') {
		// $students[$student_id] = array();

		$students[$student_id]['active'] = false;
		$students[$student_id]['student_name'] = $student_name;
		$students[$student_id]['student_surname'] = $student_surname;

		if (gettype($students[$student_id]['history']) != 'array') {
			$students[$student_id]['history'] = array();
		}
		
		array_push($students[$student_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "forcibly-leave"
		));

		$output['success'] = true;
		$output['response'] = $students[$student_id];
		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		echoJSON($students);
	} else {
		$students[$student_id] = array();
		$students[$student_id]['active'] = false;
		$students[$student_id]['student_name'] = $student_name;
		$students[$student_id]['student_surname'] = $student_surname;

		if (gettype($students[$student_id]['history']) != 'array') {
			$students[$student_id]['history'] = array();
		}

		array_push($students[$student_id]['history'], array(
			"time" => date('Y.m.d H:i:s'),
			"activity" => "forcibly-leave"
		));

		// $students[$student_id]['history'] 

		$output = array();
		$output['success'] = true;
		$output['response'] = $students;
		$students = json_encode($students, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$students' WHERE `group_id` = '$user_group_id' and `date` = '$date'");

		echoJSON($output);
	}
}






// user_id: {
// 	active: true/false,
// 	history: {
// 		{
// 			time: '12.03.2022 12:43:45',
// 			activity: 'join/leave',
// 			html: 'HTML'
// 		},
// 		{
// 			time: '12.03.2022 12:59:45',
// 			activity: 'join/leave',
// 			html: 'HTML'
// 		}
// 	}
// }

