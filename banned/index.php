<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';
	$styles_ver = '?v=5';

	// if ($user_status != 'Banned') {
	// 	header('Location: ' . $link);
	// }
	
	// include_once '../inc/redirect.php';
	// redirect('User', '/');
	// redirect('Admin', '/');
	// redirect('pre-deleted', '/pre-deleted');
	// redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Блокировка</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $styles_ver ?>">
	<link rel="stylesheet" type="text/css" href="mobile.css<?= $styles_ver ?>">
</head>
<body>
	<?
		// include_once '../inc/header.php'; // Шапка
		// include_once '../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->

	<div class="main">
		<h2>Ваш аккаунт <b><?= $user_email ?></b> был заблокирован</h2>
		<p>Причина: <?= $user_ban_reason ?></p>

		<div class="buttons">
			<a href="<?= $link ?>/support"><button class="button-1">Написать в поддержку</button></a>
			<a href="<?= $link ?>/inc/logout.php"><button class="button-3">Выйти из аккаунта</button></a>
		</div>
	</div>


		

	
	<?
		// include_once '../inc/footer.php';
	?>
</body>
</html>