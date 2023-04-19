<?php
// header("Location: " . $link . '/group');
include_once 'inc/config.php';
include_once 'inc/userData.php';
include_once 'inc/redirect.php';

// redirect('banned', '/banned');
// redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=1';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Главная страница MARK</title>
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once 'inc/head.php';
		// include_once 'inc/header.php';
	?>


	<?
		// include_once 'inc/mobile_toolbar.php';
	?>

	<script>
		if (localStorage.getItem('accept-invite') != '') {
			$.ajax({
				url: '<?= $link ?>/api/getEducationData.php',
				type: "POST",
				data: {
					type: 'accept-invite',
					token: '<?= $user_token ?>',
					invite_id: localStorage.getItem('accept-invite')
				},
				success: function (result) {
					console.log(result)
					if (result['success']) {
						localStorage.removeItem('accept-invite');
						location.href = "<?= $link ?>/group";
					}
				}
			})
			location.href = "<?= $link ?>/presence-history";
				
		}
	</script>
</body>
</html>