<?php
include_once "../inc/config.php";
// Авторизация для браузера - web_authorization.php

if ($_POST['step'] == 'check_email') {
	if ($_POST['email'] == '') {
		$output['success'] = false;
		$output['response'] = 'empty email';
		echoJSON($output);
		exit();
	} 

	$email = $_POST['email'];
	$result = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `email` = '$email'");

	if ($result -> num_rows != 0) {
		$output['response'] = 'registered email';
		echoJSON($output);
		exit();

	} 
	
	$output['response'] = 'unregistered email';
	echoJSON($output);
	exit();
}

if ($_POST['step'] == 'registration') {
	if ($_POST['email'] == '') {
		$output['success'] = false;
		$output['response'] = 'empty email';
		echoJSON($output);
		exit();

	} 
	$email = $_POST['email'];

	$result = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `email` = '$email'");

	if ($result -> num_rows != 0) {
		$output['success'] = false;
		$output['response'] = 'registered email';
		echoJSON($output);
		exit();
	} 
		
	$last_name = $_POST['surname'];
	if ($last_name == '' or strlen($last_name) < 2) {
		$output['success'] = false;
		$output['response'] = 'surname is too short';
		echoJSON($output);
		exit();
	}

	$first_name = $_POST['first-name'];
	if ($first_name == '' or strlen($first_name) < 2) {
		$output['success'] = false;
		$output['response'] = 'name is too short';
		echoJSON($output);
		exit();
	}
	// $patronymic = $_POST['patronymic'];
	// if ($patronymic == '' or strlen($patronymic) < 2) {
	// 	exit();
	// }


	$education = $_POST['education'];
	$education_id = 0;

	$city = $_POST['city'];
	$city_id = 0;

	$profile_type = 0;

	// if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
	// 	echo 'invalid email';
	//   exit();
	// }

	$token = md5($last_name . $first_name . $email . 'ZmluZHN0dWRlbnRzX2hhaGE=' . uniqid());

	if ($_POST['password'] == '' or strlen($_POST['password']) < 2) {
		$output['success'] = false;
		$output['response'] = 'password is too short';
		echoJSON($output);
		exit();
	}
	$password = md5($_POST['password'] . 'password_salt');

	mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`) VALUES ('$token', '$first_name', '$last_name', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id')");

	$output['success'] = true;
	$output['response']['token'] = $token;
	$output['response']['email'] = $email;
	$output['response']['first_name'] = $first_name;
	$output['response']['last_name'] = $last_name;
	echoJSON($output);
}



if ($_POST['step'] == 'authorization') {
	if ($_POST['email'] == '') {
		$output['success'] = false;
		$output['response'] = 'empty email';
		echoJSON($output);
		exit();
	} 

	if ($_POST['password'] == '') {
		$output['success'] = false;
		$output['response'] = 'empty password';
		echoJSON($output);
		exit();
	}

	$email = $_POST['email'];
	$password = md5($_POST['password'] . 'password_salt');

	$result = mysqli_query($connection, "SELECT `token` FROM `users` WHERE `email` = '$email' and `password` = '$password'");

	if ($result -> num_rows == 0) {
		$output['email'] = $email;
		$output['success'] = false;
		$output['response'] = 'invalid email or password';
		echoJSON($output);
		exit();
	} 
	
	$token = mysqli_fetch_assoc($result)['token'];

	$output['success'] = true;
	$output['response']['token'] = $token;
	echoJSON($output);	
	
}