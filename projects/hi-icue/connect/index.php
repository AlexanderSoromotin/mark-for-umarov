<?
	include_once '../../../inc/info.php';
	include_once '../../../inc/db.php';
	include_once '../../../inc/userData.php';

	include_once '../../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('Banned', '/banned');
	redirect('unlogged', '/');

	include_once '../../../inc/head.php';	

?>

<html>
<head>
	<meta charset="utf-8">
	<title>HI-ICUE :: Подключение</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="background">
		<video autoplay muted>
			<source src="<?= $link ?>/assets/video/Clouds_960_540.mp4">
		</video>
	</div>

	<div class="main"></div>


		

	<script type="text/javascript">
	</script>
</body>
</html>