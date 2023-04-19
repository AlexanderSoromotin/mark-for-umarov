<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
$cache_ver = '?v=5';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>FINDCREEK</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
	?>


	<main>
		<div class="empty">
			FINDCREEK
		</div>

	</main>

</body>
</html>