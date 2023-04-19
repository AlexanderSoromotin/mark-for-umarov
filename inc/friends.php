<?
include_once 'info.php';
include_once 'db.php';
// include_once 'userData.php';

// Добавление в друзья
if ($_POST['type'] == 'add-friend') {

	// Айди пользователя, которого добавляем в друзья
	$local_user_id = $_POST['local_user_id'];

	// Проверка, что local_user_id не пуст
	if ($local_user_id == '') {
		echo 'Invalid user_id';
		exit();
	}

	$user_id = decodeSecretID($_POST['secret_id'], 'addFriend');

	if (!$user_id) {
		echo $apErrorCodes['invalid_secret_id'];
		exit();
	}

	$user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$user_id' "))['blacklist'];

	if ($user_blacklist == '') {
		$user_blacklist = array();
	} else {
		$user_blacklist = unserialize($user_blacklist);
	}

	if (in_array($local_user_id, $user_blacklist)) {
		echo "User is blacklisted";
		exit();
	}

	// Пользователь пытается добавить сам себя в друзья
	if ($user_id == $local_user_id) {
		mysqli_query($connection, "DELETE FROM `friend_requests` WHERE `outgoing_id` = '$local_user_id' and `incoming_id` = '$local_user_id'");
		echo 'Trying to add yourself as a friend';
		exit();
	}

	// Функция добавления в друзья
	function addFriend () {
		global $connection;
		global $local_user_id;
		global $user_id;
		
		// Берём списки друзей у пользователей
		$user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id'") )['friends'];
		$local_user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'") )['friends'];
		

		// Если список пользователей пуст, то делаем из него корренктный массив
		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}
		if ($local_user_friends == '') {
			$local_user_friends = array();
		} else {
			$local_user_friends = unserialize($local_user_friends);
		}

		// Ищем пользователей в друзьях у друг друга, чтобы в списке друзей не было несколько копий одного пользователя
		$in_user_friends = false;
		$in_local_user_friends = false;
		
		foreach ($user_friends as $key => $value) {
			if ($value == $local_user_id) {
				$in_user_friends = true;
			}
		}
		foreach ($local_user_friends as $key => $value) {
			if ($value == $user_id) {
				$in_local_user_friends = true;
			}
		}

		// Если пользователи не найдены в списках друзей, то добавляем их
		if ($in_user_friends == false) {
			array_push($user_friends, $local_user_id);

			$user_friends = serialize($user_friends);
			mysqli_query($connection, "UPDATE `users` SET `friends` = '$user_friends' WHERE `id` = '$user_id'");
		}
		if ($in_local_user_friends == false) {
			array_push($local_user_friends, $user_id);

			$local_user_friends = serialize($local_user_friends);
			// echo $local_user_id;
			mysqli_query($connection, "UPDATE `users` SET `friends` = '$local_user_friends' WHERE `id` = '$local_user_id'");
		}
	}


	// Найдена встречная заявка на добавление в друзья
	if (mysqli_query($connection, "SELECT `id` FROM `friend_requests` WHERE `outgoing_id` = '$local_user_id' and `incoming_id` = '$user_id'") -> num_rows != 0) {

		// Добавляем пользователей друг в другу в друзья
		addFriend();

		// Удаляем обе заявки
		mysqli_query($connection, "DELETE FROM `friend_requests` WHERE (`outgoing_id` = '$local_user_id' and `incoming_id` = '$user_id') or (`outgoing_id` = '$user_id' and `incoming_id` = '$local_user_id')");

		// Удаляем уведомление у пользователя, к которому добавляются в друзья
		mysqli_query($connection, "DELETE FROM `notifications` WHERE `incoming_id` = '$user_id' and `type` = 'add-friend'");

		$notification_array = serialize(array('outgoing_id' => $user_id));

		// Удаляем старое уведомление о добавлении пользователя в друзья
		mysqli_query($connection, "DELETE FROM `notifications` WHERE `type` = 'friend-added' and `incoming_id` = '$local_user_id' and `body` = '$notification_array'");

		// Создаём уведомление о том, что пользователь одобрил заявку в друзья
		mysqli_query($connection, "INSERT INTO `notifications` (`incoming_id`, `type`, `body`) VALUES ('$local_user_id', 'friend-added', '$notification_array')");
		echo 'friend_added';

	} else {
		// Нет встречной заявки -> проверяем, существует ли заявка на добавление local_user к user в друзья
		if (mysqli_query($connection, "SELECT `id` FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$local_user_id'") -> num_rows == 0) {
			// Нет заявки на добавление в друзья со стороны пользователя к другу

			// Берём списки друзей у пользователей
			$user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$user_id'") )['friends'];
			$local_user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$local_user_id'") )['friends'];
			
			// Если список пользователей пуст, то делаем из него корренктный массив
			if ($user_friends == '') {
				$user_friends = array();
			} else {
				$user_friends = unserialize($user_friends);
			}
			if ($local_user_friends == '') {
				$local_user_friends = array();
			} else {
				$local_user_friends = unserialize($local_user_friends);
			}

			// Ищем пользователей в друзьях у друг друга, чтобы в списке друзей не было несколько копий одного пользователя
			$in_user_friends = false;
			$in_local_user_friends = false;
			
			foreach ($user_friends as $key => $value) {
				if ($value == $local_user_id) {
					$in_user_friends = true;
				}
			}
			foreach ($local_user_friends as $key => $value) {
				if ($value == $user_id) {
					$in_local_user_friends = true;
				}
			}
			

			// Пользователи не состоят в списках друзей друг у друга
			if ($in_local_user_friends == false and $in_user_friends == false) {

				mysqli_query($connection, "INSERT INTO friend_requests (outgoing_id, incoming_id) VALUES ({$user_id}, {$local_user_id})");

				$notification_array = serialize(array('outgoing_id' => $user_id));

				// Удаляем старое уведомление о добавлении пользователя в друзья
				mysqli_query($connection, "DELETE FROM `notifications` WHERE `type` = 'add-friend' and `incoming_id` = '$local_user_id' and `body` = '$notification_array'");

				// Создаём уведомление о том, что пользователь оставил заявку в друзья
				mysqli_query($connection, "INSERT INTO `notifications` (`incoming_id`, `type`, `body`) VALUES ('$local_user_id', 'add-friend', '$notification_array')");

				echo 'request_created';
			}
		} else {
			// Уже существует запрос на добавление в друзья со стороны пользователя к другу
			echo 'request_already_exist';
		}
		
	}	
}

