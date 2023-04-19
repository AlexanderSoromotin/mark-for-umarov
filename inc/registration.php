<?php

include_once "db.php";
include_once "info.php";
	
$type = $_POST['type'];

if ($type == 'check') {
	$email = $_POST['email'];

	$result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$email' and `status` != 'deleted'");
	if ($result -> num_rows > 0) {
		echo 'true';
	} else {
		echo 'false';
	}
	exit();
}

$last_name = $_POST['last-name'];
if ($last_name == '' or strlen($last_name) < 2) {
	echo 'last-name too short';
	exit();
}

$first_name = $_POST['first-name'];
if ($first_name == '' or strlen($first_name) < 2) {
	echo 'first-name too short';
	exit();
}
// $patronymic = $_POST['patronymic'];
// if ($patronymic == '' or strlen($patronymic) < 2) {
// 	exit();
// }


$education = $_POST['education'];
$education_id = 0;

// $edu_result = mysqli_query($connection, "SELECT `id` FROM `education` WHERE `short_title` = '$education' ");
// if ($edu_result -> num_rows != 0) {
// 	$education_id = mysqli_fetch_assoc($edu_result)['id'];
// }

$city = $_POST['city'];
$city_id = 0;

// $city = preg_replace(" " . "/\([^\)]+\)/", '', $city);
// if (mb_substr($city, mb_strlen($city)-1) == ' ') {
//    $city = mb_substr($city, 0, mb_strlen($city)-1);
// }

// $city_result = mysqli_query($connection, "SELECT `id` FROM `cities` WHERE `rus_title` = '$city' ");
// if ($city_result -> num_rows != 0) {
// 	$city_id = mysqli_fetch_assoc($city_result)['id'];
// }

$profile_type = 0;
// if ($_POST['profile-type'] == 'Закрытый') {
// 	$profile_type = 1;
// }

// $sex = $_POST['sex'];
// if ($sex == '') {
// 	echo 'sex param is empty';
// 	exit();
// }

$email = $_POST['email'];
if ($email == '') {
	echo 'Invalid email';
	exit();
}
if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
// 	echo 'invalid email';
//   exit();
}

$token = md5($last_name . $first_name . $email . 'token_salt');

if ($_POST['password'] == '' or strlen($_POST['password']) < 8) {
	echo 'short password';
	exit();
}
$password = md5($_POST['password'] . 'password_salt');

mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`) VALUES ('$token', '$first_name', '$last_name', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id')");

// with patronimyc
// mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `patronymic`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`) VALUES ('$token', '$first_name', '$last_name', '$patronymic', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id')");

if (isset($_POST['redirect'])) {
	echo json_encode(array("success" => true, "token" => $token));
} else {
	setcookie("token", $token, time() + 3600 * 24 * 30, "/");
	setcookie("email", $email, time() + 3600 * 24 * 30, "/");

	header('Location: ' . $link);
}




























