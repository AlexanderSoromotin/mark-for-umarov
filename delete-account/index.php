<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('Banned', '/banned');
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Удаление аккаунта</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

	<?
		include_once '../inc/header.php'; // Шапка
		include_once '../assets/online.php'; // Онлайн

	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/profile">Профиль</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/support">Удаление аккаунта</a>
		</div>
	</div>

	<div class="main">
		<div class="form">
			<h1>Удаление аккаунта</h1>
			<form method="post" action="<?= $link ?>/inc/profile.php">
				
				<p>Опишите причину</p>
				<textarea autocomplete="off" name="reason" placeholder="Почему вы решили покинуть нас?"></textarea>
				<input type="hidden" name="type" value="delete-account">
				<input type="hidden" name="secret_id" value="<?= md5('user_' . $user_token . '_deleteAccount')?>">

			</form>
			<button type="button" class="button-3">Удалить аккаунт</button>

		</div>
		<p class="info">Вы сможете восстановить аккаунт в течение 6 месяцев, после чего аккаунт будет удалён полностью.</p>
	</div>


		
	<script type="text/javascript">
		
		$('.button-3').click(function () {
			if (confirm('Вы уверены, что хотите удалить свой аккаунт?') == true) {
				$('form').submit();
			}
		})
	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
</body>
</html>