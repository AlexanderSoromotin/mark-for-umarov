<?php

include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

if ($_POST['type'] == 'get-education-data') {
	exitIfTokenIsNull($user_token);
	
	$output = array();

	$output['success'] = true;

	$output['response']['education_id'] = $user_education_id;
	$output['response']['faculty_id'] = $user_faculty_id;
	$output['response']['specialization_id'] = $user_specialization_id;
	$output['response']['group_id'] = $user_group_id;

	echoJSON($output);
}

if ($_POST['type'] == 'check-group-membership-request') {
	exitIfTokenIsNull($user_token);

	$output = array(
		"success" => true, 
		"response" => array(
			"groups" => array()
		)
	);
	// $output['success'] = true;
	// $output['success']['response'] = array("groups" => array());
	// $output['success']['response']['groups'] = array();

	$result = mysqli_query($connection, "SELECT * FROM `group_membership_requests` WHERE `user_id` = '$user_id'");

	if ($result -> num_rows != 0) {
		while ($r = mysqli_fetch_assoc($result)) {
			array_push($output['response']['groups'], $r['group_id']);
		}
	}

	echoJSON($output);
}

if ($_POST['type'] == 'get-user-data-by-token') {
	exitIfTokenIsNull($user_token);
	$output = array();

	

	$user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `token` = '$user_token'");

	if ($user_data -> num_rows == 0) {
		$output['success'] = true;
		$output['response'] = 'user undefined';
		echoJSON($output);
		exit();
	}

	$output['success'] = true;
	$output['response'] = array();

	$result = mysqli_fetch_assoc($user_data);

	$output['response']['user_id'] = $result['id'];

	$output['response']['first_name'] = $result['first_name'];
	$output['response']['last_name'] = $result['last_name'];
	$output['response']['patronymic'] = $result['patronymic'];

	$output['response']['email'] = $result['email'];
	$output['response']['avatar'] = $result['photo'];
	// $output['response']['background_image'] = $result['bg_image'];
	$output['response']['registration_date'] = $result['registration_date'];
	$output['response']['user_status'] = $result['status'];
	$output['response']['closed_profile'] = $result['closed_profile'];
	$user_google_id = $result['google_id'];

	$output['response']['reputation'] = $result['reputation'];
	$output['response']['can_use_gif'] = $result['gif_user_photo'];
	// $output['response']['ban_reason'] = $result['ban_reason'];
	// $user_delete_account_date = $result['delete_account_date'];

	$output['response']['avatar_styles'] = unserialize($result['photo_style']);

	$user_friends = $result['friends'];
	if ($user_friends == '') {
		$user_friends = array();
	} else {
		$user_friends = unserialize($user_friends);
	}

	// $output['response']['friends'] = $user_friends;

	if ($result['blacklist'] == '') {
		$user_blacklist = array();
	} else {
		$user_blacklist = unserialize($result['blacklist']);
	}
	// $output['response']['blacklist'] = $user_blacklist;

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

	// $output['response']['city_id'] = ''

	$user_education_id = $result['education_id'];
	if ($user_education_id != 0) {
		$user_education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_education_id' "));

		$user_education_title = $user_education_data['title'];
		$user_education_short_title = $user_education_data['short_title'];
	}

	$output['response']['education_id'] = $user_education_id;
	$output['response']['education_title'] = $user_education_title;
	$output['response']['education_short_title'] = $user_education_short_title;

	$output['response']['faculty_id'] = $result['faculty_id'];
	$output['response']['specialization_id'] = $result['specialization_id'];
	$output['response']['group_id'] = $result['group_id'];
	$output['response']['is_head_student'] = false;

	echoJSON($output);
}