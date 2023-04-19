<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=18';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Группа</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="stylesheet" type="text/css" href="mobile.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="reload">
			<img src="<?= $link ?>/assets/img/icons/refresh.svg">
		</div>
		<div class="screen loading_screen loading"></div>
		<div class="screen request_screen hidden">
			<br>
			<div class="description">
				Староста должен принять твою заявку на вступление в группу
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
		<div class="screen group_screen hidden">
			<ul class="missing_students">
				<center><p class="title">Отсутствующие студенты</p></center>
				<div class="no_missing_students">
						<img draggable="false" style="" src="<?= $link ?>/assets/img/icons/flame.svg">
						<p class="username">Всё студенты в сборе!</p>
				</div>

				
			</ul>

			<ul class="present_students">
				<center><p class="title">Присутствующие студенты</p></center>
				<div class="no_present_students">
						<img draggable="false" style="" src="<?= $link ?>/assets/img/icons/armchair-2.svg">
						<p class="username">Улыбаемся и отдыхаем.</p>
				</div>
				<!-- <li>
					<div class="avatar">
						<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
					</div>
					<div class="user_info">
						<p class="username">Соромотин Александр</p>
						<p class="status">Явка подтверждена в 12:03 <img src="<?= $link ?>/assets/img/icons/circle-check.svg"></p>
					</div>
				</li> -->
			</ul>
		</div>

	</main>

	<script type="text/javascript">
		// function sortSurname(arr) {
		// 	if (x.last_name < y.last_name) {return -1;}
		//     if (x.last_name > y.last_name) {return 1;}
		//     return 0;
		// }

		function reloadStudentsList (type) {
			// $('.group_screen').addClass('loading');
			if (type == 'smooth') {
				$('.group_screen').css({'opacity': '.3'});
			}
			
			x = new Date();
			$.ajax({
				url: "<?= $link ?>/api/getGroupData.php",
				type: "POST",
				cache: false,
				data: {
					type: 'get-students-list',
					token: '<?= $user_token ?>',
					group_id: '<?= $user_group_id ?>',
					get_html: true,
					timezone: x.getTimezoneOffset() * -1
				},
				success: function (result) {
					if (result != '') {
						console.log('получение списка пользователей группы', result)
						result = JSON.parse(result);
						console.log('получение списка пользователей группы json:', result)
						// result['response']['present_students'].sort(sortSurname)
						// result['response']['missing_students'].sort(sortSurname)
						$('.present_students li').remove();
						$('.missing_students li').remove();


						for (index in result['response']['present_students']) {
							user_data = result['response']['present_students'][index];

							$('.present_students').append(user_data['html']);
						}
						// $('.present_students').append(result['response']['present_students']);

						for (index in result['response']['missing_students']) {
							user_data = result['response']['missing_students'][index];

							$('.missing_students').append(user_data['html']);
						}
						// $('.missing_students').append(result['response']['missing_students']);

						if (result['response']['missing_students'] == '') {
							$('.no_missing_students').css({'display': 'flex'});
							$('.missing_students center').css({'display': 'none'})
							$('.present_students center').css({'display': 'none'})
						}

						if (result['response']['present_students'] == '') {
							$('.no_present_students').css({'display': 'flex'});
							// $('.missing_students center').css({'display': 'none'})
							$('.present_students center').css({'display': 'none'})
						}

						if (type == 'smooth') {
							setTimeout(function () {
								// $('.group_screen').removeClass('loading');
								$('.group_screen').css({'opacity': '1'});
							}, 300)
						}
						if (type == 'onload') {
							$('.loading_screen').addClass('deleted');
							$('.group_screen').removeClass('hidden');
							$('.group_screen').removeClass('hidden');
						}
						
						
					}
				}
			})
		}

		$.ajax({
			url: "<?= $link ?>/api/getUserData.php",
			cache: false,
			type: "POST",
			data: {
				type: 'get-education-data',
				token: '<?= $user_token ?>'
			},
			success: function (result) {
				console.log("get-user-education", result)
				if (result != '') {
					result = JSON.parse(result);

					console.log("get-user-group", result)
					if (result['response']['group_id'] != null) {
						group_id = result['response']['group_id'];
						$.ajax({
							url: "<?= $link ?>/api/getGroupData.php",
							cache: false,
							type: "POST",
							data: {
								type: "get-short-info",
								token: '<?= $user_token ?>',
								// get_html: true,
								group_id: group_id
							},
							success: function (result) {
								console.log("get-short-info", result)
								if (result != '') {
									result = JSON.parse(result);
									console.log("get-short-info", result)

									// $('.request_screen').prepend(result['response']['html'])
									reloadStudentsList('onload');
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

									if (result['response']['groups'].length > 0) {
										for (index in result['response']['groups']) {
											group_id = result['response']['groups'][index];
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
													console.log(result, 1)
													if (result != '') {
														result = JSON.parse(result);
														console.log(result, 2)

														$('.request_screen').prepend(result['html'])
														$('.loading_screen').addClass('deleted');
														$('.request_screen').removeClass('hidden');
													}
												}
											})
										}
									} else {
										$('.loading_screen').addClass('deleted');
										$('.add_request_screen').removeClass('hidden');
									}
								}
							}
						})
					}
				}
				
			}
		})

		$(document).on('click', '.group button', function () {
			group_id = Number($(this).parents('.group').attr('id').replace('group_', ''));
			$.ajax({
				url: "<?= $link ?>/inc/manageGroup.php",
				type: "POST",
				cache: false,
				data: {
					type: 'remove-request-and-leave-group',
					token: '<?= $user_token ?>',
					group_id: group_id
				},
				success: function (result) {
					console.log(result)
					if (result == 'success') {
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
	</script>



	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('group');	
	</script>
</body>
</html>