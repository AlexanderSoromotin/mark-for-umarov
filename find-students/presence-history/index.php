<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=8';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Посещаемость</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="screen loading_screen loading"></div>

		<div class="screen request_screen hidden">
			<br>
			<div style="position: absolute; top: 50%; transform: translateY(-50%);" class="description">
				Чтобы начать историю посещений тебе надо состоять в группе
			</div>
		</div>

		<div class="screen add_request_screen hidden">
			<br>
			<div class="description">
				Кажется, ты не состоишь ни в одной группе
			</div>
			<a href="<?= $link ?>/educational-institution/">
				<button class="button-3">Подать заявку на вступление в группу</button>
			</a>
		</div>

		<div class="screen presence_screen hidden">
			<div class="row-1">
				<div class="avatar">
					<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
				</div>
				<div class="user_info">
					<p class="username"><?= $user_last_name . ' ' . $user_first_name ?></p>
					

					<div class="presence_history">
						<p class="date">История посещений</p>

						<ul>
							<li class="empty_history">
								<p>История посещений пустует</p>
							</li>
							<!-- <li>
								<div class="time">
									<img src="<?= $link ?>/assets/img/icons/square-plus.svg">
									12:03
								</div>
								<b>Отметка о явке</b> 
							</li>
							<li>
								<div class="time">
									<img src="<?= $link ?>/assets/img/icons/square-minus.svg">
									15:43
								</div> <b>Отметка об уходе</b> 
							</li>
							<li>
								<div class="time">
									<img src="<?= $link ?>/assets/img/icons/square-plus.svg">
									16:23
								</div> <b>Отметка о явке</b> 
							</li> -->
						</ul>
					</div>
				</div>
			</div>
			<button class="button-3 add_presence">Я нахожусь на занятии!</button>
		</div>

	</main>

	<script type="text/javascript">

		function reloadPresence (type) {
			// $('.presence_screen').addClass('loading');
			x = new Date();
			if (type == 'smooth') {
				$('.presence_screen').css({'opacity': '.3'});
			}
			
			$.ajax({
				url: "<?= $link ?>/api/managePresence.php",
				type: "POST",
				cache: false,
				data: {
					type: 'get-user-presence',
					token: '<?= $user_token ?>',
					timezone: x.getTimezoneOffset() * -1
				},
				success: function (result) {
					console.log('reloadPresence', result)
					result = JSON.parse(result);
					console.log('reloadPresence json:', result);

					if (result['success']) {

						$('.presence_history .date').text('История посещений на ' + result['response']['date'] + 'г.');

						if (result['response']['active']) {
							$('.add_presence').removeClass('add_presence').removeClass('button-3').addClass('remove_presence').addClass('button-1').text('Я больше не нахожусь на занятии')
						} else {
							$('.remove_presence').removeClass('remove_presence').removeClass('button-1').addClass('add_presence').addClass('button-3').text('Я нахожусь на занятии!')
						}
						
						$('.empty_history').css({'display' : 'none'});
						$('.presence_screen ul li').remove();
						result['response']['history'].reverse()
						for (index in result['response']['history']) {
							event = result['response']['history'][index];
							// console.log(event)

							if (event['activity'] == 'join') {
								time = event['time']
								$('.presence_screen ul').append('<li><div class="time"><img src="<?= $link ?>/assets/img/icons/square-plus.svg">' + time + '</div><b>Отметка о явке</b> </li>')
							}
							if (event['activity'] == 'leave') {
								time = event['time']
								$('.presence_screen ul').append('<li><div class="time"><img src="<?= $link ?>/assets/img/icons/square-minus.svg">' + time + '</div><b>Отметка об уходе</b> </li>')
							}
							if (event['activity'] == 'forcibly-join') {
								time = event['time']
								$('.presence_screen ul').append('<li><div class="time"><img src="<?= $link ?>/assets/img/icons/square-plus.svg">' + time + '</div><b>Староста отметил твою явку</b> </li>')
							}
							if (event['activity'] == 'forcibly-leave') {
								time = event['time']
								$('.presence_screen ul').append('<li><div class="time"><img src="<?= $link ?>/assets/img/icons/square-minus.svg">' + time + '</div><b>Староста снял твою явку</b> </li>')
							}
						}

						$('.present_students').append(result['response']['present_students']);
						$('.missing_students').append(result['response']['missing_students']);
					} else {
						$('.empty_history').css({'display' : 'flex'});
					}
					if (type == 'smooth') {
						setTimeout(function () {
							// $('.presence_screen').removeClass('loading');
							$('.presence_screen').css({'opacity': '1'});
						}, 500)
					}
					if (type == 'onload') {
						$('.loading_screen').addClass('deleted');
						$('.presence_screen').removeClass('hidden');
					}
				}
			})
		}

		$.ajax({
			url: "<?= $link ?>/api/getUserData.php",
			cache: false,
			type: "POST",
			data: {
				type: "get-education-data",
				token: '<?= $user_token ?>'
			},
			success: function (result) {
				console.log("get-user-group", result)
				result = JSON.parse(result)
				console.log("get-user-group json:", result)
				if (result['response']['group_id'] != null) {
					group_id = result['response']['group_id'];
					$.ajax({
						url: "<?= $link ?>/api/getGroupData.php",
						cache: false,
						type: "POST",
						data: {
							type: "get-short-info",
							token: '<?= $user_token ?>',
							group_id: group_id
						},
						success: function (result) {
							console.log("get-short-info", result)
							if (result != '') {
								result = JSON.parse(result);
								console.log("get-short-info json:", result)

								$('.request_screen').prepend(result['response']['html'])
								reloadPresence('onload');
							}
						}
					})
				} else {
					$.ajax({
						url: "<?= $link ?>/api/getUserData.php",
						cache: false,
						type: "POST",
						data: {
							type: "check-group-membership-request",
							token: '<?= $user_token ?>'
						},
						success: function (result) {
							console.log("check-group-membership-request", result)
							if (result != '') {
								result = JSON.parse(result);
								console.log("check-group-membership-request json: ", result)

								if (result['response']['groups'].length > 0) {
									$('.request_screen').prepend(result['response']['html'])
									$('.loading_screen').addClass('deleted');
									$('.request_screen').removeClass('hidden');
								} else {
									$('.loading_screen').addClass('deleted');
									$('.add_request_screen').removeClass('hidden');
								}
							}
						}
					})
				}
			}
		})

		$(document).on('click', '.group button', function () {
			group_id = Number($(this).parents('.group').attr('id').replace('group_', ''));
			$.ajax({
				url: "<?= $link ?>/api/manageGroup.php",
				type: "POST",
				cache: false,
				data: {
					type: 'remove-request-and-leave-group',
					token: '<?= $user_token ?>',
					group_id: group_id
				},
				success: function (result) {
					console.log(result)
					if (result['success']) {
						location.href = '<?= $link ?>/educational-institution/';
					}
				}
			})
		})

		let reload_button_rotate = 0;
		$('.reload').click(function () {
			reload_button_rotate += 360;
			$(this).css({"transform": 'rotate(' + reload_button_rotate + 'deg)'})
			reloadStudentsList();
		});

		$(document).on('click', '.add_presence', () => {
			x = new Date();
			$.ajax({
				url: "<?= $link ?>/api/managePresence.php",
				type: "POST",
				cache: false,
				data: {
					type: 'add-user-presence',
					token: '<?= $user_token ?>',
					timezone: x.getTimezoneOffset() * -1
				},
				success: function (result) {
					console.log('Отметка о явке', result);
					if (result != '') {
						result = JSON.parse(result)
						console.log('Отметка о явке json:', result);

						if (result['response'] == 'head student removed presence mark') {
							button_text = $('.presence_screen button').text();
							$('.presence_screen button').text('Староста снял твою явку, чтобы подтвердить явку обратись к старосте')
						} else {
							// $('.presence_screen button').text('Я нахожусь на занятии');
							reloadPresence();
						}
						
					}
				}
			})
		})

		$(document).on('click', '.remove_presence', () => {
			console.log('-')
			x = new Date();
			$.ajax({
				url: "<?= $link ?>/api/managePresence.php",
				type: "POST",
				cache: false,
				data: {
					type: 'remove-user-presence',
					token: '<?= $user_token ?>',
					timezone: x.getTimezoneOffset() * -1
				},
				success: function (result) {
					console.log('remove presence', result);
					result = JSON.parse(result)
					console.log('remove presence json:', result);
					if (result['success']) {
						reloadPresence();
					}
				}
			})
		})
			
	</script>



	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('presence');	
	</script>
</body>
</html>