<?	
	include_once 'info.php';
	include_once 'db.php';
	include_once 'userData.php';

	$type = $_POST['type'];

	// Приходит обращение в поддержку
	if ($type == 'appeal') {
		$theme = $_POST['theme'];
		$email = $_POST['email'];
		$message = $_POST['message'];

		if (str_replace(' ', '', $theme) != '' and str_replace(' ', '', $email) != '' and str_replace(' ', '', $message) != '') {

			if (strlen($theme) < 2000 and strlen($email) < 300 and strlen($message) < 4000) {

				mysqli_query($connection, "INSERT INTO `support_tickets` (`email`, `theme`, `message`, `appealer_id`) VALUES ('$email', '$theme', '$message', '$user_id') ");
			}
		}
		header('Location: ' . $link . '/support/thanks');
	}

	// Приходит заявление на добавление нового учебного заведения
	if ($type == 'education') {
		$email = $_POST['email'];
		$title = $_POST['title'];
		$short_title = $_POST['short_title'];

		if (str_replace(' ', '', $email) != '' and str_replace(' ', '', $title) != '' and str_replace(' ', '', $short_title) != '') {

			mysqli_query($connection, "INSERT INTO `application_add_education` (`email`, `title`, `short_title`) VALUES ('$email', '$title', '$short_title') ");
		}
		header('Location: ' . $link . '/support/thanks');
	}
	
	// Администратор закрывает тикет
	if ($type == 'close-ticket') {
		$id = $_POST['id'];
		$answer = $_POST['answer'];
		$email = $_POST['email'];


		$appealer_id = mysqli_fetch_array(mysqli_query($connection, "SELECT `appealer_id` FROM `support_tickets` WHERE `id` = '$id'"))[0];

		if (str_replace(' ', '', $id) != '' and str_replace(' ', '', $answer) != '') {

			mysqli_query($connection, "UPDATE `support_tickets` SET `status` = 'Closed', `answer` = '$answer', `admin_id` = '$user_id' WHERE `id` = '$id' ");

			$res1 = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$appealer_id' ");
			$res2 = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$user_id' ");

			$appealer = mysqli_fetch_assoc($res1);
			$admin = mysqli_fetch_assoc($res2);

			$appealer_id = $appealer['id'];

			if ($res1 -> num_rows != 0) {
				$sender = '<p>от: <a target="_blink" href="' . $link . '/profile?id=' . $appealer['id'] . '">' . $email . '</a></p>';
				$admin = '<p>ответил: <a target="_blink" href="' . $link . '/profile?id=' . $admin['id'] . '">' . $admin['email'] . '</a></p>';
				
			} else {
				$sender = '<h4>от: ' . $email . '</h4>';
				$admin = '<p>ответил: <a target="_blink" href="' . $link . '/profile?id=' . $admin['id'] . '">' . $admin['email'] . '</a></p>';
			}

			echo $sender . $admin;
			// Создание уведомления
			// $auto_inc = mysqli_fetch_assoc(mysqli_query($connection, "SHOW TABLE STATUS WHERE `name` LIKE 'support_tickets'"))['Auto_increment'];

			$notification_array = serialize(array('ticket_id' => $id));
			mysqli_query($connection, "INSERT INTO `notifications` (`incoming_id`, `body`, `type`) VALUES ('$appealer_id', '$notification_array', 'support-response')");

		}
	}

	// Администратор удаляет тикет
	if ($type == 'delete-ticket') {
		$id = $_POST['id'];

		if (str_replace(' ', '', $id) != '') {

			mysqli_query($connection, "DELETE FROM `support_tickets` WHERE `id` = '$id' ");
		}
	}
?>