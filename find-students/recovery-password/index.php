<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
// redirect('unlogged', '/authorization');

$cache_ver = '?v=6';

?>

<?
	if (isset($_GET['recovery_token'])) :
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Восстановление пароля</title>
	
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">


	<main>
		<center>
			<div class="title">
				Восстановление пароля
			</div>
		</center>

		<?
			$recovery_token = $_GET['recovery_token'];
			$recovery_data = mysqli_query($connection, "SELECT * FROM `recovery_password_codes` WHERE `recovery_token` = '$recovery_token'");

			if ($recovery_data -> num_rows == 0) :
		?>

		<div class="empty">Данная сессия восстановления пароля истекла</div>

		<? else : ?>

		<?	
			$recovery_data = mysqli_fetch_assoc($recovery_data);
			$email = $recovery_data['email'];
		?>
		<form method="post" action="<?= $link ?>/api/recoveryPassword.php">
			<input type="hidden" name="type" value="web-change-password">
			<input type="hidden" name="recovery_token" value="<?= $recovery_token ?>">
			<div class="changePassword">
				<div style="width: 100%; margin-top: 20px;" class="description">
					Придумайте новый пароль к аккаунту <b><?= $email ?></b>. Пароль должен содержать не менее 8 символов
				</div>

				<div class="input">
					<input class="email_input" type="email" name="email" value="<?= $email ?>">
					<input autocomplete="false" autocomplete="disable" autocomplete="disabled" type="password" name="password" placeholder="Придумайте новый пароль">
				</div>

				<div class="save_password">
					<p class="error_field"></p>
					<button type="button" class="button-3">
						Сохранить
					</button>
				</div>

			</div>
		</form>

		<? endif; ?>
	</main>

	<?
		// include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		$('.save_password button').click(function () {
			if ($('.changePassword input:eq(1)').val().length < 8) {
				$('.changePassword .error_field').text('Длина пароля должна быть не менее 8 символов');
				return;
			}

			$('form').submit();
		})
	</script>
</body>
</html>




<? else : ?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Восстановление пароля</title>
	
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">


	<main>
		<center>
			<div class="title">
				Восстановление пароля
			</div>
		</center>

		<div class="screens">
			<div class="screen getRecoveryCode showed_screen">
				<div class="input">
					<input type="email" name="" placeholder="Введите эл. почту" value="<?= $_GET['email']?>">
				</div>
				<div class="description">
					На неё будет выслана инструкция для сброса пароля
				</div>

				<div class="send_code">
					<p class="error_field"></p>
					<button class="button-3">
						Продолжить
					</button>
				</div>

			</div>

			<div class="screen confirmRecoveryCode">
				<div class="enter_code">
					<div style="width: 100%;" class="description">
						На <b></b> выслана инструкция по восстановлению пароля. Если вы не нашли письмо, проверьте папку "Спам"
					</div>

					<!-- <div class="description">
						На <b></b> выслан код, введите его в поля ниже
					</div> -->
					<!-- <ul>
						<li>
							<input maxlength="1" type="text" name="">
						</li>
						<li>
							<input maxlength="1" type="text" name="">
						</li>
						<li>
							<input maxlength="1" type="text" name="">
						</li>
						<li>
							<input maxlength="1" type="text" name="">
						</li>
						<li>
							<input maxlength="1" type="text" name="">
						</li>
					</ul>

					<div class="confirm_code">
						<p class="error_field"></p>
						<button class="button-3">Продолжить</button>
					</div> -->
				</div>
			</div>
		</div>

	</main>



	<?
		// include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		function getRecoveryCode () {
			email = $('.getRecoveryCode input').val()
			$('.getRecoveryCode .error_field').text('');

			if (email != '') {
				$.ajax({
					url: "<?= $link ?>/api/web_authorization.php",
					type: 'POST',
					cache: false,
					data: {
						step: "check_email",
						email: email
					},
					success: function (result) {
						result = JSON.parse(result)
						console.log(result)

						if (result['response'] == 'unregistered email') {
							$('.getRecoveryCode .error_field').text('Эта эл. почта не зарегистрирована в системе');
						} else {
							$.ajax({
								url: "<?= $link ?>/api/recoveryPassword.php",
								type: 'POST',
								cache: false,
								data: {
									type: "send-recovery-code",
									email: email
								},
								success: function (result) {
									console.log('Код отправлен', result)
								}
							})
							$('.getRecoveryCode').removeClass('showed_screen').addClass('deleted_screen')
							$('.confirmRecoveryCode b').text(email)
							$('.confirmRecoveryCode').removeClass('deleted_screen').addClass('showed_screen')

						}
					}
				})
			}
		}

		$('.getRecoveryCode button').click(() => getRecoveryCode());
		function confirmRecoveryCode () {

		}

		$('.enter_code input').keyup(function (e) {
			let index = $(this).parents('li').index();

			if ($(this).val() != '') {
				$('.enter_code ul li:eq(' + (index + 1) + ') input').focus();
			} else {
				if (index != 0) {
					$('.enter_code ul li:eq(' + (index - 1) + ') input').focus();
				}
				
			}
		})
	</script>
</body>
</html>

<? endif; ?>