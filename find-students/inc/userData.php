<?php
// include_once "../../inc/connectionInfo.php";

if (!isset($user_token)) {
	$user_token = $_COOKIE['findstudents_token'];

}

// Если есть токен
if (isset($user_token)) {
	$res = mysqli_query($connection, "SELECT * FROM `users` WHERE `token` = '$user_token'");
	
	// Если есть токен от несуществующего аккаунта
	if ($res -> num_rows == 0) {
		setcookie("findstudents_token", '', time() * 0, "/");
		$userLogged = false;

		$params = '';
		foreach ($_GET as $key => $value) {
			$params .= $key . '=' . $value . '&';
		}
		// header($link . '/authorization');

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
		$user_google_id = $result['google_id'];
		$user_vk_id = $result['vk_id'];
		$user_password_change_history = $result['password_change_history'];

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

		$user_faculty_id = $result['faculty_id'];
		$user_specialization_id = $result['specialization_id'];
		$user_group_id = $result['group_id'];
		$user_is_head_student = false;

		if ($user_group_id > 0) {
			$user_group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$user_group_id'");

			if ($user_group_data -> num_rows != 0) {
				$user_group_data = mysqli_fetch_assoc($user_group_data);
				if ($user_group_data['head_student'] == $user_id or $user_group_data['deputy_head_student'] == $user_id) {
					$user_is_head_student = true;
				}
			}
		}

		// 23.10.2003 17:45
		
		$user_login_history = $result['login_history'];
		if ($user_login_history == '') {
			$user_login_history = array();
			$user_login_session_expired = true;
		} else {
			$user_login_history = json_decode($user_login_history, JSON_UNESCAPED_UNICODE);
			if (count($user_login_history) == 0) {
				$user_login_session_expired = true;
			} else {
				$last_action_date = $user_login_history[0]['date'];
				$last_action_day = mb_substr($last_action_date, 0, 2);
				$last_action_month = mb_substr($last_action_date, 3, 2);
				$last_action_year = mb_substr($last_action_date, 6, 4);

				$last_action_hour = mb_substr($last_action_date, 11, 2);
				$last_action_minutes = mb_substr($last_action_date, 14, 2);

				$current_day = date('d');
				$current_month = date('m');
				$current_year = date('Y');

				$current_hour = date('H');
				$current_minutes = date('i');

				$last_action_details = $user_login_history[0]['details'];
				$details_equals = ($last_action_details['browser'] == $connectionInfo['browser'] and $last_action_details['ip'] == $connectionInfo['ip'] and $last_action_details['country'] == $connectionInfo['country'] and $last_action_details['region'] == $connectionInfo['region'] and $last_action_details['city'] == $connectionInfo['city']);

				if ($last_action_year == $current_year and $last_action_month == $current_month and $last_action_day == $current_day and $last_action_hour == $current_hour and $details_equals) {

					$user_login_session_expired = false;
				} else {
					$user_login_session_expired = true;
				}
			}
		}

		if ($user_login_session_expired) {
			
			$current_login_info = array(
				"date" => date('d.m.Y H:i'),
				"details" => $connectionInfo
			);
			array_unshift($user_login_history, $current_login_info);

			$user_login_history_json = json_encode($user_login_history, JSON_UNESCAPED_UNICODE);
			// $user_login_history = mysqli_fetch_assoc(mysqli_query($connection, "UPDATE `users` SET `login_history` = '$user_login_history_json' WHERE `id` = '$user_id'; SELECT `login_hostory` FROM `users` WHERE `id` = '$user_id'"))['login_history'];
			mysqli_query($connection, "UPDATE `users` SET `login_history` = '$user_login_history_json' WHERE `id` = '$user_id'");

			// $user_login_history = json_decode($user_login_history);
		}

		

		$userLogged = true;
	}
} 

// Нет токена
else {
	$userLogged = false;
	$params = '';
	foreach ($_GET as $key => $value) {
		$params .= $key . '=' . $value . '&';
	}
	// header("Location: " . $link . '/authorization');
}