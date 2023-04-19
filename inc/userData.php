<?php

$user_token = $_COOKIE['token'];

// Если есть токен
if (isset($user_token)) {
	$res = mysqli_query($connection, "SELECT * FROM `users` WHERE `token` = '$user_token'");
	
	// Если есть токен от несуществующего аккаунта
	if ($res -> num_rows == 0) {
		setcookie("token", '', time() * 0, "/");
		$userLogged = false;

	} 
	// Есть токен и аккаунт с таким токеном существует
	else {
		$result = mysqli_fetch_assoc($res);
		$user_id = $result['id'];

		$user_first_name = $result['first_name'];
		$user_last_name = $result['last_name'];
		$user_patronymic = $result['patronymic'];

		$user_email = $result['email'];
		$user_photo = $result['photo'];
		$user_bg_image = $result['bg_image'];
		$user_registration_date = $result['registration_date'];
		$user_status = $result['status'];
		$user_closed_profile = $result['closed_profile'];

		$user_reputation = $result['reputation'];
		$user_gif_photo = $result['gif_user_photo'];
		$user_ban_reason = $result['ban_reason'];
		$user_delete_account_date = $result['delete_account_date'];

		$user_photo_style = unserialize($result['photo_style']);

		$user_friends = $result['friends'];
		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		if ($result['blacklist'] == '') {
			$user_blacklist = array();
		} else {
			$user_blacklist = unserialize($result['blacklist']);
		}

		$user_city_id = $result['city_id'];
		if ($user_city_id != 0) {
			$user_city_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$user_city_id' "));
			$user_country_id = $user_city_data['country_id'];
			$user_country_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `countries` WHERE `id` = '$user_country_id' "));
			$user_city_rus = $user_city_data['rus_title'];
			$user_city_eng = $user_city_data['eng_title'];
			$user_country_rus = $user_city_data['rus_title'];
			$user_country_eng = $user_city_data['eng_title'];
		}

		$user_education_id = $result['education_id'];
		if ($user_education_id != 0) {
			$user_education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_education_id' "));

			$user_education_title = $user_education_data['title'];
			$user_education_short_title = $user_education_data['short_title'];
		}
		


		$userLogged = true;
	}
} 
// Нет токена
else {
	$userLogged = false;
}