<?php
include_once "../inc/config.php";

function getRandInt ($length) {
	$output = '';
	for ($i = 0; $i < $length; $i++) { 
		$output .= rand(0, 9);
	};
	return $output;
}

if ($_POST['type'] == 'send-recovery-code') {
	$code = getRandInt(5);
	$recovery_token = uniqid();
	$email = $_POST['email'];

	$check_email = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `email` = '$email'");

	if ($check_email -> num_rows != 0) {
		mysqli_query($connection, "INSERT INTO `recovery_password_codes` (`code`, `email`, `recovery_token`) VALUES ('$code', '$email', '$recovery_token')");

		$user_name = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `first_name` FROM `users` WHERE `email` = '$email'"))['first_name'];

		$subject = "Восстановление пароля от аккаунта MARK";
		// $message = '
		// <html><body>
		// <div class="message">
		// 	<div class="findcreek_logo">
		// 		<img src="https://findcreek.com/assets/img/findcreek_logo.png">
		// 	</div>
		// 	<div class="subtitle">
		// 		Ваш код для сброса пароля:
		// 	</div>
		// 	<div class="recoveryCode">
		// 		' . $code . '
		// 	</div>

		// 	<div class="message_text">
		// 		Здравствуйте, Александр!

		// 		<br>	
		// 		<br>
		// 		Вы запросили код для восстановления пароля от учётной записи FINDCREEK ID <b>' . $email . '</b>.

		// 		<br>
		// 		<br>

		// 		Если это были не вы, то просто проигнорьте это письмо и не сообщайте никому данный код. 

		// 		<br>
		// 		<br>

		// 		С благодарностью,
		// 		<br>
		// 		Команда FINDCREEK
		// 	</div>
		// </div>

		// <style type="text/css">
		// 	.message {
		// 		display: flex;
		// 		flex-direction: column;
		// 		align-items: center;
		// 		background-color: #EDEEF0;
		// 		padding: 20px;
		// 	}
		// 	.findcreek_logo {
		// 		display: flex;
		// 		justify-content: center;
		// 		align-items: center;
		// 		background-color: #191919;
		// 		width: 260px;
		// 		height: 50px;
		// 		margin-bottom: 20px;
		// 	}
		// 	.findcreek_logo img {
		// 		position: relative;
		// 		width: 240px;
		// 		filter: invert(1);
		// 	}
		// 	.subtitle {
		// 		font-size: 20px;
		// 	}
		// 	.recoveryCode {
		// 		font-size: 25px;
		// 		letter-spacing: 5px;
		// 		margin-top: 5px;
		// 	}
		// 	.message_text {
		// 		max-width: 310px;
		// 		background-color: rgba(0, 0, 0, .07);
		// 		padding: 20px;
		// 		margin-top: 20px;
		// 		color: rgba(0, 0, 0, .7);
		// 	}
		// 	.message_text b {
		// 		font-weight: 600;
		// 	}
		// </style>
		// <body><html>';



		// <img style="position: relative;width: 240px;filter: invert(1);" src="https://findcreek.com/assets/img/findcreek_logo.png">

		// <div style="display: flex;justify-content: center;align-items: center;background-color: #191919;width: 260px;height: 50px;margin-bottom: 20px;" class="findcreek_logo">
		// 		<img style="position: relative;width: 240px;filter: invert(1);" src="https://findcreek.com/assets/img/findcreek_logo.png">
		// 	</div>

		$message = '
		<html><body>
		<div style="
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		background-color: #EDEEF0;
		padding: 20px;" class="message">

			<div style="
			display: flex;
			justify-content: center;
			align-items: center;
			background-color: #191919;
			padding: 10px;
			font-size: 24px;
			margin-bottom: 20px;
			max-width:310px;" class="findcreek_logo">
				FINDCREEK
				
			</div>
			<div style="font-size: 20px;" class="subtitle">
				Ваш код для сброса пароля:
			</div>
			<div style="font-size: 25px;letter-spacing: 5px;margin-top: 5px;" class="recoveryCode">
				' . $code . '
			</div>

			<div style="max-width: 310px;background-color: rgba(0, 0, 0, .07);padding: 20px;margin-top: 20px;color: rgba(0, 0, 0, .7);" class="message_text">
				Здравствуйте, ' . $user_name . '!

				<br>	
				<br>
				Вы запросили код для восстановления пароля от учётной записи FINDCREEK ID <b style="font-weight: 600;">' . $email . '</b>.

				<br>
				<br>

				Если это были не вы, то просто проигнорьте это письмо и не сообщайте никому данный код. 

				<br>
				<br>

				С благодарностью,
				<br>
				Команда FINDCREEK
			</div>
		</div>
		<body><html>';


		$message_with_recovery_button = '
		<html>
			<body>
				<center style="background-color: #edeef0; padding: 20px;">
					<ul style="list-style: none;max-width:310px;">
						<li>
							<div style="
							    background-color: #191919;
							    padding: 2px 11px;
							    font-size: 34px;
							    margin-bottom: 20px;
							    color: #fff;
							    font-weight: 600;
							    font-family: \'Montserrat\';
								margin-bottom: 20px;" class="findcreek_logo">
									FINDCREEK
									
							</div>
						</li>

						<li>
							<a href="' . $link . '/recovery-password/?recovery_token=' . $recovery_token . '">
								<button style="
								    padding: 10px;
								    border-radius: 5px;
								    color: #fff;
								    background-color: #5168FF;
								    cursor: pointer;
								    border: none;
								    font-size: 16px;
								    font-weight: 500;
								    font-family: \'Montserrat\';" class="recoveryButton">
								Сбросить пароль
								</button>
							</a>
						</li>

						<li>
							<div style="max-width: 310px;background-color: rgba(0, 0, 0, .07);padding: 20px;margin-top: 20px;color: rgba(0, 0, 0, .7);font-family: \'Montserrat\'; text-align: left;" class="message_text">
								Здравствуйте, Александр!

								<br>	
								<br>
								Вы запросили восстановление пароля от учётной записи FINDCREEK ID <b style="font-weight: 600;">' . $email . '</b>.

								<br>
								<br>

								Если это были не вы, то просто оставьте это письмо без внимания. 

								<br>
								<br>

								С благодарностью,
								<br>
								Команда FINDCREEK
							</div>
						</li>
					</ul>
				</center>
			</body>
		</html>

		';


		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		$mail = mail(
		    $email,
		    $subject,
		    $message_with_recovery_button,
		    $headers
		);

		$output = array();
		$output['response'] = array();
		$output['response']['mail'] = $mail;
		$output['response']['email'] = $email;

		echoJSON($output);
	}

	

}


