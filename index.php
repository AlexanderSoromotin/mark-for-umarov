<? 
	include_once 'inc/info.php';
	include_once 'inc/db.php';
	include_once 'inc/userData.php';

	if ($user_status == 'Banned') {
		header('Location: ' . $link . '/banned');
	}
	if ($user_status == 'pre-deleted') {
		header('Location: ' . $link . '/pre-deleted');
	}

	$styles_ver = '?v=2';

	// include_once 'inc/userData.php';

	// if ($_GET['s'] == 'fs') {
		if ($_GET['fs_invite'] != '') {
			$params = '';
			foreach ($_GET as $key => $value) {
				$params .= $key . '=' . $value . '&';
			}
			// echo 1;
			header("Location: " . $link . '/find-students/invites?' . $params);
		} else {
			if ($user_status == '' or $user_status == 'User') {
				// header("Location: " . $link . '/find-students');
			}
			
			// foreach ($_GET as $key => $value) {
			// 	$params .= $key . '=' . $value . '&';
			// }
			// echo 2;
			// header("Location: " . $link . '/find-students?' . $params);
		}
	// }

	// include_once 'inc/redirect.php';
	// redirect('Banned', '/banned');
	// redirect('pre-deleted', '/pre-deleted');
	// redirect('unlogged', '/authorization');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css<?= $styles_ver ?>">
	<?
		
		include_once 'inc/head.php';
		include_once 'assets/online.php';
	?>
</head>
<body>
	<?
		include_once 'inc/header.php';
	?>
	<br><br><br><br><br><br>
	<div class="main">
		<div class="crane_404">
			<div class="image">
				<img src="<?= $link ?>/assets/img/icons/crane.svg">
			</div>
			<div class="text">
				Что ты тут делаешь?
			</div>
		</div>
		<div class="description">
			Доступные сервисы:
		</div>
		<div class="services">
			<a href="<?= $link ?>/find-students">
				<div class="service">
					<?
						include_once "find-students/inc/config.php";
						$users_count = mysqli_query($connection, "SELECT COUNT(*) FROM `users` WHERE `status` != 'deleted' and `status` != 'banned'");
						$users_count = mysqli_fetch_assoc($users_count)['COUNT(*)'];
					?>
					<p class="title">FINDSTUDENTS</p>
					<p class="users">Пользователей: <?= $users_count ?></p>
				</div>
			</a>
		</div>
	</div>


	<?
		include_once 'inc/footer.php';
	?>
</body>
</html>