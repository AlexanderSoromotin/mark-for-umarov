<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=13';

$local_user_was_found = false;

if ($_GET['id'] != '') {
	$local_user_id = $_GET['id'];
	$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");
	if ($local_user_data -> num_rows != 0) {
		$local_user_data = mysqli_fetch_assoc($local_user_data);
		$local_user_was_found = true;
	}
} else {
	$local_user_data = $result;
	$local_user_was_found = true;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Настройки безопасности</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="activity">
			<h3 class="h3_title">Последняя активность</h3>
			<div class="empty">Тут пока пусто</div>
			<ul>

				<? 
				$count = 0;
				// var_dump($user_login_history) 
				foreach ($user_login_history as $key => $value) {
					// echo var_dump($value['details']) . '<br><br>';
					if (explode(' ', $value['date'])[0] == date('d.m.Y')) {
						$date = 'Сегодня в ' . explode(' ', $value['date'])[1] . ' (МСК)';
					} else {
						$date = explode('.', explode(' ', $value['date'])[0])[0] . ' ' . $months_accusative[explode('.', explode(' ', $value['date'])[0])[1]] . ' в ' . explode(' ', $value['date'])[1] . ' (МСК)';
					}

					$activity_country = $value['details']['country'];
					$activity_region = $value['details']['region'];
					$activity_city = $value['details']['city'];
					$activity_browser = $value['details']['browser'];

					if ($activity_country == '') {
						$activity_country = 'Страна не определена';
					}
					if ($activity_region == '') {
						$activity_region = 'Регион не определён';
					}
					if ($activity_city == '') {
						$activity_city = 'Город не определён';
					}
					if ($activity_browser == '') {
						$activity_browser = 'не определён';
					}

					$activity_location = $activity_country . ', ' . $activity_region . ', ' . $activity_city;
					if ($activity_city == 'Город не определён') {
						$activity_location = $activity_country . ', ' . $activity_region;
					}
					if ($activity_region == 'Регион не определён') {
						$activity_location = $activity_country;
					}
					if ($activity_country == 'Страна не определена') {
						$activity_location = 'Местоположение не определено';
					}

					// $fff = var_dump($value['details']);

					// echo '
					// 	<li>
					// 	<div class="findcreek_logo">
					// 		FINDCREEK
					// 	</div>
					// 	<div class="details">
					// 		<div class="location">
					// 			' . $activity_location . ': Браузер ' . $value['details']['browser'] . '
					// 		</div>
					// 		<div class="date">
					// 			' . $date . '
					// 		</div>
					// 	</div>
					// </li>';
					$count++;
					if ($count >= 5) {
						// echo '<a class="see_all_activity" href="#">Посмотреть все</a>';
						break;
					}
				}

				?>
			</ul>
			<hr>
			<div class="change_password security_settings">
				<div class="settings_name">
					<img src="<?= $link ?>/assets/img/icons/shield-lock.svg">
					Пароль
				</div>
				<div class="settings_value">
					<?	
						if ($user_password_change_history != null) {
							$user_password_change_history = json_decode($user_password_change_history, 1);

							$last_change_password = end($user_password_change_history);

							$last_change_password_date = $last_change_password['date'];

							echo 'Изменён ' . mb_substr($last_change_password_date, 0, 2) . ' ' . $months_short[mb_substr($last_change_password_date, 3, 2)] . ' ' . mb_substr($last_change_password_date, 6, 4);
						} else {
							if ($user_google_id != '') {
								echo 'Вход через Google';
							}
							else if ($user_vk_id != '') {
								echo 'Вход через VK';
							} else {
								echo 'Изменён ' . mb_substr($user_registration_date, 8, 2) . ' ' . $months_short[mb_substr($user_registration_date, 5, 2)] . ' ' . mb_substr($user_registration_date, 0, 4);
							} 
						}

						

						
					?>
				</div>
			</div>

			<div class="2_step_verif security_settings">
				<div class="settings_name">
					<img src="<?= $link ?>/assets/img/icons/2fa.svg">
					двухфакторная аут.
				</div>
				<div class="settings_value">
					Выключена
				</div>
			</div>

			<div class="linked_devices security_settings">
				<div class="settings_name">
					<img src="<?= $link ?>/assets/img/icons/devices-2.svg">
					Подключённые устройства
				</div>
				<div class="settings_value">
					-
				</div>
			</div>

			<hr>

			
			<div class="controls">
				<button class="end_all_sessions button-1">Завершить все сессии</button>
			
				<a href="<?= $link ?>/recovery-password/?email=<?= $user_email ?>">
					<button class="button-1">Изменить пароль</button>
				</a>
				<button class="delete_account button-5">Удалить FINDCREEK ID аккаунт</button>

			</div>
			<!-- <button class="change_password button-5">Сменить пароль</button> -->
			<!-- <button class="linked_devices button-5">Привязанные устройства</button> -->
		</div>
		
	</main>

	<!-- <div class="edit_thumbnail">
	</div> -->	

	<script type="text/javascript">
		
	</script>

	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>

		$('.end_all_sessions').click(function () {
			$.ajax({
				url: "<?= $link ?>/api/editProfile.php",
				type: "POST",
				cache: false,
				data: {
					type: 'reset-token',
					token: '<?= $user_token ?>'
				},
				success: function (result) {
					console.log('Выход из всех сессий', result);
					result = JSON.parse(result)
					console.log('Выход из всех сессий json:', result);

					if (result['response'] == 'token changed') {
						location.href = "<?= $link ?>/authorization";
					}
				}
			})
		})
		select_mobile_footer_tab('settings');
		function getRandomInt (max) {
			return Math.floor(Math.random() * max);
		}

	</script>

</body>
</html>
