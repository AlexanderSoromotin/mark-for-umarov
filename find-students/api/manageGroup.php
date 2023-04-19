<?php

include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

if ($_POST['type'] == 'remove-request-and-leave-group') {
	exitIfTokenIsNull($user_token);
	$group_id = $_POST['group_id'];

	mysqli_query($connection, "DELETE FROM `group_membership_requests` WHERE `user_id` = '$user_id' and `group_id` = '$group_id'");

	$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'");

	if ($group_data -> num_rows != 0) {
		$group_data = mysqli_fetch_assoc($group_data);

		if ($group_data['students'] != '') {
			$students = json_decode($group_data['students']);

			$new_students_list = array();
			foreach ($students as $key => $value) {
				if ($value != $user_id) {
					// array_splice($students, $key);
					array_push($new_students_list, $value);
				}
			}
			$students = $new_students_list;
			// echo json_encode($students);

			if (json_encode($students) == '[]') {
				// echo 'students list is empty now. ';
				mysqli_query($connection, "UPDATE `groups` SET `students` = null, `head_student` = null, `deputy_head_student` = null WHERE `id` = '$group_id'");
			} else {
				if ($group_data['head_student'] == $user_id) {
					// echo 'leave head_student. ';
					if ($group_data['deputy_head_student'] > 0) {
						$deputy_head_student = $group_data['deputy_head_student'];

						mysqli_query($connection, "UPDATE `groups` SET `head_student` = '$deputy_head_student', `deputy_head_student` = null WHERE `id` = '$group_id'");
					} else {
						foreach ($students as $student_id) {
							if ($student_id != $user_id) {
								// echo 'new head_student - ' . $student_id;
								mysqli_query($connection, "UPDATE `groups` SET `head_student` = '$student_id' WHERE `id` = '$group_id'");
								break;
							}
						}
					}
				}

				if ($group_data['deputy_head_student'] == $user_id) {
					// echo 'leave deputy_head_student. ';
					mysqli_query($connection, "UPDATE `groups` SET `deputy_head_student` = null WHERE `id` = '$group_id'");
				}

				$students = json_encode($students);
				mysqli_query($connection, "UPDATE `groups` SET `students` = '$students' WHERE `id` = '$group_id'");
			}

			mysqli_query($connection, "UPDATE `users` SET `group_id` = null WHERE `id` = '$user_id'");
		}	
	}
	$output['success'] = true;
	$output['response'] = '';
	echoJSON($output);
}


