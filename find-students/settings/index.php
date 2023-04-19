<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

// $last_update_ver = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `updates` ORDER BY `id` DESC LIMIT 0, 1"))['id'];
$last_update_ver = 0;
if ($_COOKIE['last_update_ver'] < $last_update_ver) {
	$new_update = true;
}

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=7';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Настройки</title>
	
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">


	<main>
		<div class="profile_block">
			<a href="<?= $link ?>/profile">
				<div class="avatar">
					<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
				</div>
			</a>
			<div class="user_info">
				<p class="username"><?= $user_last_name . ' ' . $user_first_name ?></span>
				<p class="user_email"><?= $user_email ?></span>
			</div>
		</div>
		
		<div class="settings_block">
			<center><p class="title">Настройки аккаунта</span></center>
			<ul>
				<a href="<?= $link ?>/edit-user-info">	
					<li class="">
						<img src="<?= $link ?>/assets/img/icons/user-circle.svg">
						<span>Изменить основную информацию</span>
					</li>
				</a>

				<!-- <a href="">	
					<li class="not_working">
						<img src="<?= $link ?>/assets/img/icons/compass.svg">
						<span style="text-decoration: line-through;">Дополнительная информация</span>
					</li>
				</a> -->

				<a href="<?= $link ?>/educational-institution">	
					<li class="">
						<img src="<?= $link ?>/assets/img/icons/school.svg">
						<span>Учебное учреждение и группа</span>

					</li>
				</a>

				<a href="<?= $link ?>/security">	
					<li class="">
						<img src="<?= $link ?>/assets/img/icons/lock.svg">
						<span>Безопасность</span>

					</li>
				</a>

				<a href="<?= $link ?>/inc/logout.php">	
					<li>
						<img src="<?= $link ?>/assets/img/icons/logout.svg">
						<span>Выйти из аккаунта</span>

					</li>
				</a>
			</ul>

			<center><p class="title">Сервис</span></center>
			<ul>
				<? if ($user_status == 'Admin'): ?>
				<a href="<?= $link ?>/how-it-works">	
					<li class="">
						<img src="<?= $link ?>/assets/img/icons/database.svg">
						<span style="text-decoration: line-through;">Админ-панель</span>

					</li>
				</a>
				<? endif; ?>

				<a href="<?= $link ?>/how-it-works">	
					<li>
						<img src="<?= $link ?>/assets/img/icons/tools.svg">
						<span>Как этим пользоваться</span>

					</li>
				</a>
				
				<!-- <a href="<?= $link ?>/color-themes">	
					<li>
						<img src="<?= $link ?>/assets/img/icons/brush.svg">
						<span style="text-decoration: line-through;">Оформление</span>
					</li>
				</a> -->

				<a href="<?= $link ?>/updates">	
					<li class=" <? if ($new_update) {echo "notification";}?>">
						<img src="<?= $link ?>/assets/img/icons/news.svg">
						<span>Последние обновления</span>

					</li>
				</a>

				<a href="<?= $link ?>/support">	
					<li class="">
						<img src="<?= $link ?>/assets/img/icons/help.svg">
						<span>Поддержка</span>
					</li>
				</a>

				<!-- <a href="">	
					<li class="not_working">
						<img src="<?= $link ?>/assets/img/icons/briefcase.svg">
						<span style="text-decoration: line-through;">О нас</span>

					</li>
				</a> -->
			</ul>
		</div>
	</main>



	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('settings');
	</script>
</body>
</html>