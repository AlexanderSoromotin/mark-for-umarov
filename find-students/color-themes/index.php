<?php
$cache_ver = '?v=1';

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

// redirect('banned', '/banned');
// redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Цветовые темы</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta property="og:image" content="<?= $link ?>/assets/img/findstudents.jpg">
	<meta property="og:image:width" content="968">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="content">
	        <div class="themes">
	        	<div class="theme white_theme">
	        		<div class="image">
	        			<img src="<?= $link ?>/assets/img/day.jpg">
	        		</div>
	        		<button class="button-5">Активно</button>
	        	</div>

	        	<div class="theme dark_theme">
	        		<div class="image">
	        			<img src="<?= $link ?>/assets/img/night.jpg">
	        		</div>
	        		<button class="button-1">Активировать</button>
	        	</div>
	        </div>
	    </div>
	</main>

	<?
		if ($userLogged) {
			include_once '../inc/mobile_toolbar.php';
		}
	?>

	<script>
		select_mobile_footer_tab('settings');
		if (!localStorage.getItem('color_theme')) {
			localStorage.setItem('color_theme', 'white')
		}

		if (localStorage.getItem('color_theme') == 'dark') {
			$('.dark_theme').addClass('selected');
		}
		if (localStorage.getItem('color_theme') == 'white') {
			$('.white_theme').addClass('selected');
		}

		$('.themes .theme').click(function () {
			if (!$(this).hasClass('selected')) {
				$('.themes .theme').removeClass('selected');
				$(this).addClass('selected')

				if ($(this).hasClass('dark_theme')) {
					localStorage.setItem('color_theme', 'dark');
					$('head').prepend('<link class="dark_theme_file" rel="stylesheet" type="text/css" href="<?= $link ?>/assets/dark_theme.css<?= $main_css_cache_ver ?>">');
					$('head').prepend('<link class="dark_theme_file" rel="stylesheet" type="text/css" href="dark_theme.css?' + Math.random(0, 1) + '">');
					
				}
				if ($(this).hasClass('white_theme')) {
					$('head .dark_theme_file').remove();
					localStorage.setItem('color_theme', 'white');
				}
			}
		})
	</script>

</body>
</html>