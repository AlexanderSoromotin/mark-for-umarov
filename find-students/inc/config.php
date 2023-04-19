<?php session_start();

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_url = "127.0.0.1";
$db_username = "root";
$db_password = "";
// $db_password = "root";
$db_name = "findstudents";

// $db_url = "localhost";
// $db_username = "findstudents";
// $db_password = "ilovefindcreek";
// $db_name = "findstudents";

$link = 'http://frmjdg.com/find-students';
// $link = 'https://mark.findcreek.com';

$main_css_cache_ver = '?v=12';

$_SESSION['server_timezone'] = 180; // В минутах

// Соединение с БД
$connection = mysqli_connect($db_url, $db_username, $db_password, $db_name);
$connection -> set_charset("utf8mb4");

function caseOfWords ($count, $word) {

	$length = strlen((string) $count);
	$last_number = (int) substr((string) $count, $length-1, $length);
	
	if ($word = 'минуты') {
		$text = ' минут';
		if ($last_number == 1) {
			$text = ' минуту';
		}
		if (in_array($last_number, array(2, 3, 4))) {
			$text = ' минуты';
		}
		if (in_array($count, array(10, 11, 12, 13, 14, 15, 16, 17, 18, 19))) {
			$text = ' минут';
		}
	}
	if ($word = 'студенты') {
		$text = ' студентов';
		if ($last_number == 1) {
			$text = ' студент';
		}
		if (in_array($last_number, array(2, 3, 4))) {
			$text = ' студента';
		}
		if (in_array($count, array(10, 11, 12, 13, 14, 15, 16, 17, 18, 19))) {
			$text = ' студентов';
		}
	}
	return $text;
}

$months = array(
	"01" => "Январь",
	"02" => "Февраль",
	"03" => "Март",
	"04" => "Апрель",
	"05" => "Май",
	"06" => "Июнь",
	"07" => "Июль",
	"08" => "Август",
	"09" => "Сентябрь",
	"10" => "Октябрь",
	"11" => "Ноябрь",
	"12" => "Декабрь"
);
$months_accusative = array(
	"01" => "Января",
	"02" => "Февраля",
	"03" => "Марта",
	"04" => "Апреля",
	"05" => "Мая",
	"06" => "Июня",
	"07" => "Июля",
	"08" => "Августа",
	"09" => "Сентября",
	"10" => "Октября",
	"11" => "Ноября",
	"12" => "Декабря",
	1 => "Января",
	2 => "Февраля",
	3 => "Марта",
	4 => "Апреля",
	5 => "Мая",
	6 => "Июня",
	7 => "Июля",
	8 => "Августа",
	9 => "Сентября",
	10 => "Октября",
	11 => "Ноября",
	12 => "Декабря"
);

$months_short = array(
	"01" => "янв.",
	"02" => "фев.",
	"03" => "мар.",
	"04" => "апр.",
	"05" => "май.",
	"06" => "июн.",
	"07" => "июл.",
	"08" => "авг.",
	"09" => "сен.",
	"10" => "окт.",
	"11" => "ноя.",
	"12" => "дек.",
	1 => "янв.",
	2 => "фев.",
	3 => "мар.",
	4 => "апр.",
	5 => "май.",
	6 => "июн.",
	7 => "июл.",
	8 => "авг.",
	9 => "сен.",
	10 => "окт.",
	11 => "ноя.",
	12 => "дек."
);

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

function echoJSON ($var) {
	$var = json_encode($var, JSON_UNESCAPED_UNICODE);
	echo $var;
}

function exitIfTokenIsNull ($user_token) {
	if ($user_token == '') {
		$output['success'] = false;
		$output['response'] = 'Invalid user token';
		echoJSON($output);
		exit();
	}
}

?>