<?php

include_once 'info.php';
include_once 'db.php';

if ($_POST['type'] == 'add-reputation') {
	$user_id = decodeSecretID($_POST['secret_id'], 'addReputation');

	

	if ($user_id) {
		$local_user_id = $_POST['user_id'];

		$local_user_reputation = mysqli_query($connection, "SELECT `reputation` FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user_reputation -> num_rows == 0) {
			echo 'Ivalid user_id';
			exit();
		}

		$local_user_reputation = mysqli_fetch_assoc($local_user_reputation)['reputation'];

		if ($local_user_reputation == '') {
			$local_user_reputation = array();
		} else {
			$local_user_reputation = json_decode($local_user_reputation);
		}

		if (!in_array($user_id, $local_user_reputation)) {
			array_push($local_user_reputation, $user_id);
			echo json_encode(array("response_text" => "reputation added", "reputation_count" => count($local_user_reputation)));
			
			$local_user_reputation = json_encode($local_user_reputation);
			mysqli_query($connection, "UPDATE `users` SET `reputation` = '$local_user_reputation' WHERE `id` = '$local_user_id'");
		} else {
			echo json_encode(array("response_text" => "reputation has already been added", "reputation_count" => count($local_user_reputation)));
		}

	}
	else {
		echo $apiErrorCodes['1.1'];
	}
}

if ($_POST['type'] == 'remove-reputation') {
	$user_id = decodeSecretID($_POST['secret_id'], 'removeReputation');

	if ($user_id) {
		$local_user_id = $_POST['user_id'];

		$local_user_reputation = mysqli_query($connection, "SELECT `reputation` FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user_reputation -> num_rows == 0) {
			echo 'Ivalid user_id';
			exit();
		}

		$local_user_reputation = mysqli_fetch_assoc($local_user_reputation)['reputation'];

		if ($local_user_reputation == '') {
			$local_user_reputation = array();
		} else {
			$local_user_reputation = json_decode($local_user_reputation);
		}

		if (in_array($user_id, $local_user_reputation)) {
			// unset($local_user_reputation[$user_id]);

			foreach ($local_user_reputation as $key => $id) {
				if ($user_id == $id) {
					unset($local_user_reputation[$key]);
				}
			} 

			echo json_encode(array("response_text" => "reputation removed", "reputation_count" => count($local_user_reputation)));

			$local_user_reputation = json_encode($local_user_reputation);
			mysqli_query($connection, "UPDATE `users` SET `reputation` = '$local_user_reputation' WHERE `id` = '$local_user_id'");
		} else {
			echo json_encode(array("response_text" => "nothing to remove", "reputation_count" => count($local_user_reputation)));
		}

	}
	else {
		echo $apiErrorCodes['1.1'];
	}
}