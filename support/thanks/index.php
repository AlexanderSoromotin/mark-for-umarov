<?
	include_once '../../inc/info.php';
	include_once '../../inc/db.php';
	include_once '../../inc/userData.php';
	
	include_once '../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	// redirect('unlogged', '/');

	include_once '../../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Поддержка</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

	<?
		include_once '../../inc/header.php'; // Шапка
		include_once '../../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<?= $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/support">Поддержка</a>
		</div>
	</div>

	<div class="main">
		<img draggable="false" src="<?= $link ?>/assets/img/icons/heart.svg">
		<div class="form">
			<p>Благодарим за ваше сообщение. Кто-то из нашей команды по обслуживанию скоро свяжется с вами.</p>
		</div>
	</div>
	
	<?
		include_once '../../inc/footer.php';
	?>
</body>
</html>