// Отклонение заявки в друзья
if ($_POST['type'] == 'reject-request') {
	$user_id = $_POST['local_user_id'];

	if ($user_id == '') {
		echo 'Invalid user_id';
	}

	$local_user_id = decodeSecretID($_POST['secret_id'], 'rejectRequest');

	if ($local_user_id) {
		mysqli_query($connection, "DELETE FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$local_user_id'");

		// Удаляем уведомление у пользователя, к которому добавляются в друзья
		mysqli_query($connection, "DELETE FROM `notifications` WHERE `incoming_id` = '$local_user_id' and `type` = 'add-friend'");
		echo 'request_rejected';
	} 
	else {
		echo $apiErrorCodes['1.1'];
	}
}








// Удаление пользователя из списка друзей
if ($_POST['type'] == 'remove-friend') {
	$local_user_id = $_POST['local_user_id'];

	// Если чего-то не хватает - завершаем
	if ($local_user_id == '') {
		echo 'Invalid user_id';
	}

	// Основной пользователь
	$user_id = decodeSecretID($_POST['secret_id'], 'removeFriend');

	if ($user_id) {
		// Друзья основного пользователя
		$user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$user_id'") )['friends'];

		// Друзья локального пользователя
		$local_user_friends = mysqli_fetch_assoc( mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$local_user_id'") )['friends'];

		// Приведение массива в нормальный вид
		if ($local_user_friends == '') {
			$local_user_friends = array();
		} else {
			$local_user_friends = unserialize($local_user_friends);
		}
		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		// Удаление локального пользователя из друзей ОСНОВНОГО пользователя
		foreach ($user_friends as $key => $value) {
			if ($value == $local_user_id) {
				unset($user_friends[$key]);
			}
		}
		// Удаление ОСНОВНОГО пользователя из друзей локального пользователя
		foreach ($local_user_friends as $key => $value) {
			if ($value == $user_id) {
				unset($local_user_friends[$key]);
			}
		}

		$local_user_friends = serialize($local_user_friends);
		$user_friends = serialize($user_friends);

		// Сохранение изменений
		mysqli_query($connection, "UPDATE `users` SET `friends` = '$user_friends' WHERE `id` = '$user_id'");
		mysqli_query($connection, "UPDATE `users` SET `friends` = '$local_user_friends' WHERE `id` = '$local_user_id'");

		// Удаление уведомления о том, что когда-то ОСНОВНОЙ пользователь добавил в свои друзья локального пользователя
		$notification_array_1 = serialize(array('outgoing_id' => $user_id));
		mysqli_query($connection, "DELETE FROM  `notifications` WHERE `incoming_id` = '$local_user_id' and `body` = '$notification_array_1' and `type` = 'friend-added'");

		// Удаление уведомления о том, что когда-то локальный пользователь добавил в свои друзья ОСНОВНОГО пользователя
		$notification_array_2 = serialize(array('outgoing_id' => $local_user_id));
		mysqli_query($connection, "DELETE FROM  `notifications` WHERE `incoming_id` = '$user_id' and `body` = '$notification_array_2' and `type` = 'friend-added'");

		echo 'friend_removed';
	}
	else {
		echo $apiErrorCodes['1.1'];
	}
}








// Запрос на получение списка друзей
if ($_POST['type'] == 'get-friends-list') {

	// Основной пользователь
	$user_id = decodeSecretID($_POST['secret_id'], 'getFriendsList');

	if ($user_id) {
		$output = array();

		if ($_POST['html']) {
			// Если запрос в формате html
			$output = '';
		}

		// Получение списка друзей
		$friends_array = mysqli_fetch_array(mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$user_id'"))[0];

		// Если список друзей пуст
		if ($friends_array == '' or count(unserialize($friends_array)) == 0) {
			// Если запрос в формате html
			if ($_POST['html']) {
				echo '<div class="empty">Тут пусто :с</div>';
				exit;
			}
			echo "null";
			exit();
		} 
		else {
			// Список друзей не пуст
			$friends_array = unserialize($friends_array);

			foreach ($friends_array as $key) {

				// Данные локального пользователя
				$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$key'"));

				// Информация об учебном заведении
				$education_id = $local_user_data['education_id'];
				$education_title = mysqli_fetch_array(mysqli_query($connection, "SELECT `title` FROM `education` WHERE `id` = '$education_id'"))[0];

				// Отношение между пользователями
				$relationship_with_user = 'none';

				if (mysqli_query($connection, "SELECT `id` FROM `friend_requests` WHERE `incoming_id` = '$local_user_id' and `outgoing_id` = '$key' ") -> num_rows != 0) {

					// Локальный пользователь ожидает одобрения заявки
					$relationship_with_user = 'waiting_to_be_added';
				} else {
					if (mysqli_query($connection, "SELECT `id` FROM `friend_requests` WHERE `incoming_id` = '$key' and `outgoing_id` = '$local_user_id' ") -> num_rows != 0) {

						// Основной пользователь ожидает одобрения заявки
						$relationship_with_user = 'request_has_been_sent';
					} 
					else {
						if (in_array($key, $friends_array)) {
							// Пользователи друзья
							$relationship_with_user = 'is_a_friend';
						}
					}
				}
			
				if ($_POST['html']) {
					// Если запрос в формате html

					// Настройка приватности сообщений (отключено)
					if ($local_user_data['privacy_messages'] == 0) {
						$message_button = '<a href="' . $link . '/messenger/?id=' . $local_user_data['id'] . '"><button class="button-3 ">Написать сообщение</button></a>';
					} else {
						$message_button = '<a href="' . $link . '/messenger/?id=' . $local_user_data['id'] . '"><button class="button-3 ">Написать сообщение</button></a>';
					}

					$output .= '
								<div class="list-block" id="friend_' . $local_user_data['id'] . '">
									<a  href="' . $link . '/profile/?id=' . $local_user_data['id'] . '">
										<div class="photo online">
											<div class="image">
												<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
											</div>
										</div>
									</a>
									<div class="name">
										<a href="' . $link . '/profile/?id=' . $local_user_data['id'] . '"><p>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</p></a>
										<p>' . $education_title . '</p>
									</div>
									<div class="buttons">
										' . $message_button . '
										<button class="button-1 deleteFriend">Удалить из друзей</button>
									</div>
								</div>
							';
				} else {
					// Обычный запрос
					$local_data_array = array(
						"user_id" => $local_user_data['id'],
						"first_name" => $local_user_data['first_name'],
						"last_name" => $local_user_data['last_name'],
						"patronymic" => $local_user_data['patronymic'],
						"user_photo" => $local_user_data['photo'],
						"user_photo_style" => unserialize($local_user_data['photo_style']),
						"education_title" => $education_title,
						"relationship_with_user" => $relationship_with_user
					);
					$output[$key] = $local_data_array;
				}
				
			}
			if ($_POST['html']) {
				// Запрос в формате html
				echo $output;
			} else {
				echo json_encode($output);
			}
		}
	} else {
		// Не удалось расшифровать айди пользователя
		echo $apiErrorCodes["invalid_secret_id"];
	}
}








// Запрос на получение списка заявок в друзья
if ($_POST['type'] == 'get-friend-requests') {

	// Основной пользователь
	$user_id = decodeSecretID($_POST['secret_id'], 'getFriendRequests');

	if ($user_id) {
		$output = array();

		if ($_POST['html']) {
			// Если запрос в формате html
			$output = '';
		}

		// Берём список друзей
		$requests = mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `incoming_id` = '$user_id'");

		// Список запросов пуст
		if ($requests -> num_rows == 0) {
			// Запрос на возврат html конструкции
			if ($_POST['html']) {
				echo '<div class="empty">Тут пусто :с</div>';
				exit();
			}
			echo "null";
			exit();
		}

		// Список запросов не пуст
		else {
			while ($r = mysqli_fetch_assoc($requests)) {
				$incoming_id = $r['outgoing_id'];

				// Данные локального пользователя
				$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$incoming_id'"));

				// Информация об учебном заведении
				$education_id = $local_user_data['education_id'];
				$education_title = mysqli_fetch_array(mysqli_query($connection, "SELECT `title` FROM `education` WHERE `id` = '$education_id'"))[0];

				// Запрос на возврат html конструкции 
				if ($_POST['html']) {
					$output .= '
						<div class="list-block" id="request_' . $local_user_data['id'] . '">
							<a href="' . $link . '/profile/?id=' . $local_user_data['id'] . '">
								<div class="photo online">
									<div class="image">
										<img style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" src="' . $local_user_data['photo'] . '">
									</div>
								</div>
							</a>
							<div class="name">
								<a href="' . $link . '/profile/?id=' . $local_user_data['id'] . '"><p>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</p></a>
								<p>' . $education_title . '</p>
							</div>
							<div class="buttons">
								<button class="button-3 addFriend">Добавить в друзья</button>
								<button class="button-1 cancelRequest">Отклонить заявку</button>
							</div>
						</div>

					';
				} else {
					// Формирование локального массива
					$local_data_array = array(
						"user_id" => $local_user_data['id'],
						"first_name" => $local_user_data['first_name'],
						"last_name" => $local_user_data['last_name'],
						"patronymic" => $local_user_data['patronymic'],
						"user_photo" => $local_user_data['photo'],
						"user_photo_style" => unserialize($local_user_data['photo_style']),
						"education_title" => $education_title
					);
					$output[$local_user_data['id']] = $local_data_array;
				}
			}

			if ($_POST['html']) {
				// Запрос в формате html
				echo $output;
			} else {
				echo json_encode($output);
			}
			
		}

	} else {
		// Не удалось расшифровать айди пользователя
		echo $apiErrorCodes["invalid_secret_id"];
	}
}









// Блокировка пользователя
if ($_POST['type'] == 'block-user') {

	// Основной пользователь
	$user_id = decodeSecretID($_POST['secret_id'], 'blockUser');

	if ($user_id) {
		$local_user_id = $_POST['local_user_id'];

		if ($local_user_id == '') {
			echo 'Empty local_user_id';
			exit();
		}

		if ($local_user_id == $user_id) {
			echo 'User is trying to block himself';
			exit();
		}

		$local_user = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user -> num_rows == 0) {
			echo 'User with local_user_id does not exist (' . $local_user_id . ')';
			exit();
		}

		$user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$user_id'"))['blacklist'];
		$user_friends = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$user_id'"))['friends'];

		if ($user_blacklist == '') {
			$user_blacklist = array();
		} else {
			$user_blacklist = unserialize($user_blacklist);
		}

		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		if (!in_array($local_user_id, $user_blacklist)) {

			array_push($user_blacklist, $local_user_id);

			if (in_array($local_user_id, $user_friends)) {
				$local_user_friends = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$local_user_id'"))['friends'];

				if ($local_user_friends == '') {
					$local_user_friends = array();
				} else {
					$local_user_friends = unserialize($local_user_friends);
				}

				// Удаление основного пользователя из друзей ЛОКАЛЬНОГО пользователя
				foreach ($local_user_friends as $key => $value) {
					if ($value == $user_id) {
						unset($local_user_friends[$key]);
					}
				}
			
				// Удаление локального пользователя из друзей ОСНОВНОГО пользователя
				foreach ($user_friends as $key => $value) {
					if ($value == $local_user_id) {
						unset($user_friends[$key]);
					}
				}
				$local_user_friends = serialize($local_user_friends);
				mysqli_query($connection, "UPDATE `users` SET `friends` = '$local_user_friends' WHERE `id` = '$local_user_id' ");
			}

			$user_blacklist = serialize($user_blacklist);
			$user_friends = serialize($user_friends);

			mysqli_query($connection, "UPDATE `users` SET `blacklist` = '$user_blacklist', `friends` = '$user_friends' WHERE `id` = '$user_id' ");
			
			mysqli_query($connection, "DELETE FROM `friend_requests` WHERE (`outgoing_id` = '$user_id' and `incoming_id` = '$local_user_id') ");
		}
		
		echo 'user_blocked';
	} else {
		// Не удалось расшифровать айди пользователя
		echo $apiErrorCodes["invalid_secret_id"];
	}
}








// Разблокирование пользователя
if ($_POST['type'] == 'unblock-user') {

	// Основной пользователь
	$user_id = decodeSecretID($_POST['secret_id'], 'unblockUser');

	if ($user_id) {
		$local_user_id = $_POST['local_user_id'];

		if ($local_user_id == '') {
			echo 'Empty local_user_id';
			exit();
		}

		$local_user = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user -> num_rows == 0) {
			echo 'User with local_user_id does not exist (' . $local_user_id . ')';
			exit();
		}

		$user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$user_id'"))['blacklist'];

		if ($user_blacklist == '') {
			$user_blacklist = array();
		} else {
			$user_blacklist = unserialize($user_blacklist);
		}

		if (in_array($local_user_id, $user_blacklist)) {
			// Удаление локального пользователя из друзей ОСНОВНОГО пользователя
			foreach ($user_blacklist as $key => $value) {
				if ($value == $local_user_id) {
					unset($user_blacklist[$key]);
				}
			}
			$user_blacklist = serialize($user_blacklist);
			mysqli_query($connection, "UPDATE `users` SET `blacklist` = '$user_blacklist' WHERE `id` = '$user_id'");
		}

		echo 'user_unblocked';
	} else {
		// Не удалось расшифровать айди пользователя
		echo $apiErrorCodes["invalid_secret_id"];
	}
}
