<?php
include_once 'info.php';
include_once 'db.php';
// include 'userData.php';
// Получение списка пользователей

if ($_POST['type'] == 'get-all') {
	$users = mysqli_query($connection, "SELECT * FROM `users` WHERE `status` != 'deleted' and `status` != 'pre-deleted' ORDER BY `id` DESC");

	$output = '';

	if ($users -> num_rows == 0) {
		$output = '<div class="empty">Тут пусто :с</div>';
	}
	

	while ($u = mysqli_fetch_assoc($users)) {
		$u_id = $u['id'];

		$addFriend_button = '<button class="button-3 addFriend">Добавить в друзья</button>';

		if (mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$u_id'") -> num_rows != 0) {
			$addFriend_button = '<button class="button-3">Запрос отправлен</button>';
		}
		foreach ($user_friends as $key => $value) {
			if ($value == $u['id']) {
				$addFriend_button = '<button class="button-3">У вас в друзьях</button>';
			}
		}

		
		if ($user_id == $u['id']) {
			$addFriend_button = '<button class="button-3"> Это Вы</button>';
		}

		$education_id = $u['education_id'];
		$education = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));
		$education_title = $education['title'];
		$education_short_title = $education['short_title'];
		
		$output .= '
			<div alt="(' . $u['id'] . ') ' . $u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['patronymic'] . ' (' . $education_title . ') (' . $education_short_title . ')" class="list-block" id="user_' . $u['id'] . '">
				<a href="' . $link . '/profile/?id=' . $u['id'] . '">
					<div class="photo online">
						<div class="image">
							<img style="' . unserialize($u['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($u['photo_style'])['scale'] . ');" src="' . $u['photo'] . '">
						</div>
					</div>
				</a>
				<div class="name">
					<a href="' . $link . '/profile/?id=' . $u['id'] . '"><p>' . $u['last_name'] . ' ' . $u['first_name'] . '</p></a>
					<p>' . $education_title . '</p>
				</div>
				<div class="buttons">
					' . $addFriend_button . '
					<a href="' . $link . '/profile/?id=' . $u['id'] . '"><button class="button-1 cancelRequest">Открыть профиль</button></a>
				</div>
			</div>

		';
	}
	echo $output;
}