// Принятие запроса на вступление в группу
if ($_POST['type'] == 'accept-request') {
	exitIfTokenIsNull($user_token);
	$student_id = $_POST['student_id'];

	if ($user_is_head_student or $user_status == 'Admin') {
		$request = mysqli_query($connection, "SELECT * FROM `group_membership_requests` WHERE `user_id` = '$student_id' and `group_id` = '$user_group_id'");

		if ($request -> num_rows != 0) {
			mysqli_query($connection, "DELETE FROM `group_membership_requests` WHERE `user_id` = '$student_id' and `group_id` = '$user_group_id'");

			mysqli_query($connection, "UPDATE `users` SET `group_id` = '$user_group_id' WHERE `id` = '$student_id'");

			$group_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$user_group_id'"));
			$student_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$student_id'"));

			$date = date('d.m.Y');
			$archive = mysqli_query($connection, "SELECT * FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

			$group_students = json_decode($group_data['students']);
			$group_robots = json_decode($group_data['robots'], 1);

			if (!in_array($student_id, $group_students)) {
				array_push($group_students, $student_id);	
			}

			$new_robots = array();

			foreach ($group_robots as $index => $robot_data) {
				if ($robot_data['robot_name'] != $student_data['first_name'] and $robot_data['robot_surname'] != $student_data['last_name']) {
					array_push($new_robots, $robot_data);
				} else {
					if (isset($archive[$robot_data['robot_id']])) {
						$tmp_array = $archive[$robot_data['robot_id']];
						unset($archive[$robot_data['robot_id']]);
						$archive[$student_id] = $tmp_array;

						$archive = json_encode($archive, JSON_UNESCAPED_UNICODE);
						mysqli_query($connection, "UPDATE `visits_archive` SET `students` = '$archive' WHERE `id` = '$user_group_id'");
					}
					
				}
			}

			

			$group_students = json_encode($group_students);
			mysqli_query($connection, "UPDATE `groups` SET `students` = '$group_students' WHERE `id` = '$user_group_id'");
		}
	}
}

if ($_POST['type'] == 'reject-request') {
	exitIfTokenIsNull($user_token);
	$student_id = $_POST['student_id'];

	if ($user_is_head_student or $user_status == 'Admin') {
		mysqli_query($connection, "DELETE FROM `group_membership_requests` WHERE `user_id` = '$student_id' and `group_id` = '$user_group_id'");
	}
}

if ($_POST['type'] == 'create-new-invitation') {
	exitIfTokenIsNull($user_token);
	$uid = '';
	while (1) {
		$arr = array_merge(range('0','9'), range('a','z'), range('A','Z'));
		$arr = array_flip($arr);

		for ($i = 0; $i < 8; $i++){
		    $uid .= array_rand($arr, 1);
		}
		if (mysqli_query($connection, "SELECT `id` FROM `invites` WHERE `text_id` = '$uid'") -> num_rows == 0) {
			break;
		}
	}
	$text_id = $uid;
	$date = date('d.m.Y H:i:s');

	mysqli_query($connection, "DELETE FROM `invites` WHERE `group_id` = '$user_group_id'");
	mysqli_query($connection, "INSERT INTO `invites` (`text_id`, `inviting_user_id`, `group_id`, `date`, `expires`) VALUES ('$text_id', '$user_id', '$user_group_id', '$date', 0)");

	$output['success'] = true;
	$output['response']['link'] = 'https://findcreek.com' . '?fs_invite=' . $text_id;

	echoJSON($output);

}

if ($_POST['type'] == 'exclude-from-group') {
	exitIfTokenIsNull($user_token);

	$student_id = $_POST['student_id'];
	$output = array("success" => true);

	// if ($student_id == '') {
	// 	$output['success'] = false;
	// 	$output['error_text'] = 'student_id is empty';
	// 	echoJSON($output);
	// }

	if ($student_id == '') {
		$output['success'] = false;
		$output['response'] = 'student_id is empty';
		echoJSON($output);
		exit();
	}

	if (mb_strpos($student_id, 'robot') !== false) {
		if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
			$output['success'] = false;
			$output['response'] = 'access denied';
			echoJSON($output);
			exit();
		}

		if ($user_group_data['robots'] != null) {
			$user_group_data['robots'] = json_decode($user_group_data['robots'], 1);
		} else {
			$user_group_data['robots'] = array();
		}

		$new_robots_array = array();
		foreach ($user_group_data['robots'] as $key => $value) {
			if ($value['robot_id'] != $student_id) {
				array_push($new_robots_array, $value);
			}
		}

		$new_robots_array = json_encode($new_robots_array, JSON_UNESCAPED_UNICODE);

		mysqli_query($connection, "UPDATE `groups` SET `robots` = '$new_robots_array' WHERE `id` = '$user_group_id'");


		$output['success'] = true;
		$output['response'] = $student_id;
		echoJSON($output);

		exit();
	}

	$student_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$student_id'"))['status'];

	if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();
	}
	$additional_query = '';
	// if ($user_status != 'Admin') {
		if ($user_group_data['head_student'] == $student_id or $user_group_data['deputy_head_student'] == $student_id or $student_status == 'Admin') {

			$output['success'] = false;
			$output['response'] = 'failed attempt to kick head student';
			echoJSON($output);
			exit();
		}
	// } 

	$students_list = json_decode($user_group_data['students']);
	$new_students_list = array();

	foreach ($students_list as $key => $local_student_id) {
		if ($local_student_id != $student_id) {
			array_push($new_students_list, $local_student_id);
		}
	}

	$new_students_list = json_encode($new_students_list, JSON_UNESCAPED_UNICODE);


	if ($user_group_data['head_student'] == $student_id) {
		mysqli_query($connection, "UPDATE `groups` SET `students` = '$new_students_list', `head_student` = '$user_id' WHERE `id` = '$user_group_id'");
	} else {
		mysqli_query($connection, "UPDATE `groups` SET `students` = '$new_students_list' WHERE `id` = '$user_group_id'");
	}

	mysqli_query($connection, "UPDATE `users` SET `group_id` = null WHERE `id` = '$student_id'");

	$output['success'] = true;
	$output['students'] = json_decode($new_students_list);
	echoJSON($output);
}