if ($_POST['type'] == 'web-change-password') {
	$recovery_token = $_POST['recovery_token'];
	$password = md5($_POST['password'] . 'password_salt');
	$token = md5($password . '_changed_password_' . 'GJSJPI$SKMGSLK:)RW#FW!#%F');

	$email = mysqli_query($connection, "SELECT `email` FROM `recovery_password_codes` WHERE `recovery_token` = '$recovery_token'");

	if ($email -> num_rows == 0) {
		$output = array();
		$output['response'] = 'invalid recovery code';

		echoJSON($output);
		exit();
	}

	$email = mysqli_fetch_assoc($email)['email'];

	echo $email . '<br>';
	echo $token . '<br>';
	echo $recovery_token . '<br>';
	echo $password . '<br>';
	echo $_POST['password'] . '<br>';

	mysqli_query($connection, "UPDATE `users` SET `password` = '$password', `token` = '$token' WHERE `email` = '$email'");
	mysqli_query($connection, "DELETE FROM `recovery_password_codes` WHERE `recovery_token` = '$recovery_token'");

	$password_change_history = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `password_change_history` FROM `users` WHERE `token` = '$token'"))['password_change_history'];

	if (is_null($password_change_history)) {
		$password_change_history = array();

	} else {
		$password_change_history = json_decode($password_change_history, 1);
	}

	$new_change_password = array(
		"date" => date('d.m.Y H:i:s'),
		"function" => "recovery_password",
		"ip" => $_SERVER['REMOTE_ADDR']
	);

	array_push($password_change_history, $new_change_password);

	$password_change_history = json_encode($password_change_history, JSON_UNESCAPED_UNICODE);
	mysqli_query($connection, "UPDATE `users` SET `password_change_history` = '$password_change_history' WHERE `token` = '$token'");

	setcookie("findstudents_token", $token, time() + 3600 * 24 * 30, "/");
	setcookie("findstudents_email", $email, time() + 3600 * 24 * 30, "/");
	header("Location: " . $link);

	// $output = array();
	// $output['response'] = 'password changed';

	// echoJSON($output);
}


if ($_POST['type'] == 'confirm-recovery-code') {
	$code = $_POST['code'];
	$email = $_POST['email'];

	$result = mysqli_query($connection, "SELECT `recovery_token` FROM `recovery_password_codes` WHERE `email` = '$email' and `code` = '$code'");

	if ($result -> num_rows == 0) {
		$output = array();
		$output['response'] = 'invalid recovery code';

		echoJSON($output);
		exit();
	}

	$result = mysqli_fetch_assoc($result);
	$output = array();
	$output['response'] = array();
	$output['response']['recovery_token'] = $result['recovery_token'];
	$output['response']['email'] = $email;

	echoJSON($output);
}
