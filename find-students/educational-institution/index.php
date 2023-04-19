<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=7';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Учебное учреждение и группа</title>
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
		<div class="screen settings_screen hidden">
			<div style="margin-bottom: 19px;" class="description">
				Чтобы вступить в группу тебе надо пошагово указать некоторые данные
			</div>
			<div class="select_element select_city">
				<label>Выбор города</label>
				<div class="shortInfo">
					<img draggable="false" class="" src="https://findcreek.com/assets/img/icons/help.svg">
					<p>Выберите город, в котором находится ваше учебное заведение</p>
				</div>

				<div class="elements_list">
					
				</div>
			</div>

			<div class="select_element select_education_institution">
				<label>Выбор учебного заведения</label>
				<div class="shortInfo">
					<img draggable="false" class="" src="https://findcreek.com/assets/img/icons/help.svg">
					<p>Выбери учебное заведение, в котором ты учишься</p>
				</div>

				<div class="elements_list">
					
				</div>
			</div>

			<div class="select_element select_faculty">
				<label>Выбор факультета</label>
				<div class="shortInfo">
					<img draggable="false" class="" src="https://findcreek.com/assets/img/icons/help.svg">
					<p>Выбери факультет, на котором ты учишься</p>
				</div>

				<div class="elements_list">
					
				</div>
			</div>


			<div class="select_element select_specialization">
				<label>Выбор специальности</label>
				<div class="shortInfo">
					<img draggable="false" class="" src="https://findcreek.com/assets/img/icons/help.svg">
					<p>Выбери специальность, на которой ты учишься</p>
				</div>

				<div class="elements_list">
					
				</div>
			</div>

			<div class="select_element select_group">
				<label>Выбор группы</label>
				<div class="shortInfo">
					<img draggable="false" class="" src="https://findcreek.com/assets/img/icons/help.svg">
					<p>Выбери групп, в которой ты учишься</p>
				</div>

				<div class="elements_list groups_list">
					
				</div>
			</div>
		</div>

		<div class="screen groups_screen hidden">
			<br>
		</div>
	</main>


	<script type="text/javascript">
		function setSettingsScreen () {
			
		}

		function loadUserData () {
			$.ajax({
				url: "<?= $link ?>/api/getUserData.php",
				cache: false,
				type: "POST",
				data: {
					type: "get-education-data",
					token: '<?= $user_token ?>'
				},
				success: function (result) {
					console.log('Группа пользователя', result)
					result = JSON.parse(result);
					console.log('Группа пользователя json:', result)

					if (result['response']['group_id'] != null) {
						$.ajax({
							url: "<?= $link ?>/api/getGroupData.php",
							cache: false,
							type: "POST",
							data: {
								type: "get-short-info",
								token: '<?= $user_token ?>',
								get_html: true,
								group_id: result['response']['group_id']
							},
							success: function (result) {
								console.log('информация о группе', result)
								if (result != '') {
									result = JSON.parse(result);
									console.log('информация о группе json:', result);

									$('.groups_screen').prepend(result['response']['html'])
									$('.loading_screen').addClass('deleted');
									$('.groups_screen').removeClass('hidden');
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
								console.log('получение запросов на вступление в группу', result)
								if (result != '') {
									result = JSON.parse(result);
									console.log('получение запросов на вступление в группу json:', result)

									if (result['response']['groups'].length != 0) {
										for (index in result['response']['groups']) {
											group_id = result['response']['groups'][index];
											$.ajax({
												url: "<?= $link ?>/api/getGroupData.php",
												cache: false,
												type: "POST",
												data: {
													type: "get-short-info",
													token: '<?= $user_token ?>',
													get_html: true,
													group_id: group_id
												},
												success: function (result) {
													console.log('получение информации о группе', result)
													if (result != '') {
														result = JSON.parse(result);
														console.log('получение информации о группе', result)
														$('.description').remove();
														$('.groups_screen').prepend(result['response']['html'] + '<div class="description">Староста должен принять твою заявку на вступление в группу</div>');
														$('.loading_screen').addClass('deleted');
														$('.groups_screen').removeClass('hidden');
													}
												}
											})
										}
									} else {
										// setSettingsScreen();
										getCitiesList();
									}
								}
							}
						})
					}
				}
			})

			
		}
		loadUserData();

		function getCitiesList () {
			$.ajax({
				url: "<?= $link ?>/api/getEducationData.php",
				cache: false,
				type: "POST",
				data: {
					type: "get-cities-list",
					html: true,
					token: "<?= $user_token ?>",
					country_id: 1 //Пока одна страна
				},
				success: function (result) {
					console.log('Получение списка городов', result)
					if (result != '') {
						result = JSON.parse(result);
						console.log('Получение списка городов', result)

						for (index in result) {
							cityData = result[index];
							$('.select_city .elements_list').append(cityData['html']);	
						}

						$('.have_error').remove();
						$('.select_city .elements_list').append('<div class="have_error"><div class="text">Твоего города нет в списке?</div><a href="<?= $link ?>/support"><button class="button-1">Узнать почему</button></a></div>');
						$('.have_error').fadeIn();	

						$('.loading_screen').addClass('deleted');
						$('.settings_screen').removeClass('hidden');
					}
				}
			})
		}

		function getEducationInstitutionList () {
			city_id = $('.select_city .element.selected').attr('city_id');
			let i = 0;
			$('.have_error').fadeOut();	
			removeCities = setInterval(() => {
				$('.select_city .element:eq(' + i + ')').css({'transform': 'translateX(100vw)', 'opacity': '0'})
				i++;
				// console.log('delete', i-5)
				if ($('.select_city .element').length < (i-4) || i > 9) {
					$('.select_city .element').remove()
					// console.log('last', i)		
					$.ajax({
						url: "<?= $link ?>/api/getEducationData.php",
						cache: false,
						type: "POST",
						data: {
							type: "get-education-institution-list",
							html: true,
							token: "<?= $user_token ?>",
							city_id: city_id
						},
						success: function (result) {
							console.log('Получение списка учебных заведений', result)
							if (result != '') {
								result = JSON.parse(result);
								console.log('Получение списка учебных заведений', result)

								let count_objects = 0;
								for (index in result) {
									cityData = result[index];
									$('.select_education_institution .elements_list').append(cityData['html']);	
									count_objects++;
								}

								$('.select_city').css({'display': 'none'});
								$('.select_education_institution').css({'display': 'inline'});

								let i = 0;
								addEducationInstitutions = setInterval(() => {
									console.log('circle', i)
									$('.select_education_institution .element:eq(' + i + ')').css({'transform': 'translateX(0)', 'opacity': '1'})
									i++;
									if (i > count_objects) {
										console.log('clear', i)
										$('.have_error').remove();
										$('.select_education_institution .elements_list').append('<div class="have_error "><div class="text">Твоего учебного заведения нет в списке?</div><a href="<?= $link ?>/support"><button class="button-1">Узнай почему</button></a></div>');
									$('.have_error').fadeIn();
										clearInterval(addEducationInstitutions);
									}
								}, 50)

								
							}
						}
					})
					// console.log('clear', i)
					clearInterval(removeCities)
				}
			}, 50)
		}

		function getFacultiesList () {
			education_institution_id = $('.select_education_institution .element.selected').attr('education_institution_id');

			let i = 0;
			$('.have_error').fadeOut();
			removeEducationInstitutions = setInterval(() => {
				$('.select_education_institution .element:eq(' + i + ')').css({'transform': 'translateX(100vw)', 'opacity': '0'})
				i++;
				// console.log('delete', i-5)
				if ($('.select_education_institution .element').length < (i-4) || i > 9) {
					$('.select_education_institution .element').remove()
					$.ajax({
						url: "<?= $link ?>/api/getEducationData.php",
						cache: false,
						type: "POST",
						data: {
							type: "get-faculties-list",
							html: true,
							token: "<?= $user_token ?>",
							education_institution_id: education_institution_id
						},
						success: function (result) {
							console.log('Получение списка факультетов', result)
							if (result != '') {
								result = JSON.parse(result);
								console.log('Получение списка факультетов', result)

								let count_objects = 0;
								for (index in result) {
									cityData = result[index];
									$('.select_faculty .elements_list').append(cityData['html']);
									count_objects++;	
								}

								$('.select_education_institution').css({'display': 'none'});
								$('.select_faculty').css({'display': 'inline'});

								let i = 0;
								addFaculties = setInterval(() => {
									console.log('circle', i)
									$('.select_faculty .element:eq(' + i + ')').css({'transform': 'translateX(0)', 'opacity': '1'})
									i++;
									if (i > count_objects) {
										console.log('clear', i)
										$('.have_error').remove();
										$('.select_faculty .elements_list').append('<div class="have_error "><div class="text">Твоего факультета нет в списке?</div><a href="<?= $link ?>/support"><button class="button-3">Пожалуйста, напиши нам об этом</button></a></div>');
										$('.have_error').fadeIn();
										clearInterval(addFaculties);
									}
								}, 50)
							}
						}
					})
					clearInterval(removeEducationInstitutions)
				}
			}, 50)


			
		}

		function getSpecializationsList () {
			faculty_id = $('.select_faculty .element.selected').attr('faculty_id');

			let i = 0;
			$('.have_error').fadeOut();	
			removeFaculties = setInterval(() => {
				$('.select_faculty .element:eq(' + i + ')').css({'transform': 'translateX(100vw)', 'opacity': '0'})
				// $('.select_faculty .element:eq(' + (i-5) + ')').remove()
				i++;
				// console.log('delete', i-5)
				if ($('.select_faculty .element').length < (i-4) || i > 9) {
					$('.select_faculty .element').remove()
					$.ajax({
						url: "<?= $link ?>/api/getEducationData.php",
						cache: false,
						type: "POST",
						data: {
							type: "get-specializations-list",
							html: true,
							token: "<?= $user_token ?>",
							faculty_id: faculty_id
						},
						success: function (result) {
							console.log('Получение списка специальностей', result)
							if (result != '') {
								result = JSON.parse(result);
								console.log('Получение списка специальностей', result)

								let count_objects = 0;
								for (index in result) {
									cityData = result[index];
									$('.select_specialization .elements_list').append(cityData['html']);
									count_objects++;	
								}

								$('.select_faculty').css({'display': 'none'});
								$('.select_specialization').css({'display': 'inline'});

								let i = 0;
								addSpecializations = setInterval(() => {
									console.log('circle', i)
									$('.select_specialization .element:eq(' + i + ')').css({'transform': 'translateX(0)', 'opacity': '1'})
									i++;
									if (i > count_objects) {
										console.log('clear', i)
										$('.have_error').remove();
										$('.select_specialization .elements_list').append('<div class="have_error "><div class="text">Твоей специальности нет в списке?</div><a href="<?= $link ?>/support"><button class="button-3">Пожалуйста, напиши нам об этом</button></a></div>');
										$('.have_error').fadeIn();
										clearInterval(addSpecializations);
									}
								}, 50)
							}
						}
					})
					clearInterval(removeFaculties)
				}
			}, 50)

			
		}

		function getGroupsList () {
			specialization_id = $('.select_specialization .element.selected').attr('specialization_id');

			let i = 0;
			$('.have_error').fadeOut();	
			removeSpecializations = setInterval(() => {
				$('.select_specialization .element:eq(' + i + ')').css({'transform': 'translateX(100vw)', 'opacity': '0'})
				// $('.select_faculty .element:eq(' + (i-5) + ')').remove()
				i++;
				// console.log('delete', i-5)
				if ($('.select_specialization .element').length < (i-4) || i > 9) {
					$('.select_specialization .element').remove()

					$.ajax({
						url: "<?= $link ?>/api/getEducationData.php",
						cache: false,
						type: "POST",
						data: {
							type: "get-groups-list",
							html: true,
							token: "<?= $user_token ?>",
							specialization_id: specialization_id
						},
						success: function (result) {
							console.log('Получение списка групп', result)
							if (result != '') {
								result = JSON.parse(result);
								console.log('Получение списка групп', result)

								let count_objects = 0;
								for (index in result) {
									cityData = result[index];
									$('.select_group .elements_list').append(cityData['html']);	
									count_objects++;
								}

								$('.select_specialization').css({'display': 'none'});
								$('.select_group').css({'display': 'inline'});

								let i = 0;
								addGroups = setInterval(() => {
									console.log('circle', i)
									$('.select_group .group:eq(' + i + ')').css({'transform': 'translateX(0)', 'opacity': '1'})
									i++;
									if (i > count_objects) {
										console.log('clear', i)
										$('.have_error').remove();
										$('.select_group .elements_list').append('<div class="have_error "><div class="text">Твоей группы нет в списке?</div><button class="button-3">Создай её!</button></div>');
										$('.have_error').fadeIn();
										clearInterval(addGroups);
									}
								}, 50)
							}
						}
					})
					clearInterval(removeSpecializations)
				}

			}, 50)
			
		}

		// Клик на выбранный город
		$(document).on('click', '.select_city .element button', function () {
			if ($(this).hasClass('button-3')) {
				$('.select_city .element button').removeClass('button-3').addClass('button-3-off');
				$(this).addClass('loading').text('');
				$(this).parents('.element').addClass('selected')
				getEducationInstitutionList();
			}
		})

		// Клик на выбранное учебное заведение
		$(document).on('click', '.select_education_institution .element button', function () {
			if ($(this).hasClass('button-3')) {
				$('.select_education_institution .element button').removeClass('button-3').addClass('button-3-off');
				$(this).addClass('loading').text('');
				$(this).parents('.element').addClass('selected')

				if ($(this).parents('.element').attr('status') == 'hei') {
					getFacultiesList();
				} 
				else if ($(this).parents('.element').attr('status') == 'sei') {
					getSpecializationsList();
				} 

				
			}
		})


		// Клик на выбранный факультет
		$(document).on('click', '.select_faculty .element button', function () {
			if ($(this).hasClass('button-3')) {
				$('.select_faculty .element button').removeClass('button-3').addClass('button-3-off');
				$(this).addClass('loading').text('');
				$(this).parents('.element').addClass('selected')

				getSpecializationsList(); 
			}
		})


		// Клик на выбранную специальность
		$(document).on('click', '.select_specialization .element button', function () {
			if ($(this).hasClass('button-3')) {
				$('.select_specialization .element button').removeClass('button-3').addClass('button-3-off');
				$(this).addClass('loading').text('');
				$(this).parents('.element').addClass('selected')

				getGroupsList(); 
			}
		})


		$('.select_group input').on('change', function () {
			$('.button-5').removeClass('button-5').addClass('button-3');
		})
		$(document).on('click', '.select_group	li', function () {
			$('.button-5').removeClass('button-5').addClass('button-3');
		})

		$(document).on('click', '.settings_screen .group .button-2', function () {
			group_id = $(this).parents('.group').attr('id').replace('group_', '');
			$(this).text('Загрузка').removeClass('button-2').addClass('button-5');
			console.log(group_id);
			$('.select_group').removeClass('disabled_drop_down_menu');
			$.ajax({
				url: "<?= $link ?>/api/getEducationData.php",
				cache: false,
				type: "POST",
				data: {
					type: "save",
					token: '<?= $user_token ?>',
					group_id: group_id
				},
				success: function (result) {
					console.log('выбор группы', result)
					result = JSON.parse(result);
					console.log('выбор группы json:', result)
					if (result['success']) {
						$('.settings_screen').addClass('deleted');
						$('.groups_screen').removeClass('hidden').removeClass('deleted');
						loadUserData();
					}
					if (result['response'] == 'user is new head student') {
						location.href = "<?= $link ?>/group";
					}
				}
			})
		})

		$(document).on('click', '.groups_screen .group button', function () {
			group_id = Number($(this).parents('.group').attr('id').replace('group_', ''));
			// console.log(group_id)
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
					console.log('Выход из группы', result)
					result = JSON.parse(result)
					console.log('Выход из группы json:', result)
					if (result['success']) {
						$('.groups_screen').addClass('deleted');
						$('.settings_screen').removeClass('deleted').removeClass('hidden');
						$('.settings_screen button').text('Подать заявку на вступление')
						$('#group_' + group_id).remove();
						setSettingsScreen()
						getCitiesList();
					}
				}
			})
		})



	</script>

	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('settings');	
	</script>
</body>
</html>