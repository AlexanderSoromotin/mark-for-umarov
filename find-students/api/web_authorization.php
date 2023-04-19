<?php
include_once "../inc/config.php";
$google_debug = 0;
// echo 1;

setcookie("findstudents-auth-error", '', time() + 0, "/");
setcookie("findstudents-auth-error-email", '', time() + 0, "/");

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

	# if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
	// 	echo 'invalid email';
	//   exit();
	// }

	$token = md5($last_name . $first_name . $email . 'ZmluZHN0dWRlbnRzX2hhaGE=');

	if ($_POST['password'] == '' or strlen($_POST['password']) < 2) {
		$output['success'] = false;
		$output['response'] = 'password is too short';
		echoJSON($output);
		exit();
	}
	$password = md5($_POST['password'] . 'password_salt');

	mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`) VALUES ('$token', '$first_name', '$last_name', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id')");

	// with patronimyc
	// mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `patronymic`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`) VALUES ('$token', '$first_name', '$last_name', '$patronymic', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id')");

	setcookie("findstudents_token", $token, time() + 3600 * 24 * 30, "/");
	setcookie("findstudents_email", $email, time() + 3600 * 24 * 30, "/");

	header('Location: ' . $link);	
}



if ($_POST['step'] == 'authorization') {
	if ($_POST['email'] == '') {
		$output['success'] = false;
		$output['response'] = 'empty email';
		echoJSON($output);		exit();
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
		setcookie("findstudents-auth-error", 'Неверный пароль и/или неверная почта', time() + 3600 * 24 * 30, "/");
		setcookie("findstudents-auth-error-email", $email, time() + 3600 * 24 * 30, "/");
		
		header('Location: ' . $link);
		exit();
	} 

	$token = mysqli_fetch_assoc($result)['token'];

	setcookie("findstudents_token", $token, time() + 3600 * 24 * 30, "/");
	setcookie("findstudents_email", $email, time() + 3600 * 24 * 30, "/");

	header('Location: ' . $link);
	
}

