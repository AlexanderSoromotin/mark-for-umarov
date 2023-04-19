<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

if ($user_group_data['head_student'] != $user_id and $user_group_data['deputy_head_student'] != $user_id and $user_status != 'Admin') {
	header("Location: " . $link . '/');
}

$cache_ver = '?v=28';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Панель управления группой</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
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
			<div class="group_settings">
				<button class="create_invitation button-3">
					Пригласить людей
					<img src="<?= $link ?>/assets/img/icons/user-plus.svg">
				</button>

				<!-- <button class="create_report button-1">
					Сформировать отчёт
					<img src="<?= $link ?>/assets/img/icons/file-text.svg">
				</button> -->

				<button class="manage_reports button-1">
					Управление отчётами посещаемости
					<img src="<?= $link ?>/assets/img/icons/file-text.svg">
				</button>

				<button class="add_student button-1">
					Добавить студентов вручную
					<img src="<?= $link ?>/assets/img/icons/robot.svg">
				</button>
			</div>
			<ul class="requests">
				<center><p class="title">Запросы на вступление в группу</p></center>
			</ul>

			<div class="tabs">
				<ul>
					<li class="group_screen_tab_list selected">Список <b></b></li>
					<li class="group_screen_tab_manage">Управление</li>
				</ul>
			</div>

			<ul class="students_list">
				<!-- <center><p class="title">Список студентов</p></center> -->
			</ul>
		</div>

		<div class="popup_menu">
			<div class="popup_container create_invitation_popup">
				<div class="popup_header">
					<h1>Создание приглашения</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<p style="margin-top: 0px;" class="popup_content_title">Отправь ссылку своим друзьям</p>
					<div class="link_input">
						<div class="link_field copy_text">
							<?
								$invite_data = mysqli_query($connection, "SELECT * FROM `invites` WHERE `group_id` = '$user_group_id'");
								if ($invite_data -> num_rows != 0) {
									$invite_data = mysqli_fetch_assoc($invite_data);
									$invite_data_text_id = $invite_data['text_id'];
								} else {
									$uid = '';
									while (1) {
										$arr = array_merge(range('0','9'), range('a','z'), range('A','Z'));
										$arr = array_flip($arr);

										for ($i = 0; $i < 8; $i++){
										    $uid .= array_rand($arr, 1);
										}
										if (mysqli_query($connection, "SELECT `id` FROM `invites` WHERE `text_id` = '$uid'") -> num_rows == 0) {
											break;
										}
									}
									// $invite_data_text_id = uniqid();
									$invite_data_text_id = $uid;

									$invite_date = date('d.m.Y H:i:s');
									mysqli_query($connection, "INSERT INTO `invites` (`text_id`, `inviting_user_id`, `group_id`, `date`, `expires`) VALUES ('$invite_data_text_id', '$user_id', '$user_group_id', '$invite_date', 0)");
								}
								$invitation_link = 'https://findcreek.com' . '?fs_invite=' . $invite_data_text_id;
							?>
							<?= $invitation_link ?>
						</div>
						<button class="copy_link">
							<p>copied!</p>
							<img src="<?= $link ?>/assets/img/icons/copy.svg">
						</button>
					</div>
					<center>
						<p class="popup_content_title">Или... Просто покажи QR</p>
					</center>

					<div class="qr_invitation">
					</div>
					<p class="description">По приглашению пользователь автоматически появится в списке студентов</p>

					<button class="create_new_invitation button-1">Сформировать новую ссылку</button>
					<p class="description">Все предыдущие ссылки перестанут работать</p>
				</div>
			</div>


			<!-- <div class="popup_container create_report_popup loading">
				<div class="popup_header">
					<h1>Управление отчётами</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<p class="popup_content_title">Отправь ссылку преподавателю</p>
					<div class="link_input">
						<div class="link_field copy_text">
							Формируется ссылка...
						</div>
						<button class="copy_link">
							<p>copied!</p>
							<img src="<?= $link ?>/assets/img/icons/copy.svg">
						</button>
					</div>
					<p class="description">Для каждого нового отчёта генерируется новая уникальная ссылка</p>
				</div>
			</div> -->

			<div class="popup_container manage_reports_popup ">
				<div class="popup_header">
					<h1>Управление отчётами</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<button class="button-3 create_new_report">Сформировать отчёт</button>
					<div class="new_report hidden">
						<p class="popup_content_title">Отчёт доступен по ссылке:</p>
						<div class="link_input loading">
							<div class="link_field copy_text">
								Формируется ссылка...
							</div>
							<button class="copy_link">
								<p>copied!</p>
								<img src="<?= $link ?>/assets/img/icons/copy.svg">
							</button>
						</div>
						<p class="description">Для каждого нового отчёта генерируется новая уникальная ссылка</p>
					</div>
					
					<p style="margin-top: 20px;" class="popup_content_title">Последние 10 отчётов</p>
					<div class="reports_list loading">

					</div>
				</div>
			</div>

			<div class="popup_container user_management_popup">
				<div class="popup_header">
					<h1>Управление пользователем</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<div class="user_info">
						<div class="avatar">
							<img src="">
						</div>
						<div class="username">
							<p class="user_name">Имя пользователя</p>
							<p class="user_status"></p>
						</div>
					</div>

					<!-- <p class="popup_content_title">Отправь ссылку преподавателю</p> -->
					<div class="empty">
						Ничего не поделаешь
						<img src="<?= $link ?>/assets/img/icons/mood-neutral.svg">
					</div>
					<button class="remove_from_post_deputy_head_student button-1">
						Снять с должности заместителя
						<img src="<?= $link ?>/assets/img/icons/crown-off.svg">
					</button>
					<button class="appoint_deputy_head_student button-1">
						Назначить заместителем
						<img src="<?= $link ?>/assets/img/icons/crown.svg">
					</button>
					<button class="appoint_head_student button-1">
						Назначить старостой
						<img src="<?= $link ?>/assets/img/icons/crown.svg">
					</button>
					<button class="exclude_from_group button-1">
						Исключить из группы
						<img src="<?= $link ?>/assets/img/icons/trash.svg">
					</button>
				</div>
			</div>

			<div class="popup_container add_student_popup">
				<div class="popup_header">
					<h1>Добавление студентов</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<div class="empty">
						Что ж, если твои студенты не могут или очень ленивы для того, чтобы зайти в группу, то ты можешь их добавить вручную.
					</div>

					<input type="" name="" placeholder="Имя студента">
					<input type="" name="" placeholder="Фамилия студента">

					<button class="button-3">
						Добавить
					</button>
					<p class="description">В списке группы появится новый студент, одно но - это робот, но ты так же сможешь управлять его явкой и, как и обычный сутдент, он будет отображен в отчётах.</p>

					
					<!-- <button class="not_working create_file_report button-5">
						Сгенерировать файл
					</button> -->
				</div>
			</div>
		</div>
	</main>

	<script type="text/javascript">
		<?
			if ($user_group_data['head_student'] == $user_id) {
				$main_user_group_status = 'head_student';
			}
			if ($user_group_data['deputy_head_student'] == $user_id) {
				$main_user_group_status = 'deputy_head_student';
			}
		?>
		var main_user_group_status = '<?= $main_user_group_status ?>';
		var main_user_status = '<?= $user_status ?>';


		function reloadReportList () {
			$('.manage_reports_popup .reports_list').addClass('loading');
			$.ajax({
				url: "<?= $link ?>/api/getGroupData.php",
				type: "POST",
				cache: false,
				data: {
					type: "get-reports-list",
					token: "<?= $user_token ?>",
					html: true
				},
				success: function (result) {
					// console.log('Обновление списка отчётов', result)
					result = JSON.parse(result)
					// console.log('Обновление списка отчётов json:', result)

					$('.reports_list .report').remove();
					$('.reports_list').append(result['response']['html']);
					$('.manage_reports_popup .reports_list').removeClass('loading');
				}
			})
		}

		$('.qr_invitation').qrcode({width: 200,height: 200,text: $('.create_invitation_popup .link_field').text()});
		console.log($('.create_invitation_popup .link_field').text())

		$('.create_invitation').click(() => {
			$('.popup_container').removeClass('opened_popup_container');
			$('.popup_menu').addClass('opened_popup_menu');
			$('.create_invitation_popup').addClass('opened_popup_container');
		})

		$('.create_report').click(() => {
			$('.popup_container').removeClass('opened_popup_container');
			$('.popup_menu').addClass('opened_popup_menu');
			$('.create_report_popup').addClass('opened_popup_container');
		})

		$('.manage_reports').click(() => {
			$('.popup_container').removeClass('opened_popup_container');
			$('.popup_menu').addClass('opened_popup_menu');
			$('.manage_reports_popup').addClass('opened_popup_container');
			reloadReportList();

		})

		$(document).on('click', '.reports_list .report .update_students_list', function () {
			report_id = $(this).parents('.report').attr('id').replace('report_', '');
			$('.manage_reports_popup .reports_list .students').addClass('loading');
			$.ajax({
				url: "<?= $link ?>/api/getGroupData.php",
				type: "POST",
				cache: false,
				data: {
					type: "update-report",
					token: "<?= $user_token ?>",
					get_students_list: true,
					html: true,
					report_id: report_id
				},
				success: function (result) {
					console.log('Обновление списка студентов отчёта', result)
					result = JSON.parse(result)
					console.log('Обновление списка студентов отчёта json:', result)

					// $('.reports_list .report').remove();
					$('.reports_list').append(result['response']['html']);
					$('.manage_reports_popup .reports_list .students').removeClass('loading');
					reloadReportList();
				}
			})	
			// 

		})

		$(document).on('click', '.user_management', function () {
			user_avatar = $(this).parents('li').find('.avatar img').attr('src');
			user_avatar_style = $(this).parents('li').find('.avatar img').css('transform');
			user_name = $(this).parents('li').find('.username').text();
			$('.user_management_popup .user_info .avatar img').attr('src', user_avatar).css({'transform': user_avatar_style});
			$('.user_management_popup .user_info .username .user_name').text(user_name);
			user_id = $(this).parents('li').attr('id').split('_')[2];
			if ($(this).parents('li').attr('id').split('_')[3] != '') {
				user_id	+= '_' + $(this).parents('li').attr('id').split('_')[3];
			}
			$('.user_management_popup').attr('managing_user_id', user_id);

			user_status = $(this).parents('li').attr('user_status');
			group_status = $(this).parents('li').attr('group_status');
			$('.exclude_from_group').addClass('button-1').removeClass('button-5')

			console.log(main_user_group_status)
			console.log('group_status', group_status)

			
			if (group_status == 'head_student') {
				$('.user_management_popup .user_info .username .user_status').text('Староста');
			} 
			else if (group_status == 'deputy_head_student') {
				$('.user_management_popup .user_info .username .user_status').text('Заместитель старосты');
			}
			else if (group_status == 'robot') {
				$('.user_management_popup .user_info .username .user_status').text('Робот');
			}
			else {
				$('.user_management_popup .user_info .username .user_status').text('Студент');

				if (user_status == 'Admin') {
					$('.user_management_popup .user_info .username .user_status').text('Администратор');
				} 
			}
			

			$('.user_management_popup button').css({'display': 'none'});
			$('.user_management_popup .empty').css({'display': 'none'});

			

			if (main_user_group_status == 'deputy_head_student') {
				if (group_status == 'student') {
					// $('.appoint_deputy_head_student').css({'display': 'flex'})
					// $('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
				}

				if (group_status == 'robot') {
					// $('.appoint_deputy_head_student').css({'display': 'flex'})
					// $('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
				}

				if (group_status == 'deputy_head_student') {
					// $('.remove_from_post_deputy_head_student').css({'display': 'flex'})
					// $('.appoint_head_student').css({'display': 'flex'})
					// $('.exclude_from_group').css({'display': 'flex'})
					$('.user_management_popup .empty').css({'display': 'flex'});
					
				}

				if (group_status == 'head_student') {
					$('.user_management_popup .empty').css({'display': 'flex'});
				}


				if (user_status == 'Admin') {
					$('.exclude_from_group').removeClass('button-1').addClass('button-5')
				}

			} 
			else {
				if (group_status == 'student') {
					$('.appoint_deputy_head_student').css({'display': 'flex'})
					$('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
				}

				if (group_status == 'deputy_head_student') {
					$('.remove_from_post_deputy_head_student').css({'display': 'flex'})
					$('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
					
				}

				if (group_status == 'head_student') {
					$('.user_management_popup .empty').css({'display': 'flex'});
				}

				if (user_status == 'Admin') {
					$('.exclude_from_group').css({'display': 'flex'}).removeClass('button-1').addClass('button-5')
				}
				if (group_status == 'robot') {
					// $('.appoint_deputy_head_student').css({'display': 'flex'})
					// $('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
				}

			}

			if (main_user_status == 'Admin') {
				if (group_status == 'student') {
					$('.appoint_deputy_head_student').css({'display': 'flex'})
					$('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
				}

				if (group_status == 'deputy_head_student') {
					$('.remove_from_post_deputy_head_student').css({'display': 'flex'})
					$('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
					$('.user_management_popup .empty').css({'display': 'none'});
					
				}

				if (group_status == 'head_student') {
					// $('.remove_from_post_deputy_head_student').css({'display': 'flex'})
					// $('.appoint_head_student').css({'display': 'flex'})
					$('.exclude_from_group').css({'display': 'flex'})
					$('.user_management_popup .empty').css({'display': 'none'});
				}

				if (user_status == 'Admin') {
					// $('.exclude_from_group').css({'display': 'flex'}).removeClass('button-1').addClass('button-5')
					$('.user_management_popup .empty').css({'display': 'none'});
				}
			}
			

			$('.popup_container').removeClass('opened_popup_container');
			$('.popup_menu').addClass('opened_popup_menu');
			$('.user_management_popup').addClass('opened_popup_container');
		})

		$('.add_student').click(() => {
			$('.popup_container').removeClass('opened_popup_container');
			$('.popup_menu').addClass('opened_popup_menu');
			$('.add_student_popup').addClass('opened_popup_container');
		})

		$('.close_popup_menu').click(() => {
			$('.popup_container').removeClass('opened_popup_menu');
			$('.popup_menu').removeClass('opened_popup_menu');
		})

		// $('.create_report').click(() => {
		// 	$.ajax({
		// 		url: "<?= $link ?>/api/getGroupData.php",
		// 		type: "POST",
		// 		data: {
		// 			type: "create-report",
		// 			token: '<?= $user_token ?>'
		// 		},
		// 		success: function (result) {
		// 			// console.log(result);
		// 			if (result != '') {
		// 				result = JSON.parse(result)
		// 				console.log('Создание отчёта', result);
		// 				$('.create_report_popup .copy_text').text('<?= $link ?>/visit-report?id=' + result['response']['report_id']);
		// 				$('.create_report_popup').removeClass('loading');
		// 			}
		// 		}
		// 	})
		// })

		$('.create_new_report').click(() => {
			$('.new_report .link_input').addClass('loading');

			$.ajax({
				url: "<?= $link ?>/api/getGroupData.php",
				type: "POST",
				data: {
					type: "create-report",
					token: '<?= $user_token ?>'
				},
				success: function (result) {
					// console.log(result);
					if (result != '') {
						result = JSON.parse(result)
						console.log('Создание отчёта', result);
						$('.new_report').removeClass('hidden')
						$('.new_report .copy_text').text('<?= $link ?>/visit-report?id=' + result['response']['report_id']);
						$('.new_report .link_input').removeClass('loading');
						reloadReportList();
					}
				}
			})
		})

		$('.create_new_invitation').click(() => {
			$('.create_invitation_popup .link_input').addClass('loading');
			$.ajax({
				url: "<?= $link ?>/api/manageGroup.php",
				type: "POST",
				data: {
					type: "create-new-invitation",
					token: '<?= $user_token ?>'
				},
				success: function (result) {
					console.log('создание новой пригласительной ссылки', result);
					result = JSON.parse(result);
					console.log('создание новой пригласительной ссылки json:', result);
					// setTimeout(function () {
						$('.create_invitation_popup .link_input').removeClass('loading');
						console.log(result);
						if (result['success']) {
							console.log('Создание нового приглашения', result);
							$('.create_invitation_popup .copy_text').text(result['response']['link']);
							$('.qr_invitation canvas').remove();
							$('.qr_invitation').qrcode({width: 256,height: 256,text: result['response']['link']});
						}
					// }, 500)
					
				}
			})
		})

		
		$('.group_screen .tabs li').click(function () {
			$('.group_screen .tabs li').removeClass('selected');
			$(this).addClass('selected');

			if ($(this).hasClass('group_screen_tab_manage')) {
				$('.students_list li .add_presence').css({'transform': 'scale(.3)', 'opacity': '0'})
				$('.students_list li .remove_presence').css({'transform': 'scale(.3)', 'opacity': '0'})
				$('.students_list li .user_management').css({'display': 'inline'});
				setTimeout(() => {
					$('.students_list li .user_management').css({'transform': 'scale(1)', 'opacity': '1'})
					$('.students_list li .add_presence').css({'display': 'none'})
					$('.students_list li .remove_presence').css({'display': 'none'})
				}, 100)
			}

			if ($(this).hasClass('group_screen_tab_list')) {
				$('.students_list li .user_management').css({'transform': 'scale(.3)', 'opacity': '0'})

				$('.students_list li .add_presence').css({'display': 'inline'})
				$('.students_list li .remove_presence').css({'display': 'inline'})
				;
				setTimeout(() => {
					$('.students_list li .add_presence').css({'transform': 'scale(1)', 'opacity': '1'})
					$('.students_list li .remove_presence').css({'transform': 'scale(1)', 'opacity': '1'})
					$('.students_list li .user_management').css({'display': 'none'})
					
				}, 100)
			}
		})

		// $(document).on('click', '.students_list .user_management', function () {
		// 	student_id = $(this).parents('li').attr('id').split('_')[2];
		// 	block_id = $(this).parents('li').attr('id')
		// 	student_name = $(this).parents('li').find('.username').text();

		// 	if (confirm('Исключить пользователя ' + student_name + ' из группы?')) {
		// 		$.ajax({
		// 			url: "<?= $link ?>/api/manageGroup.php",
		// 			type: "POST",
		// 			data: {
		// 				type: "kick-user",
		// 				token: '<?= $user_token ?>',
		// 				student_id: student_id 
		// 			},
		// 			success: function (result) {
		// 				if (result != '') {
		// 					result = JSON.parse(result);
		// 					console.log('Исключение пользователя', result);

		// 					if (result['success']) {
		// 						reloadStudentsList('smooth');
		// 					} else {
		// 						$('#' + block_id + ' .user_management img').addClass('negative_shake_animation');
		// 						setTimeout(function () {
		// 							$('#' + block_id + ' .user_management img').removeClass('negative_shake_animation');
		// 						}, 550)
		// 					}
		// 				}
		// 			}
		// 		})
		// 	}
		// })

		$(document).on('click', '.remove_from_post_deputy_head_student', function () {
			user_id = $('.user_management_popup').attr('managing_user_id');
			$.ajax({
				url: '<?= $link ?>/api/manageGroup.php',
				type: "POST",
				data: {
					type: 'remove-from-post-head-student',
					token: '<?= $user_token ?>',
					student_id: user_id,
				},
				success: function (result) {
					console.log('Снятие пользователя с должности заместителя старосты', result);
					result = JSON.parse(result)
					console.log('Снятие пользователя с должности заместителя старосты json:', result);

					if (result['success']) {
						$('.remove_from_post_deputy_head_student').css({'display': 'none'});
						$('.appoint_deputy_head_student').css({'display': 'flex'});
						reloadStudentsList();
						
						if (result['response']['user_status_in_group'] == 'student') {
							$('.user_management_popup .user_status').text('Студент');
						} else if (result['response']['user_status'] == 'Admin') {
							$('.user_management_popup .user_status').text('Администратор');
						} else {
							$('.user_management_popup .user_status').text('Студент');
						}
						
					}
				}
			})
		});


		$(document).on('click', '.appoint_deputy_head_student', function () {
			user_id = $('.user_management_popup').attr('managing_user_id');
			$.ajax({
				url: '<?= $link ?>/api/manageGroup.php',
				type: "POST",
				data: {
					type: 'appoint-deputy-head-student',
					token: '<?= $user_token ?>',
					student_id: user_id,
				},
				success: function (result) {
					console.log('Назначение пользователя заместителем старосты', result);
					result = JSON.parse(result)
					console.log('Назначение пользователя заместителем старосты json:', result);

					if (result['success']) {
						$('.remove_from_post_deputy_head_student').css({'display': 'flex'});
						$('.appoint_deputy_head_student').css({'display': 'none'});

						if (result['response']['user_status_in_group'] == 'deputy_head_student') {
							$('.user_management_popup .user_status').text('Заместитель старосты');
							reloadStudentsList();
						} else if (result['response']['user_status_in_group'] == 'student') {
							location.reload();
						} else if (result['response']['user_status'] == 'Admin') {
							$('.user_management_popup .user_status').text('Администратор');
						} else {
							$('.user_management_popup .user_status').text('Студент');
						}
						
					}
				}
			})
		});

		$(document).on('click', '.appoint_head_student', function () {
			user_id = $('.user_management_popup').attr('managing_user_id');
			$.ajax({
				url: '<?= $link ?>/api/manageGroup.php',
				type: "POST",
				data: {
					type: 'appoint-head-student',
					token: '<?= $user_token ?>',
					student_id: user_id,
				},
				success: function (result) {
					console.log('Назначение пользователя старостой', result);
					result = JSON.parse(result)
					console.log('Назначение пользователя старостой json:', result);

					if (result['success']) {
						$('.remove_from_post_deputy_head_student').css({'display': 'none'});
						$('.appoint_deputy_head_student').css({'display': 'none'});
						$('.appoint_head_student').css({'display': 'none'});
						$('.exclude_from_group').css({'display': 'none'});
						$('.user_management_popup .empty').css({'display': 'flex'})


						if (result['response']['user_status_in_group'] == 'head_student') {
							$('.user_management_popup .user_status').text('Староста');
							reloadStudentsList();
							main_user_group_status = 'deputy_head_student';
						}
						else if (result['response']['user_status'] == 'Admin') {
							$('.user_management_popup .user_status').text('Администратор');
						} 
						else {
							$('.user_management_popup .user_status').text('Студент');
						}
						
					}
				}
			})
		});

		$(document).on('click', '.exclude_from_group', function () {
			user_id = $('.user_management_popup').attr('managing_user_id');
			$.ajax({
				url: '<?= $link ?>/api/manageGroup.php',
				type: "POST",
				data: {
					type: 'exclude-from-group',
					token: '<?= $user_token ?>',
					student_id: user_id,
				},
				success: function (result) {
					console.log('Исключение пользователя из группы', result);
					result = JSON.parse(result)
					console.log('Исключение пользователя из группы json:', result);

					if (result['success']) {
						$('.popup_container').removeClass('opened_popup_menu');
						$('.popup_menu').removeClass('opened_popup_menu');
						$('.create_invitation_popup').removeClass('opened_popup_container');
						reloadStudentsList();
						
					} else {
						$('.exclude_from_group img').addClass('negative_shake_animation');
						setTimeout(function () {
							$('.exclude_from_group img').removeClass('negative_shake_animation');
						}, 520)
					}
				}
			})
		});

		// remove_from_post_deputy_head_student
// appoint_deputy_head_student
// appoint_head_student
// exclude_from_group

		$('.copy_link').click(function () {
			text = $(this).parent().find('.copy_text').text();
			var $tmp = $("<textarea>");
		    $("body").append($tmp);
		    $tmp.val(text).select();
		    document.execCommand("copy");
		    $tmp.remove();

		    $('.copy_link img').css({'transform': 'scale(0)', 'opacity': '0'});
		    setTimeout(function() {
		    	$('.copy_link p').css({'transform': 'translate(-50%, -50%) scale(1)', 'opacity': '1'});
		    	setTimeout(function() {
		    		$('.copy_link p').css({'transform': 'translate(-50%, -50%) scale(0)', 'opacity': '0'});
			    	setTimeout(function() {
				    	$('.copy_link img').css({'transform': 'scale(1)', 'opacity': '1'});
				    }, 200)
			    }, 1000)
		    }, 200)
		    

		})

		$('.link_field').click(function () {
			$(this).select();
		})

		function reloadStudentsList (type) {
			// $('.group_screen').addClass('loading');
			if (type == 'smooth') {
				$('.students_list').css({'opacity': '.3'});
			}
			
			x = new Date();
			$.ajax({
				url: "<?= $link ?>/api/getGroupData.php",
				type: "POST",
				cache: false,
				data: {
					type: 'get-users-data-for-head-student',
					token: '<?= $user_token ?>',
					timezone: x.getTimezoneOffset() * -1
				},
				success: function (result) {
					if (result != '') {
						// console.log(result)
						result = JSON.parse(result);
						console.log('Получение данных для старосты', result);
						$('.students_list li').remove();
						$('.requests li').remove();
						$('.requests center').css({'display': 'none'});
						// $('.requests').remove();

						if (result['response']['requests'] != '') {
							console.log('requests added');
							$('.requests center').css({'display': 'inline'});
							$('.requests').append(result['response']['requests']);
						}

						// $('.present_students');
						$('.students_list').append(result['response']['missing_students']).append(result['response']['present_students']);

						if (type == 'smooth') {
							setTimeout(function () {
								// $('.group_screen').removeClass('loading');
								$('.students_list').css({'opacity': '1'});
							}, 300)
						}
						if (type == 'onload') {
							$('.loading_screen').addClass('deleted');
							$('.group_screen').removeClass('hidden');
							$('.group_screen').removeClass('hidden');
						}

						if ($('.tabs .selected').hasClass('group_screen_tab_list')) {
							$('.students_list li .user_management').css({'transform': 'scale(.3)', 'opacity': '0'})

							$('.students_list li .add_presence').css({'display': 'inline'})
							$('.students_list li .remove_presence').css({'display': 'inline'})
							;
							setTimeout(() => {
								$('.students_list li .add_presence').css({'transform': 'scale(1)', 'opacity': '1'})
								$('.students_list li .remove_presence').css({'transform': 'scale(1)', 'opacity': '1'})
								$('.students_list li .user_management').css({'display': 'none'})
								
							}, 100)
						}
						if ($('.tabs .selected').hasClass('group_screen_tab_manage')) {
							$('.students_list li .add_presence').css({'transform': 'scale(.3)', 'opacity': '0'})
							$('.students_list li .remove_presence').css({'transform': 'scale(.3)', 'opacity': '0'})
							$('.students_list li .user_management').css({'display': 'inline'});
							setTimeout(() => {
								$('.students_list li .user_management').css({'transform': 'scale(1)', 'opacity': '1'})
								$('.students_list li .add_presence').css({'display': 'none'})
								$('.students_list li .remove_presence').css({'display': 'none'})
							}, 100)
						}
						$('.group_screen_tab_list b').text('(' + $('.group_screen .students_list li').length + ')');
						
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
				console.log("get-education-data", result)
				result = JSON.parse(result)
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
							if (result != '') {
								result = JSON.parse(result);
								console.log('Получение краткой информации о группе', result);

								$('.request_screen').prepend(result['html'])
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
							if (result != '') {
								result = JSON.parse(result);
								console.log('check-group-membership-request', result);

								if (result['groups']) {
									for (index in result['groups']) {
										group_id = result['groups'][index];
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
													console.log('Получение краткой информации о группе', result);

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
					if (result != '') {
						result = JSON.parse(result);
						console.log('Выход из группы', result);
					}
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
		$(document).on('click', '.accept_request', (e) => {
			student_id = $(e.target).parents('li').attr('id').replace('request_user_', '');
			// console.log(student_id);
			$.ajax({
				url: "<?= $link ?>/api/manageGroup.php",
				type: "POST",
				cache: false,
				data: {
					type: 'accept-request',
					token: '<?= $user_token ?>',
					student_id: student_id
				},
				success: function (result) {

					reloadStudentsList('smooth');
				}
			})
			$('.mobile_footer_tab_head_student').removeClass('notification');
		})

		$(document).on('click', '.reject_request', (e) => {
			student_id = $(e.target).parents('li').attr('id').replace('request_user_', '');
			// console.log(student_id);
			$.ajax({
				url: "<?= $link ?>/api/manageGroup.php",
				type: "POST",
				cache: false,
				data: {
					type: 'reject-request',
					token: '<?= $user_token ?>',
					student_id: student_id
				},
				success: function (result) {
					reloadStudentsList('smooth');
				}
			})
			$('.mobile_footer_tab_head_student').removeClass('notification');
		})

		$(document).on('click', '.add_presence', (e) => {
			student_id = $(e.target).parents('li').attr('id').replace('missing_user_', '');
			// console.log(student_id);
			$.ajax({
				url: "<?= $link ?>/api/managePresence.php",
				type: "POST",
				cache: false,
				data: {
					type: 'forcibly-add-user-presence',
					token: '<?= $user_token ?>',
					student_id: student_id
				},
				success: function (result) {
					console.log(result)
					reloadStudentsList('smooth');
				}
			})
			// $('.mobile_footer_tab_head_student').removeClass('notification');
		})

		$(document).on('click', '.remove_presence', (e) => {
			student_id = $(e.target).parents('li').attr('id').replace('present_user_', '');
			// console.log(student_id);
			$.ajax({
				url: "<?= $link ?>/api/managePresence.php",
				type: "POST",
				cache: false,
				data: {
					type: 'forcibly-remove-user-presence',
					token: '<?= $user_token ?>',
					student_id: student_id
				},
				success: function (result) {
					console.log(result)
					reloadStudentsList('smooth');
				}
			})
			// $('.mobile_footer_tab_head_student').removeClass('notification');
		})

		$(document).on('click', '.add_student_popup button', function () {
			console.log(12);
			student_name = $('.add_student_popup input:eq(0)').val();
			student_surname = $('.add_student_popup input:eq(1)').val();

			if (student_name.length < 2) {
				$('.add_student_popup input:eq(0)').css({'border-color': '#FF6E78'});
				setTimeout(function () {
					$('.add_student_popup input:eq(0)').css({'border-color': '#fff'});
				}, 500)
				return;
			}

			if (student_surname.length < 2) {
				$('.add_student_popup input:eq(1)').css({'border-color': '#FF6E78'});
				setTimeout(function () {
					$('.add_student_popup input:eq(1)').css({'border-color': '#fff'});
				}, 500)
				return;
			}

			$.ajax({
				url: '<?= $link ?>/api/manageGroup.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'manually-add-student',
					token: '<?= $user_token ?>',
					student_name: student_name,
					student_surname: student_surname
				},
				success: function (result) {
					console.log('manually add student', result);
					result = JSON.parse(result);
					console.log('manually add student json:', result);

					if (result['success']) {
						$('.add_student_popup button').text('Успешно добавлено');
						$('.add_student_popup input:eq(0)').val('');
						$('.add_student_popup input:eq(1)').val('');
						reloadStudentsList();
						setTimeout(function () {
							$('.add_student_popup button').text('Добавить');
						}, 1000)
					}
				}
			})
		})

		$(document).on('click', '.reports_list .report .info', function () {
			console.log(1)
			if ($(this).parents('.report').hasClass('show_details')) {
				$(this).parents('.report').removeClass('show_details')
			} else {
				$(this).parents('.report').addClass('show_details')
			}
		})

		$(document).on('click', '.reports_list .report .copy_link', function () {
			text = $(this).attr('link');
			var $tmp = $("<textarea>");
		    $("body").append($tmp);
		    $tmp.val(text).select();
		    document.execCommand("copy");
		    $tmp.blur().remove();
		})

		select_mobile_footer_tab('head_student');	
	</script>
</body>
</html>