<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	// if ($user_status != 'pre-deleted') {
	// 	header('Location: ' . $link);
	// }
	// if ($user_status == 'deleted') {
	// 	header('Location: ' . $link . '/inc/logout.php');
	// }

	// include_once '../inc/redirect.php';
	// redirect('User', '/');
	// redirect('Admin', '/');
	// redirect('Banned', '/banned');
	// redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Удалённый аккаунт</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?
		// include_once '../inc/header.php'; // Шапка
		// include_once '../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->

	<div class="main">
		<h2>Ваш аккаунт <b><?= $user_email ?></b> был удалён</h2>
		<?	
			function deleteZeroes ($text) {
				if ($text[0] == '0') {
					return $text[1];
				}
				return $text;
			}
			function addZeroes ($text) {
				if (strlen($text) == 1) {
					return '0' . $text;
				}
				return $text;
			}

			$PreDeleted_day = substr($user_delete_account_date, 0, 2);
			$PreDeleted_month = (int) substr($user_delete_account_date, 3, 2);
			$PreDeleted_year = substr($user_delete_account_date, 6, 4);

			$deleted_month = $PreDeleted_month + 6;
			$deleted_year = $PreDeleted_year;

			if ($deleted_month > 12) {
				$deleted_month -= 12;
				$deleted_year = $PreDeleted_year++;
			}



			$date = deleteZeroes($PreDeleted_day) . ' ' . $months_accusative[addZeroes($deleted_month)] . ' ' . $deleted_year . ' года';
		?>
		<p>У вас есть возможность восстановить его до <?= $date ?>.</p>

		<div class="buttons">
			<button name="recovery" class="button-1">Восстановить аккаунт</button>
			<a href="<?= $link ?>/inc/logout.php"><button name="" class="button-3">Выйти из аккаунта</button></a>
		</div>
	</div>

	<script type="text/javascript">
		$('button[name="recovery"]').click(function () {
			$.ajax({
				url: '<?= $link ?>/inc/profile.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'recovery-account',
					secret_id: '<?= md5('user_' . $user_token . '_recoveryAccount')?>',
				},
				success: function () {
					location.href = "<?= $link ?>/profile";
				}
			})
		})
	</script>
		
	<?
		include_once '../inc/footer.php';
	?>
</body>
</html>