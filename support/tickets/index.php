<?
	include_once '../../inc/info.php';
	include_once '../../inc/db.php';
	include_once '../../inc/userData.php';

	include_once '../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/support');

	include_once '../../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Обращения к поддержке</title>
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
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/support">Поддержка</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/support">Обращения</a>
		</div>
	</div>

	<div class="main">
		<div class="appeals">
			<h2>Обращения к службе поддержки</h2>
			<div class="list">
				<center>
					<a href="<?= $link ?>/support">
						<button class="button-3">Создать обращение</button>
					</a>
				</center>

				<?
					$appeals = mysqli_query($connection, "SELECT * FROM `support_tickets` WHERE `appealer_id` = '$user_id' ORDER BY `status`, `id` DESC");

					while ($a = mysqli_fetch_assoc($appeals)) {
						$a_id = $a['id'];
						if ($a['status'] == 'Checking') {
							echo '
							<div class="list-block">
								<h5>Дата обращения: ' . mb_substr($a['date'], 8, 2) . ' ' . $months_short[mb_substr($a['date'], 5, 2)] . ' ' . mb_substr($a['date'], 0, 4) . ' ' . mb_substr($a['date'], 11, 5) . '</h5>
								<h5>Обращение на рассмотрении</h5>

								<label>Тема</label>
								<p>' . $a['theme'] . '</p>

								<label>Сообщение</label>
								<p>' . $a['message'] . '</p>
							</div>
							';
						}

						if ($a['status'] == 'Closed') {
							$admin_id = $a['admin_id'];
							$admin_first_name = mysqli_fetch_array(mysqli_query($connection, "SELECT `first_name` FROM `users` WHERE `id` = '$admin_id' "))[0];
							echo '
							<div class="list-block">
								<h5>Дата обращения: ' . mb_substr($a['date'], 8, 2) . ' ' . $months_short[mb_substr($a['date'], 5, 2)] . ' ' . mb_substr($a['date'], 0, 4) . ' ' . mb_substr($a['date'], 11, 5) . '</h5>
								<h5>Ответил ' . $admin_first_name . '</h5>

								<label>Тема</label>
								<p>' . $a['theme'] . '</p>

								<label>Сообщение</label>
								<p>' . $a['message'] . '</p>

								<label>Ответ</label>
								<p>' . $a['answer'] . '</p>
							</div>
							';
						}

						mysqli_query($connection, "UPDATE `support_tickets` SET `user_viewed` = 1 WHERE `id` = '$a_id'");
					}
				?>

			</div>
		</div>
	</div>


		
	<script type="text/javascript">
		
	</script>
	
	<?
		include_once '../../inc/footer.php';
	?>
</body>
</html>