if ($_POST['type'] == 'search-users') {
	$user_id = decodeSecretID($_POST['secret_id'], 'searchUsers');
	
	if ($user_id) {
		$limitFrom = $_POST['limitFrom'];
		$limitTo = $_POST['limitTo'];

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'"));
		$user_friends = $user_data['friends'];

		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		$user_education_id = $user_data['education_id'];
		$user_education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));

		$output = '';

		$search_text = mb_strtolower($_POST['search_text']);

		if ($search_text == '') {
			// $users_with_identical_education_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' and `education_id` = " . $user_education_id . " LIMIT " . $limitFrom . ", " . $limitTo;
			// $users_with_identical_education = mysqli_query($connection, $users_with_identical_education_query_text);

			// $users_from_other_education_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' and `education_id` != " . $user_education_id . " LIMIT " . $limitFrom . ", " . ($limitTo - $users_with_identical_education -> num_rows);

			// $users_from_other_education = mysqli_query($connection, $users_from_other_education_query_text);

			$users_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' LIMIT " . $limitFrom . ", " . $limitTo;

			$users = mysqli_query($connection, $users_query_text);
		} else {
			// $users_with_identical_education_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' and `education_id` = " . $user_education_id . " and (LOWER(`first_name`) LIKE '%" . $search_text . "%' OR LOWER(`last_name`) LIKE '%" . $search_text . "%') LIMIT " . $limitFrom . ", " . $limitTo;
			
			// $users_with_identical_education = mysqli_query($connection, $users_with_identical_education_query_text);

			// $users_from_other_education_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' and `education_id` != " . $user_education_id . " and (LOWER(`first_name`) LIKE '%" . $search_text . "%' OR LOWER(`last_name`) LIKE '%" . $search_text . "%') LIMIT " . $limitFrom . ", " . ($limitTo - $users_with_identical_education -> num_rows);
			
			// $users_from_other_education = mysqli_query($connection, $users_from_other_education_query_text);

			$users_query_text = "SELECT * FROM `users` WHERE `id` != " . $user_id . " and `status` != 'deleted' and `status` != 'pre-deleted' and `education_id` != " . $user_education_id . " and (LOWER(`first_name`) LIKE '%" . $search_text . "%' OR LOWER(`last_name`) LIKE '%" . $search_text . "%') LIMIT " . $limitFrom . ", " . $limitTo;
			
			$users = mysqli_query($connection, $users_query_text);
		}

		// if ($users_with_identical_education -> num_rows == 0 and $users_from_other_education -> num_rows == 0) {
		// 	$output = '<div class="empty">Тут пусто :с</div>';
		// }
		

		// if ($users_with_identical_education -> num_rows != 0) {
			while ($u = mysqli_fetch_assoc($users)) {
				$u_id = $u['id'];

				$addFriend_button = '<button class="button-3 addFriend">Добавить в друзья</button>';

				if (mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$u_id'") -> num_rows != 0) {
					$addFriend_button = '<button class="button-3">Запрос отправлен</button>';
				}
				foreach ($user_friends as $key => $value) {
					if ($value == $u['id']) {
						$addFriend_button = '<button class="button-3">У вас в друзьях</button>';
					}
				}

				
				if ($user_id == $u['id']) {
					$addFriend_button = '<button class="button-3"> Это Вы</button>';
				}

				$education_id = $u['education_id'];
				$education = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));
				$education_title = $education['title'];
				$education_short_title = $education['short_title'];
				
				$output .= '
					<div alt="(' . $u['id'] . ') ' . $u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['patronymic'] . ' (' . $education_title . ') (' . $education_short_title . ')" class="list-block" id="user_' . $u['id'] . '">
						<a href="' . $link . '/profile/?id=' . $u['id'] . '">
							<div class="photo online">
								<div class="image">
									<img style="' . unserialize($u['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($u['photo_style'])['scale'] . ');" src="' . $u['photo'] . '">
								</div>
							</div>
						</a>
						<div class="name">
							<a href="' . $link . '/profile/?id=' . $u['id'] . '"><p>' . $u['last_name'] . ' ' . $u['first_name'] . '</p></a>
							<p>' . $education_title . '</p>
						</div>
						<div class="buttons">
							' . $addFriend_button . '
							<a href="' . $link . '/profile/?id=' . $u['id'] . '"><button class="button-1 cancelRequest">Открыть профиль</button></a>
						</div>
					</div>

				';
			}
		// }

		// if ($users_from_other_education -> num_rows != 0) {
		// 	while ($u = mysqli_fetch_assoc($users_from_other_education)) {
		// 		$u_id = $u['id'];

		// 		$addFriend_button = '<button class="button-3 addFriend">Добавить в друзья</button>';

		// 		if (mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$u_id'") -> num_rows != 0) {
		// 			$addFriend_button = '<button class="button-3">Запрос отправлен</button>';
		// 		}
		// 		foreach ($user_friends as $key => $value) {
		// 			if ($value == $u['id']) {
		// 				$addFriend_button = '<button class="button-3">У вас в друзьях</button>';
		// 			}
		// 		}

				
		// 		if ($user_id == $u['id']) {
		// 			$addFriend_button = '<button class="button-3"> Это Вы</button>';
		// 		}

		// 		$education_id = $u['education_id'];
		// 		$education = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));
		// 		$education_title = $education['title'];
		// 		$education_short_title = $education['short_title'];
				
		// 		$output .= $u_id . '
		// 			<div alt="(' . $u['id'] . ') ' . $u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['patronymic'] . ' (' . $education_title . ') (' . $education_short_title . ')" class="list-block" id="user_' . $u['id'] . '">
		// 				<a target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '">
		// 					<div class="photo">
		// 						<div class="image">
		// 							<img style="' . unserialize($u['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($u['photo_style'])['scale'] . ');" src="' . $u['photo'] . '">
		// 						</div>
		// 					</div>
		// 				</a>
		// 				<div class="name">
		// 					<a target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '"><p>' . $u['last_name'] . ' ' . $u['first_name'] . '</p></a>
		// 					<p>' . $education_title . '</p>
		// 				</div>
		// 				<div class="buttons">
		// 					' . $addFriend_button . '
		// 					<a target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '"><button class="button-1 cancelRequest">Открыть профиль</button></a>
		// 				</div>
		// 			</div>

		// 		';
		// 	}
		// }
		echo $output;

	} else {
		echo $apiErrorCodes['1.1'];
	}
	
}
