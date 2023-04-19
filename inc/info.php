<?php session_start();

// $old_link = "http://frmjdg.com1";
$old_link = "http://frmjdg.com";
$old_link = "http://findcreek.com";
$link = "http://frmjdg.com";
// $link = "https://findcreek.com";

$styles_ver = '?v=1';

// $link = "http://hi-icue.mcdir.me";

$standart_user_photo = '/assets/img/none.png';
$standart_user_bg_image = 'https://www.msetconf.org/wp-content/uploads/2018/10/abstract-grey-wallpaper-14.jpg';

$_SESSION['server_timezone'] = 180; // В минутах

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

$apiErrorCodes = array(
	"1.1" => "1. Invalid secret ID"
);

// Правильный падеж слова "минут"
//Пример 4 минут назад -> 4 минуты назад
function caseOfMinutes ($minutes) {
	$length = strlen((string) $minutes);
	$last_number = (int) substr((string) $minutes, $length-1, $length);
	$text = ' минут';
	if ($last_number == 1) {
		$text = ' минуту';
	}
	if (in_array($last_number, array(2, 3, 4))) {
		$text = ' минуты';
	}
	if (in_array($minutes, array(10, 11, 12, 13, 14, 15, 16, 17, 18, 19))) {
		$text = ' минут';
	}
	return $minutes . $text;
}

// Расшифровка секретного айди пользователя
// Если расшифровано, то вернет айди, если нет, то FALSE
function decodeSecretID ($secret_id, $func) {
	global $connection;

	$tokens = mysqli_query($connection, "SELECT `token` FROM `users`");

	$decode_id = 0;
	$flag = 0;

	while ($t = mysqli_fetch_assoc($tokens)['token']) {
		if ($secret_id == md5('user_' . $t . '_' . $func)) {
			$decode_id = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `users` WHERE `token` = '$t'"))['id'];
			$flag = 1;
			break;
		}
	}

	if (mysqli_query($connection, "SELECT `id` FROM `users` WHERE `id` = '$decode_id'") -> num_rows == 0) {
		$flag = 0;
	}

	if ($flag == 1) {
		return $decode_id;
	} else {
		return false;
	}
}

function searchUrl ($text) {
	$text_array = explode(' ', $text);

	foreach ($text_array as $key => $value) {
		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $value) == 1) {
			$text_array[$key] = '<a href="' . $value . '">' . $value . '</a>';
		}
	}

	return implode(' ', $text_array);
}