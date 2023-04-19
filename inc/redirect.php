<?	
	include_once 'info.php';
	include_once 'db.php';

	if (!isset($_COOKIE['token'])) {
		header("Location: " . $link);
		exit();
	}

	include_once 'userData.php';
	global $user_status;

	$site_settings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `site_settings`"));

	if ($site_settings['technical_break'] == 1 and $user_status != 'Admin') {
		header("Location: " . $link . "/technical-break");
	}

	if ($user_status != 'Admin') {
		header("Location: " . $link);
	}

	// Перенаправляем указанный статус на указанную страницу
	function redirect ($status, $dir) {

		global $user_status;
		
		// echo '<br> User: ' . $user_status . ' | checking with ' . $status;
		if ($user_status == $status) {

			header("Location: " . $link . $dir);
		}
		if ($status == 'unlogged' and !isset($_COOKIE['token'])) {
			header("Location: " . $link . $dir);
		}
	}

	// Если аккаунт пользователя полностью удалён, то выходим из аккаунта
	if ($user_status == 'deleted') {
		header("Location: " . $link . '/inc/logout.php');
	}
?>