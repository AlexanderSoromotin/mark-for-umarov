<?
include_once 'db.php';
include_once 'info.php';


if ($type = $_POST['type']) {
	// Сохранение изменений в учебном заведении
	if ($type == 'save') {
		$index = (int) $_POST['index'];
		$title = $_POST['title'];
		$shortTitle = $_POST['shortTitle'];
		$id = (int) $_POST['id'];
		$united_title = $_POST['united_title'];

		if ($united_title == '') {
			$united_id = 0;
		} else {
			$united_id = mysqli_fetch_array(mysqli_query($connection, "SELECT `id` FROM `united_education` WHERE `title` = '$united_title'"))[0];
		}

		$res = mysqli_query($connection, "UPDATE `education` SET `display_index` = '$index', `title` = '$title', `short_title` = '$shortTitle', `united_id` = '$united_id' WHERE `id` = '$id' ");
		echo 'ok';
	}

	// Удаление учебного заведения
	if ($type == 'delete') {
		$id = (int) $_POST['id'];

		$res = mysqli_query($connection, "DELETE FROM `education` WHERE `id` = '$id' ");
		echo 'ok';
	}

	// Добавление нового учебного заведения
	if ($type == 'add') {
		$index = (int) $_POST['index'];
		$title = $_POST['title'];
		$shortTitle = $_POST['shortTitle'];

		$united_title = $_POST['united_title'];

		if ($united_title == '') {
			$united_id = 0;
		} else {
			$united_id = mysqli_fetch_array(mysqli_query($connection, "SELECT `id` FROM `united_education` WHERE `title` = '$united_title'"))[0];
		}

		$res = mysqli_query($connection, "INSERT INTO `education` (`display_index`, `title`, `short_title`, `united_id`) VALUES ('$index', '$title', '$shortTitle', '$united_id')");

		$id = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `title` = '$title' and `short_title` = '$shortTitle' "))['id'];

		echo $id;
	}

	// Одобрение заявки на добавление учебного заведения
	if ($type == 'approval') {
		$index = (int) $_POST['index'];
		$title = $_POST['title'];
		$appeal_id = $_POST['id'];
		$shortTitle = $_POST['shortTitle'];
		$local_user_email = $_POST['user_email'];

		$united_title = $_POST['united_title'];

		if ($united_title == '') {
			$united_id = 0;
		} else {
			$united_id = mysqli_fetch_array(mysqli_query($connection, "SELECT `id` FROM `united_education` WHERE `title` = '$united_title'"))[0];
		}

		// Добавляем новое учебное заведение
		$res = mysqli_query($connection, "INSERT INTO `education` (`display_index`, `title`, `short_title`, `united_id`) VALUES ('$index', '$title', '$shortTitle', '$united_id')");

		// Получим айди нового учебного завдения
		$id = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `title` = '$title' and `short_title` = '$shortTitle' "))['id'];

		// Создание уведомления пользователю, который запросил добавление этого учебного заведения
		$local_user_id = mysqli_fetch_array(mysqli_query($connection, "SELECT `id` FROM `users` WHERE `email` = '$local_user_email'"))[0];
		$notification_array = serialize(array('id' => $id));
		mysqli_query($connection, "INSERT INTO `notifications` (`incoming_id`, `body`, `type`) VALUES ('$local_user_id', '$notification_array', 'education-added')");

		// Удаление заявки
		mysqli_query($connection, "DELETE FROM `application_add_education` WHERE `id` = '$appeal_id'");

		echo $id;
	}

	// Отклонение заявки на добавение нового учебного заведения
	if ($type == 'cancel') {
		$id = (int) $_POST['id'];

		$res = mysqli_query($connection, "DELETE FROM `application_add_education` WHERE `id` = '$id' ");

		echo 'ok';
	}
	
} else {
	// print_r(mysqli_fetch_assoc(mysqli_query($connection, "SHOW TABLE STATUS")));
	// echo "result: " . mysqli_fetch_assoc(mysqli_query($connection, "SHOW TABLE STATUS WHERE `name` LIKE 'education'"))['Auto_increment'];
}

// hello
?>