<?php

include "db.php";
include "info.php";
// echo 
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

$email = $_POST['email'];
$password = md5($_POST['password'] . 'password_salt');

$result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$email' and `password` = '$password' and `status` != 'deleted'");
// echo '-' . $_POST['redirect'] . '-';

if ($result -> num_rows == 0) {
	
		
// 	if ($_POST['redirect'] == "true" or $_POST['redirect'] == true) {
		setcookie("auth-error", "Вы ввели неверный адрес электронной почты или пароль", time() + 3600, "/");
    	setcookie("auth-email", $email, time() + 3600, "/");
    	// echo $password . ' - ';
    	// echo $email;
    	header("Location: " . $link . '/secret-findcreek');
// 	} else {
// 	    echo json_encode(array("success" => false, "error" => 'Неверный адрес электронной почты или пароль', "email" => $email, "password" => $_POST['password']), JSON_UNESCAPED_UNICODE);
// 	}
	exit();
	
} else {
	$token = mysqli_fetch_assoc($result)['token'];
	
	
// 	if ($_POST['redirect'] == "true" or $_POST['redirect'] == true) {
		setcookie("token", $token, time() + 3600 * 24 * 30, "/");
		setcookie("email", $email, time() + 3600 * 24 * 30, "/");
		header("Location: " . $link . '');
// 	} else {
// 	    echo json_encode(array("success" => true, "token" => $token), JSON_UNESCAPED_UNICODE);
// 	}
}



