if ($_POST['type'] == 'remove-from-post-head-student') {
	exitIfTokenIsNull($user_token);

	$student_id = $_POST['student_id'];
	$output = array();

	if ($student_id == '') {
		$output['success'] = false;
		$output['response'] = 'student_id is null';
		echoJSON($output);
		exit();
	}

	if ($user_group_data['head_student'] != $user_id and $user_status != 'Admin') {
		// if ($user_status != 'Admin') {
			$output['success'] = false;
			$output['response'] = 'access denied';
			echoJSON($output);
			exit();
		// }	
	}

	mysqli_query($connection, "UPDATE `groups` SET `deputy_head_student` = null WHERE `id` = '$user_group_id'");

	$output['success'] = true;
	$output['response'] = array(
		"user_status" => $user_status,
		"user_status_in_group" => "student"
	);
	echoJSON($output);
}




if ($_POST['type'] == 'appoint-deputy-head-student') {
	exitIfTokenIsNull($user_token);

	$student_id = $_POST['student_id'];
	$output = array();

	if ($student_id == '') {
		$output['success'] = false;
		$output['response'] = 'student_id is null';
		echoJSON($output);
		exit();
	}

	if ($user_status != 'Admin' and $user_group_data['head_student'] != $user_id) {
		// if ($user_group_data['head_student'] != $user_id) {
			$output['success'] = false;
			$output['response'] = 'access denied';
			echoJSON($output);
			exit();
		// }	
	}

	$student_data = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `id` = '$student_id'");

	if ($student_data -> num_rows == 0) {
		$output['success'] = false;
		$output['response'] = 'student not found';
		echoJSON($output);
		exit();
	}

	mysqli_query($connection, "UPDATE `groups` SET `deputy_head_student` = '$student_id' WHERE `id` = '$user_group_id'");

	$output['success'] = true;
	$output['response'] = array(
		"user_status" => $user_status,
		"user_status_in_group" => "deputy_head_student"
	);
	echoJSON($output);
}




if ($_POST['type'] == 'appoint-head-student') {
	exitIfTokenIsNull($user_token);

	$student_id = $_POST['student_id'];
	$output = array();

	if ($student_id == '') {
		$output['success'] = false;
		$output['response'] = 'student_id is null';
		echoJSON($output);
		exit();
	}

	if ($user_group_data['head_student'] != $user_id and $user_status != 'Admin') {
		// if ($user_status != 'Admin') {
			$output['success'] = false;
			$output['response'] = 'access denied';
			echoJSON($output);
			exit();
		// }	
	}

	$student_data = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `id` = '$student_id'");

	if ($student_data -> num_rows == 0) {
		$output['success'] = false;
		$output['response'] = 'student not found';
		echoJSON($output);
		exit();
	}

	$new_deputy_head_student = $user_group_data['head_student'];
	// if ($new_deputy_head_student == null) {

	// }

	mysqli_query($connection, "UPDATE `groups` SET `head_student` = '$student_id', `deputy_head_student` = '$new_deputy_head_student' WHERE `id` = '$user_group_id'");

	$output['success'] = true;
	$output['response'] = array(
		"user_status" => $user_status,
		"user_status_in_group" => "head_student"
	);
	echoJSON($output);
}


if ($_POST['type'] == 'manually-add-student') {
	exitIfTokenIsNull($user_token);

	$student_name = $_POST['student_name'];
	$student_surname = $_POST['student_surname'];
	$output = array();

	if (strlen($student_name) < 2) {
		$output['success'] = false;
		$output['response'] = 'student_name is too short';
		echoJSON($output);
		exit();
	}

	if (strlen($student_surname) < 2) {
		$output['success'] = false;
		$output['response'] = 'student_surname is too short';
		echoJSON($output);
		exit();
	}

	if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {

		$output['success'] = false;
		$output['response'] = 'access denied';
		echoJSON($output);
		exit();	
	}

	$robot_id = 'robot_' . uniqid();
	$new_student = array(
		'robot_id' => $robot_id,
		'robot_name' => $student_name,
		'robot_surname' => $student_surname
	);

	if ($user_group_data['robots'] != null) {
		$user_group_data['robots'] = json_decode($user_group_data['robots']);
	} else {
		$user_group_data['robots'] = array();
	}


	array_push($user_group_data['robots'], $new_student);

	$new_group_robots = json_encode($user_group_data['robots'], JSON_UNESCAPED_UNICODE);

	mysqli_query($connection, "UPDATE `groups` SET `robots` = '$new_group_robots' WHERE `id` = '$user_group_id'");

	$output['success'] = true;
	$output['response'] = '';
	echoJSON($output);
}