if ($_GET['provider'] == 'google') {
	if ($google_debug) {
		echo 'Авторизация через google<br>';
	}
	
	if (!empty($_GET['code'])) {
		if ($google_debug) {
			echo 'Код не пуст<br><br>';
		} 
		// Отправляем код для получения токена (POST-запрос).
		$params = array(
			'client_id'     => '483285812826-804r69bk46vk1kvhr3htqova7hn3753o.apps.googleusercontent.com',
			'client_secret' => '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',
			'redirect_uri'  => 'https://mark.findcreek.com/api/web_authorization.php?provider=google',
			'grant_type'    => 'authorization_code',
			'code'          => $_GET['code']
		);	

		if ($google_debug) {
			echo 'Отправляем параметры<br>';
			echo json_encode($params) . '<br><br> Полученные данные:<br>';
		}	

		$ch = curl_init('https://accounts.google.com/o/oauth2/token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
		curl_close($ch);	
	 	
	 	if ($google_debug) {
	 		echo $data . '<br>';
	 	}
	 	
		$data = json_decode($data, true);
		if (!empty($data['access_token'])) {
			if ($google_debug) {
				echo 'access token не пуст<br><br>';
			}
			
			// Токен получили, получаем данные пользователя.
			$params = array(
				'access_token' => $data['access_token'],
				'id_token'     => $data['id_token'],
				'token_type'   => 'Bearer',
				'expires_in'   => 3599
			);

			if ($google_debug) {
				echo json_encode($params) . '<br>';
			} 
	 
			$info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
			if ($google_debug) {
				echo 'вывод:<br><br>';
				echo $info . '<br>';
			}

			$info = json_decode($info, true);

			// print_r($info);
			$user_email = $info['email'];
			$user_google_id = $info['id'];

			$result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$user_email'");

			if ($result -> num_rows != 0) {
				// Авторизация

				if ($google_debug) {
					echo 'авторизация. ' . $info['email'] . ' ' . $info['id'];
				}

				$result = mysqli_fetch_assoc($result);
				if ($result['google_id'] != $user_google_id) {
					mysqli_query($connection, "UPDATE `users` SET `google_id` = '$user_google_id' WHERE `email` = '$user_email'");
				}

				setcookie("findstudents_token", $result['token'], time() + 3600 * 24 * 30, "/");
				setcookie("findstudents_email", $result['email'], time() + 3600 * 24 * 30, "/");
				echo '<br>token - ' . $result['token'];
				if (!$google_debug) {
					header('Location: ' . $link);
				}
				
			} else {
				// Регистрация
				if ($google_debug) {
					echo 'регистрация. ' . $info['email'] . ' ' . $info['id'];
				}
				
				$first_name = $info['given_name'];
				$last_name = $info['family_name'];
				$email = $info['email'];
				// $password = '';
				$profile_type = 0;
				// $sex = 
				$education_id = 0;
				$city_id = 0;
				$token = md5($last_name . $first_name . $email . 'ZmluZHN0dWRlbnRzX2hhaGE=' . date('dmYHis'));
				$google_id = $info['id'];
				$photo = $info['picture'];
				$photo_style = '';

				mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`, `google_id`, `photo`, `photo_style`) VALUES ('$token', '$first_name', '$last_name', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id', '$google_id', '$photo', '$photo_style')");

				setcookie("findstudents_token", $token, time() + 3600 * 24 * 30, "/");
				setcookie("findstudents_email", $email, time() + 3600 * 24 * 30, "/");
				echo '<br>token - ' . $token;
				if (!$google_debug) {
					header('Location: ' . $link);
				}
				
			}
		}
	}
}


if ($_GET['provider'] == 'vk') { 

	if (!empty($_GET['code'])) {
		$params = array(
			'client_id'     => '8173506',
			'client_secret' => 'iZyjXwRJyCePQmJfCM26',
			'redirect_uri'  => 'https://mark.findcreek.com/api/web_authorization.php?provider=vk',
			'code'          => $_GET['code']
		);

		// echo 'Получение access_token';
		
		// Получение access_token
		$data = file_get_contents('https://oauth.vk.com/access_token?' . urldecode(http_build_query($params)));
		$data = json_decode($data, true);
		if (!empty($data['access_token'])) {
			
			// Получим данные пользователя
			$params = array(
				'v'            => '5.81',
				'uids'         => $data['user_id'],
				'access_token' => $data['access_token'],
				'fields'       => 'photo_big',
			);
	 
			$info = file_get_contents('https://api.vk.com/method/users.get?' . urldecode(http_build_query($params)));
			$info = json_decode($info, true);	

			$user_email = $data['email'];
			$user_vk_id = $data['user_id'];
			$user_first_name = $info['response'][0]['first_name'];
			$user_last_name = $info['response'][0]['last_name'];
			$user_avatar = $info['response'][0]['photo_big'];

			// echo 'user_email: ' . $user_email . '<br>';
			// echo 'user_vk_id: ' . $user_id . '<br>';
			// echo 'user_first_name: ' . $user_first_name . '<br>';
			// echo 'user_last_name: ' . $user_last_name . '<br>';
			// echo 'user_avatar: ' . $user_avatar . '<br>';

			// exit();

			$result = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$user_email'");

			if ($result -> num_rows != 0) {
				// Авторизация

				$result = mysqli_fetch_assoc($result);
				if ($result['vk_id'] != $user_vk_id) {
					mysqli_query($connection, "UPDATE `users` SET `vk_id` = '$user_vk_id' WHERE `email` = '$user_email'");
				}

				setcookie("findstudents_token", $result['token'], time() + 3600 * 24 * 30, "/");
				setcookie("findstudents_email", $result['email'], time() + 3600 * 24 * 30, "/");
				// echo '<br>token - ' . $result['token'];
				
				header('Location: ' . $link);
				
				
			} else {
				// Регистрация
				
				$first_name = $user_first_name;
				$last_name = $user_last_name;
				$email = $user_email;
				// $password = '';
				$profile_type = 0;
				// $sex = 
				$education_id = 0;
				$city_id = 0;
				$token = md5($last_name . $first_name . $email . 'ZmluZHN0dWRlbnRzX2hhaGE=' . date('dmYHis'));
				$vk_id = $user_vk_id;
				$photo = $user_avatar;
				$photo_style = '';

				mysqli_query($connection, "INSERT INTO `users` (`token`, `first_name`, `last_name`, `email`, `password`, `closed_profile`, `sex`, `education_id`, `city_id`, `google_id`, `photo`, `photo_style`) VALUES ('$token', '$first_name', '$last_name', '$email', '$password', '$profile_type', '$sex', '$education_id', '$city_id', '$google_id', '$photo', '$photo_style')");

				setcookie("findstudents_token", $token, time() + 3600 * 24 * 30, "/");
				setcookie("findstudents_email", $email, time() + 3600 * 24 * 30, "/");
				echo '<br>token - ' . $token;
				if (!$google_debug) {
					header('Location: ' . $link);
				}
				
			}
		}
	}
}






// echo 1;