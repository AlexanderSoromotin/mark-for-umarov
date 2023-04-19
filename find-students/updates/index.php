<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

$last_update_ver = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `updates` ORDER BY `id` DESC LIMIT 0, 1"))['id'];
if ($_COOKIE['last_update_ver'] < $last_update_ver) {
	setcookie("last_update_ver", $last_update_ver, time() + 3600 * 24 *30 * 12, "/");
}

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=2';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Последние обновления</title>
	
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">


	<main>
		<!-- <h3>Как мы можем Вам помочь?</h3> -->
		<div class="page_title">
			Последние обновления
		</div>

		<div class="updates">
			<?
				$updates = mysqli_query($connection, "SELECT * FROM `updates` ORDER BY `id` DESC LIMIT 0, 10");

				if ($updates -> num_rows != 0) {
					while ($item = mysqli_fetch_assoc($updates)) {
						echo '
						<div class="update" id="update_' . $item['id'] . '">
							<div class="post_title">
								' . $item['title'] . '
							</div>
							<div class="post_text">
								' . str_replace(PHP_EOL, "<br>", $item['text']) . '
							</div>
							<div class="date">Обновление от ' . mb_substr($item['date'], 8, 2) . '.' . mb_substr($item['date'], 5, 2) . '.' . mb_substr($item['date'], 0, 4) . '</div>
						</div>
						';
					}
					

				} else {
					echo '<div class="empty">Тут пока пусто</div>';
				}

			?>
			

		</div>

	</main>



	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('settings');
		$('.post_text').click(function() {
			if ($(this).hasClass('opened_text')) {
				$(this).removeClass('opened_text')
			} else {
				$(this).addClass('opened_text')
			}
		})
	</script>
</body>
</html>