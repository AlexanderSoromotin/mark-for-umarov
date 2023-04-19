<?php
	include_once "info.php";
	include_once 'db.php';
	// include_once 'userData.php';
	// include_once 'connectionInfo.php';

if (isset($_POST['type'])) {

	// Сохранение настроек основной информации
	if ($_POST['type'] == 'save-main') {
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'saveMain');

		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];

		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_id == $local_user_id or $user_status == 'Admin') {

			// Рандомное число для кэширования картинки
			$rand = '?v=' . rand(0, 1000000); 

			// перепроверка входных данных
			$last_name = strip_tags(addslashes($_POST['last_name']));
			if ($last_name == '' or strlen($last_name) < 2) {
				exit();
			}
			$first_name = strip_tags(addslashes($_POST['first_name']));
			if ($first_name == '' or strlen($first_name) < 2) {
				exit();
			}
			// $patronymic = strip_tags(addslashes($_POST['patronymic']));
			// if ($patronymic == '' or strlen($patronymic) < 2) {
			// 	exit();
			// }

			// Получение айди выбранного учебного заведения
			$education = strip_tags(addslashes($_POST['education']));
			$university = mysqli_query($connection, "SELECT * FROM `education` WHERE `short_title` = '$education'");
			$uni = '0';

			$city = $_POST['city'];
			$city_id = 0;

			$city = preg_replace(" " . "/\([^\)]+\)/", '', $city);
			if (mb_substr($city, mb_strlen($city)-1) == ' ') {
			   $city = mb_substr($city, 0, mb_strlen($city)-1);
			}

			$city_result = mysqli_query($connection, "SELECT `id` FROM `cities` WHERE `rus_title` = '$city' ");
			if ($city_result -> num_rows != 0) {
				$city_id = mysqli_fetch_assoc($city_result)['id'];
			}

			if ($university -> num_rows != 0 and str_replace(' ', '', $education) != '') {
				$university = mysqli_fetch_assoc($university);
				$uni = $university['id'];
			}

			$photo_style = $_POST['photo_style'];
			$photo_scale = $_POST['photo_scale'];
			$photo_url = $_POST['photo_url'];

			// формирование массива с данными и позиционировании фотографии пользователя
			$photo_style_arr = serialize( array(
				'ox_oy' => $photo_style,
				'scale' => $photo_scale
			));

			// Если изменена фотография пользователя
			if ($photo_url != '') {
				// Если файл лежит в папке uploads, то его переносим в user_photo
				if (file_exists('../uploads/' . $photo_url)) {
					rename('../uploads/' . $photo_url, '../uploads/user_photo/' . $photo_url);
				}

				// $mime = end(split('.', split('?', $photo_url)[0]));
				// Удаление старых фото

				
				// Формируем ссылку на новое фото пользователя
				$photo_link = $link . '/uploads/user_photo/' . $photo_url . $rand;

				// Сохранение изменений (с фотографией)
				mysqli_query($connection, "UPDATE `users` SET `last_name` = '$last_name', `first_name` = '$first_name', `photo_style` = '$photo_style_arr', `photo` = '$photo_link', `education_id` = '$uni', `city_id` = '$city_id' WHERE `id` = '$local_user_id'");
			} else {
				// Сохранение изменений (без фотографии)
				mysqli_query($connection, "UPDATE `users` SET `last_name` = '$last_name', `first_name` = '$first_name', `photo_style` = '$photo_style_arr', `education_id` = '$uni', `city_id` = '$city_id' WHERE `id` = '$local_user_id'");
			}

			// Получение и вывод нового изображения
			$new_img = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"))['photo'];
			echo $new_img;

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [main]', '')");
		}
		else {
			// Неверный секретный айди
			echo $apErrorCodes['1.1'];
		}
	}



	// Сохранение настроек фона
	if ($_POST['type'] == 'save-bg') {
		
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'saveBg');
		
		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];
		
		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_id == $local_user_id or $user_status == 'Admin') {
			// Рандомное число для кэширования фотографии
			$rand = '?v=' . rand(0, 1000000); 

			$photo_style = $_POST['photo_style'];
			$photo_url = $_POST['photo_url'];

			// Если фотография изменена
			if ($photo_url != '') {
				// Если файл лежит в папке uploads, то его переносим в user_photo
				if (file_exists('../uploads/' . $photo_url)) {
					rename('../uploads/' . $photo_url, '../uploads/user_bg_photo/' . $photo_url);
				}
				
				// Формируем ссылку на новый фон пользователя
				$photo_link = $link . '/uploads/user_bg_photo/' . $photo_url . $rand;

				// Сохранение изменений (с фотографией)
				mysqli_query($connection, "UPDATE `users` SET `bg_image_style` = '$photo_style', `bg_image` = '$photo_link' WHERE `id` = '$local_user_id'");

			} else {
				// Сохранение изменений (без фотографии)
				mysqli_query($connection, "UPDATE `users` SET `bg_image_style` = '$photo_style' WHERE `id` = '$local_user_id'");
			}

			// Получение и вывод нового изображения фото
			$new_img = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"))['bg_image'];
			echo $new_img;

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [bg]', '')");
		}
		else {
			// Некорректный айди пользователя
			echo $apErrorCodes['1.1'];
		}
		
	}

	// Сохранение настроек приватности
	if ($_POST['type'] == 'save-privacy') {
		
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'savePrivacy');
		
		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];
		
		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_id == $local_user_id or $user_status == 'Admin') {
			$privacy_messages = $_POST['privacy_messages'];
			$closed_profile = $_POST['closed_profile'];

			// Сохранение изменений
			mysqli_query($connection, "UPDATE `users` SET `privacy_messages` = '$privacy_messages', `closed_profile` = '$closed_profile' WHERE `id` = '$local_user_id'");

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [privacy]', '')");
		} else {
			// Некорректный секретный айди
			echo $apiErrorCodes['1.1'];
		}
	}

	// Сохранение настроек безопасности
	if ($_POST['type'] == 'save-security') {
		
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'save-security');
		
		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];
		
		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_id == $local_user_id or $user_status == 'Admin') {
			// mysqli_query($connection, "UPDATE `users` SET `closed_profile` = '$closed_profile' WHERE `id` = '$local_user_id'");

			// Запись логов
			// mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [basic_info]', '')");
		}
	}	

	// Изменение токена пользователя (выйти со всех устройств)
	if ($_POST['type'] == 'change-token') {		
		
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'changeToken');
		
		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];
		
		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_id == $local_user_id or $user_status == 'Admin') {
			if ($local_user_id) {
				// Создание нового токена
				$newToken = md5( mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$id' "))['token'] . date('Y-m-d H:i:s') . 'salt_hehe' . rand(0, 10000000));

				// Сохранение изменений
				mysqli_query($connection, "UPDATE `users` SET `token` = '$newToken' WHERE `id` = '$local_user_id'");

				// Запись логов
				mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [change_token]', '')");
			}
		}	
	}

	// Изменение статуса пользователя
	if ($_POST['type'] == 'change-status') {
		
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'changeStatus');
		
		// Локальный пользователь
		$local_user_id = $_POST['local_user_id'];
		
		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		echo $user_id;
		if ($user_status == 'Admin') {

			$status = $_POST['status'];
			$ban_reason = $_POST['ban_reason'];
			$gif_user_photo = $_POST['gif_user_photo'];

			// Сохранение изменений
			mysqli_query($connection, "UPDATE `users` SET `status` = '$status', `gif_user_photo` = '$gif_user_photo', `ban_reason` = '$ban_reason' WHERE `id` = '$local_user_id'");

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [change_status]', '')");
		}
	}	

	// Удаление аккаунта
	if ($_POST['type'] == 'delete-account') {

		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'deleteAccount');

		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_status != 'Banned' and $user_id) {
			$reason = $_POST['reason'];
			$date = date('d-m-Y H:i:s');

			// Сохранение изменений
			mysqli_query($connection, "UPDATE `users` SET `status` = 'pre-deleted', `delete_account_reason` = '$reason', `delete_account_date` = '$date' WHERE `id` = '$user_id'");

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [delete_account]', '')");

			header("Location: " . $link . '/pre-deleted');
		}
	}

	// Восстановление удалённого аккаунта
	if ($_POST['type'] == 'recovery-account') {
		// Основной пользователь
		$user_id = decodeSecretID($_POST['secret_id'], 'recoveryAccount');

		// Статус основного пользователя
		$user_status = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `status` FROM `users` WHERE `id` = '$user_id'"))['status'];

		if ($user_status == 'pre-deleted' and $user_id) {

			// Сохранение изменений
			mysqli_query($connection, "UPDATE `users` SET `status` = 'User', `delete_account_reason` = '', `delete_account_date` = '' WHERE `id` = '$user_id'");

			// Запись логов
			mysqli_query($connection, "INSERT INTO `logs` (`user_id`, `second_user_id`, `function`, `description`) VALUES ('$user_id', '$local_user_id', 'edit_profile [recovery_account]', '')");
		}
	}

	// Получение данных об аккаунте
	if ($_POST['type'] == 'get-user-data') {
		// Основной пользователь
		// $user_id = decodeSecretID($_POST['secret_id'], 'recoveryAccount');
		$local_user_id = $_POST['user_id'];
		$params = $_POST['params'];
		$user_id = 1;
		if ($user_id) {
			// Статус основного пользователя
			$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));

			if ($params == 'all') {
				$user_education_id = $user_data['education_id'];

				$output = array(
					"id" => $user_data['id'],
					"reputation" => $user_data['reputation'],
					"friends" => $user_data['friends'],
					"blacklist" => $user_data['blacklist'],
					"first_name" => $user_data['first_name'],
					"last_name" => $user_data['last_name'],
					"sex" => $user_data['sex'],
					"email" => $user_data['email'],
					"education_id" => $user_data['education_id'],
					"city_id" => $user_data['city_id'],
					"photo" => $user_data['photo'],
					"photo_style" => $user_data['photo_style'],
					"bg_image" => $user_data['bg_image'],
					"bg_image_style" => $user_data['bg_image_style'],
					"registration_date" => $user_data['registration_date'],
					"status" => $user_data['status'],
					"closed_profile" => $user_data['closed_profile'],
					"last_online" => $user_data['last_online'],
					"gif_user_photo" => $user_data['gif_user_photo'],
					"ban_reason" => $user_data['ban_reason'],
					"delete_account_reason" => $user_data['delete_account_reason'],
					"delete_account_date" => $user_data['delete_account_date'],
					"privacy_messages" => $user_data['privacy_messages'],
					"hi_icue" => $user_data['hi_icue']
				);

				$education_data = mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_education_id'");

				if ($education_data -> num_rows != 0) {
					$education_data = mysqli_fetch_assoc($education_data);
					$output['education_title'] = $education_data['title'];
					$output['education_short_title'] = $education_data['short_title'];
					$output['education_united_id'] = $education_data['united_id'];

					if ($education_data['united_id'] != 0) {
						$united_education_id = $education_data['united_id'];

						$united_education_data = mysqli_query($connection, "SELECT * FROM `united_education` WHERE `id` = '$united_education_id'");

						if ($united_education_data -> num_rows != 0) {
							$output['education_united_title'] = mysqli_fetch_assoc($united_education_data)['title'];
						} else {
							$output['education_united_title'] = false;
						}

						
					} else {
						$output['education_united_title'] = false;
					}
					
				}
				
				$city_id = $user_data['city_id'];
				$city_data = mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$city_id'");

				if ($city_data -> num_rows != 0) {
					$city_data = mysqli_fetch_assoc($city_data);

					$output['city_rus_title'] = $city_data['rus_title'];
					$output['city_eng_title'] = $city_data['eng_title'];
					$output['country_id'] = $city_data['country_id'];

					$country_id = $city_data['country_id'];
					$country_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `countries` WHERE `id` = '$country_id'"));

					$output['country_rus_title'] = $country_data['rus_title'];
					$output['country_eng_title'] = $country_data['eng_title'];

				} else {
					$output['city_rus_title'] = false;
					$output['city_eng_title'] = false;
					$output['country_id'] = false;
					$output['country_rus_title'] = false;
					$output['country_eng_title'] = false;
				}


			} else {
				$params = json_decode($params);
				$output = array();

				foreach ($params as $key => $value) {
					if (isset($user_data[$value])) {
						$output[$value] = $user_data[$value];

					} else {
						if ($value == 'education_title') {
							$user_education_id = $user_data['education_id'];

							$education_data = mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_education_id'");
							if ($education_data -> num_rows != 0) {
								$education_data = mysqli_fetch_assoc($education_data);
								$output['education_title'] = $education_data['title'];
							} else {
								$output['education_title'] = false;
							}
						}

						if ($value == 'education_short_title') {
							$user_education_id = $user_data['education_id'];

							if (!isset($education_data)) {
								$education_data = mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_education_id'");
								$education_data = mysqli_fetch_assoc($education_data);
							}
							
							if ($education_data -> num_rows != 0) {
								$output['education_short_title'] = $education_data['short_title'];
							} else {
								$output['education_short_title'] = false;
							}
						}
					}

					// if ($value == '')
				}
			}

			

			
		}
		
		echo json_encode($output);
	}

	
}	