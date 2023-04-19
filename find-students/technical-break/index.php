<?php
$cache_ver = '?v=2';

include_once '../inc/config.php';
include_once '../inc/userData.php';

$site_settings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `site_settings`"));

if ($site_settings['technical_break'] != 1) {
	header('Location: ' . $link);
}
// include_once '../inc/redirect.php';

// redirect('banned', '/banned');
// redirect('pre-deleted', '/pre-deleted');
// redirect('unlogged', '/authorization');



?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Технический перерыв</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>

	<?
		include_once '../inc/head.php';
	?>


	<main>
		<div class="background_image">
			<!-- Это админ -->
			<img class="background_blured_image" src="<?= $link ?>/assets/img/technical_break_admin.gif">

			<div class="background_image_content">
				<img class="background_prev_image" src="<?= $link ?>/assets/img/technical_break_admin.gif">

				<div class="title">
					В настоящий момент на сайте ведутся технические работы. Скоро всё заработает - обязательно возвращайся!
				</div>
			</div>
			
		</div>
		
	</main>

	<script>
	</script>

</body>
</html>