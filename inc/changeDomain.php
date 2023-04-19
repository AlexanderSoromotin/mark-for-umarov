<!-- Этот скрипт заменяет старое доменное имя на новое в таблице пользователей,  -->
<!-- в строках "user_photo" и "bg_image" -->
<?

include_once "info.php";
include_once "db.php";
include_once "userData.php";

if ($user_status == 'Admin') {
	$users = mysqli_query($connection, "SELECT * FROM `users`");


	while ($u = mysqli_fetch_assoc($users)) {
		$user_id = $u['id'];
		$new_photo = str_replace($old_link, $link, $u['photo']);
		$new_bg_image = str_replace($old_link, $link, $u['bg_image']);

		mysqli_query($connection, "UPDATE `users` SET `photo` = '$new_photo', `bg_image` = '$new_bg_image' WHERE `id` = '$user_id'");
	}

	echo "success. don't forget change DEFAULT value in DB";
} else {
	echo 'access denied';
}

