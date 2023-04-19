<?php

include "../db.php";
include "../info.php";
// echo 
$type = $_POST['type'];
$output = array();

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
	$output['success'] = false;
	$output['response'] = 'Invalid email or password';
	echo json_encode($output, JSON_UNESCAPED_UNICODE);
	exit();
	
} else {
	$token = mysqli_fetch_assoc($result)['token'];

$output['success'] = true;
$output['response'] = 'authorization is successful';
$output['token'] = $token;
echo json_encode($output, JSON_UNESCAPED_UNICODE);
	

}



























