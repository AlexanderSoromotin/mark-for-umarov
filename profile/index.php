<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';
	include_once '../inc/redirect.php';
	
	if ($_GET['id'] == '' and $user_token == '') {
		redirect('unlogged',' /authorization');
	} 
	else if ($_GET['id'] == '') {
		header("Location: " . $link . "/profile/?id=" . $user_id);
	}

	redirect('Banned', '/banned');
	redirect('pre-deleted', '/pre-deleted');

	include_once '../inc/head.php';	

	// Получение данных о пользователе
	$local_id = $_GET['id'];
	$result = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_id'");
	$local_user_data = mysqli_fetch_assoc($result);
?>

<html>
<head>
	<meta charset="utf-8">
	<title><?= $local_user_data['first_name'] . ' ' . $local_user_data['last_name'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?
		include_once '../inc/header.php'; // Шапка
		include_once '../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?=$link?>/">Главная</a>
			<img draggable="false" src="<?= $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?=$link?>/profile">Профиль</a>
		</div>
	</div>

	<div class="profile">
	<?	

		$local_user_data['friends'] = unserialize($local_user_data['friends']);
		if ($local_user_data['friends'] == '') {
			$local_user_data['friends'] = array();
		}

		// Пользователь удалён
		if ($local_user_data['status'] == 'pre-deleted' or $local_user_data['status'] == 'deleted') {
			$local_user_data['first_name'] = 'Пользователь удалён';
			$local_user_data['last_name'] = '';
			$local_user_data['patronymic'] = '';
			$local_user_data['photo'] = 'http://frmjdg.com/assets/img/deleted-user.png';
			$local_user_data['photo_style'] = ''; 
			$local_user_data['bg_image'] = 'http://frmjdg.com/assets/img/bg_image.jpg';
			$local_user_data['bg_image_style'] = ''; 
			$local_user_data['closed_profile'] = 2; 

		}
		if ($result -> num_rows != 0) : 
	?>
	<!-- Пользователь найден -->
		<div class="image">

			<!-- Выпадающий список -->
			<div class="options drop-down-parent">
				<div class="button">
					<div></div>
					<div></div>
					<div></div>
				</div>

				<ul class="drop-down-child">
					<li> 
						<img draggable="false" src="<?=$link?>/assets/img/icons/clipboard.svg"> 
						<p>Скопировать ссылку</p>
					</li>
					<?	
						$areFriend = false;
						global $user_friends;
						foreach ($user_friends as $key => $value) {
							if ($value == $local_user_data['id']) {
								$areFriend = true;
							}
						}
					?>
					<? if ($user_id == $local_user_data['id'] or $user_status == 'Admin' and $local_user_data['status'] != 'deleted' and $local_user_data['status'] != 'pre-deleted') : ?>
						<li> 
							<img draggable="false" src="<?=$link?>/assets/img/icons/edit.svg"> 
							<p>Редактировать профиль</p>
						</li>
					<? endif; ?>

					
				</ul>
			</div>

			<!-- Фото пользователя -->
			<div class="avatar unselected">
				<img draggable="false" style="<?= unserialize($local_user_data['photo_style'])['ox_oy']?>;transform: scale(<?= unserialize($local_user_data['photo_style'])['scale']?>);" class="unselected" src="<?=$local_user_data['photo']?> ">
			</div>

			<!-- Фон профиля -->
			<div class="bg">
				<img draggable="false" style="<?= $local_user_data['bg_image_style']?>" class="unselected" src="<?=$local_user_data['bg_image']?>">
			</div>
			
			<!-- 1 Блок с информацией -->
			<div class="info-1">
				<div class="col-1">
					<?
						$fullname_1 = $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . ' ' . $local_user_data['patronymic'];
						$fullname_2 = $local_user_data['last_name'] . ' ' . $local_user_data['first_name'];

					?>
					<h2><?= $fullname_2 ?></h2>
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
		
						$ending;
						if ($local_user_data['sex'] == 'Женский') {
							$ending = 'а';
						}
						// function deleteZeroes ($text) {
						// 	if ($text[0] == '0') {
						// 		return $text[1];
						// 	}
						// 	return $text;
						// }
						// function addZeroes ($text) {
						// 	if (strlen($text) == 1) {
						// 		return '0' . $text;
						// 	}
						// 	return $text;
						// }

						// Разница между часовыми поясами сервера и пользователя
						$timezone = $_SESSION['user_timezone'] - $_SESSION['server_timezone'];

						// Дата последнего посещения по часовому поясу сервера
						$server_last_online_year = (int) mb_substr($local_user_data['last_online'], 0, 4);
						$server_last_online_month = (int) mb_substr($local_user_data['last_online'], 5, 2);
						$server_last_online_day = (int) mb_substr($local_user_data['last_online'], 8, 2);

						$server_last_online_hour = (int) mb_substr($local_user_data['last_online'], 11, 2);
						$server_last_online_minute = (int) mb_substr($local_user_data['last_online'], 14, 2);

						$server_minutes = $server_last_online_hour * 60 + $server_last_online_minute + $timezone;

						// Дата последнего посещения по часовому поясу клиента
						$client_last_online_year = $server_last_online_year;
						$client_last_online_month = $server_last_online_month;
						$client_last_online_day = $server_last_online_day;
						$client_last_online_minutes = $server_minutes;

						// Если в на сервере и у клиента разные дни, то высчитываем последнее время посещения от лица клиента
						if ($client_last_online_minutes >= 1440) {

							$client_last_online_day++;
							$client_last_online_minutes -= 1440;

							if (cal_days_in_month(CAL_GREGORIAN, $server_last_online_month, $server_last_online_year) < $client_last_online_day) {
								$client_last_online_month++;
								$client_last_online_day = 1;

								if ($client_last_online_month > 12) {
									$client_last_online_year++;
									$client_last_online_month = 1;
								}
							}
						}

						// Текущая дата по часовому поясу сервера
						$server_current_year = (int) date('Y');
						$server_current_month = (int) date('m');
						$server_current_day = (int) date('d');

						$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

						// Текущая дата по часовому поясу клиента
						$client_current_year = $server_current_year;
						$client_current_month = $server_current_month;
						$client_current_day = $server_current_day;

						$client_current_minutes = $server_current_minutes;

						// Если в один момент на сервере и у клиенка разные дни, то высчитываем настоящее время у клиента
						if ($client_current_minutes >= 1440) {

							$client_current_day++;
							$client_current_minutes -= 1440;

							if (cal_days_in_month(CAL_GREGORIAN, $server_current_month, $server_current_year) < $client_current_day) {
								$client_current_month++;
								$client_current_day = 1;

								if ($client_current_month > 12) {
									$client_current_year++;
									$client_current_month = 1;
								}
							}
						}

						
						
						function calcLastOnlineTime () {
							// Имеем client_last_online - время последнего посещения по часовому поясу клиента
							global $client_last_online_year;
							global $client_last_online_month;
							global $client_last_online_day;
							global $client_last_online_minutes;

							// Имеем client_current - время на данный момент по часовому поясу клиента
							global $client_current_year;
							global $client_current_month;
							global $client_current_day;
							global $client_current_minutes;

							global $months_accusative;

							if ($client_current_year != $client_last_online_year) {
								$hour = intdiv($client_last_online_minutes, 60);
								$minute = $client_last_online_minutes - $hour * 60;
								return 'Был' . $ending . ' в сети ' . addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
							}

							if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
								return 'Онлайн';

							}

							if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
								return 'Был' . $ending . ' в сети ' . caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

							}
							if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
								$hour = intdiv($client_last_online_minutes, 60);
								$minute = $client_last_online_minutes - $hour * 60;
								return 'Был' . $ending . ' в сети вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
							}
							if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
								$hour = intdiv($client_last_online_minutes, 60);
								$minute = $client_last_online_minutes - $hour * 60;
								return 'Был' . $ending . ' в сети сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
							}
							if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
								$hour = intdiv($client_last_online_minutes, 60);
								$minute = $client_last_online_minutes - $hour * 60;
								return 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
							}
							if ( $client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
								$hour = intdiv($client_last_online_minutes, 60);
								$minute = $client_last_online_minutes - $hour * 60;
								return 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
							}
						}
						


						// calcLastOnlineTime()
					?>
					<h5><?= calcLastOnlineTime() ?></h5>
				</div>

				<!-- Статус пользователя -->
				<? if ($local_user_data['status'] == 'Admin') : ?>
					<div class="col-2">
						<h4 class="unselected">Администратор</h4>
					</div>
				<? endif; ?>
				<? if ($local_user_data['status'] == 'Banned') : ?>
					<div class="col-2">
						<h4 class="unselected">Пользователь заблокирован</h4>
					</div>
				<? endif; ?>

			</div>

			<!-- Профиль пользователя закрыт -->
			<? if ($local_user_data['closed_profile'] == 2) : ?>
				<div class="info-2">
					<p class="closed-profile-error">
						Пользователь удалил свой аккаунт
					</p>
				</div>

			<? elseif ($local_user_data['closed_profile'] == 1 and $user_status == "User" and $local_user_data['id'] != $user_id) : ?>
				<div class="info-2">
					<p class="closed-profile-error">
						Пользователь скрыл свой профиль
					</p>
				</div>
			<? else : ?>

				<!-- Профиль пользователя открыт -->
				<div class="info-2">
					<a href="<?= $link ?>/interest-groups/user/?id=<?= $local_user_data['id'] ?>">
					<div class="col-1 col">
						<h3>Тем на форуме</h3>
						<h4>
							<?
								$records_count = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `user_id` = '$local_id'  and `status` = 'standart'");

								echo $records_count -> num_rows;
							?>
							
						</h4>
					</div>
					</a>

					<div class="col-1 col">
						<h3>Зарегистрирован</h3>
						<h4><?= $months[mb_substr($local_user_data['registration_date'], 5, 2)] . ' ' . mb_substr($local_user_data['registration_date'], 0, 4) ?></h4>
					</div>
					<?
						if ($local_user_data['reputation'] == '') {
							$local_user_data['reputation'] = array();
						} else {
							$local_user_data['reputation'] = json_decode($local_user_data['reputation']);
						}
					?>
					<div class="col-1 col reputation">
						<h3>Симпатий</h3>
						<h4><?= count($local_user_data['reputation']) ?></h4>
					</div>
					<a href="<?= $link ?>/profile/friends/?id=<?= $local_user_data['id'] ?>">
						<div class="col-1 col">
							<!-- <a href="<?= $link ?>/profile/friends/?id=<?= $local_user_data['id'] ?>"> -->
								<h3>Друзей</h3>
							<!-- </a> -->

							<!-- <a href="<?= $link ?>/profile/friends/?id=<?= $local_user_data['id'] ?>"> -->
								<h4><?= count($local_user_data['friends'])?></h4>
							<!-- </a> -->
						</div>
					</a>
					<div style="border-right: 0px solid transparent;" class="col-1 col">
						<h3>Учебное заведение</h3>
						<?
							$user_uni = $local_user_data['education_id'];
							$user_uni_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_uni' "))['short_title'];
							if ($user_uni_title == '') {
								$user_uni_title = '-';
							}
						?>
						<h4><?= $user_uni_title ?></h4>
					</div>
				</div>
			<? endif; ?>
			
		</div>

		<!-- $user_id != $local_user_data['id'] and  -->
		<? if ($user_id != $local_user_data['id'] and $local_user_data['status'] != 'deleted' and $local_user_data['status'] != 'pre-deleted' and $local_user_data['status'] != 'Banned' and $user_token != '') : ?>

		<div class="additional-block">
			<a href="<?= $link ?>/messenger/?id=<?= $local_user_data['id'] ?>">
				<button class="button-3">
					Написать сообщение
				</button>
			</a>

			<? if ($user_friends != '' and $areFriend == true) : ?>
				<button class="button-1 removeFriend">
					Удалить из друзей
				</button>
			<? elseif (mysqli_query($connection, "SELECT id FROM friend_requests WHERE outgoing_id = {$user_id} and incoming_id = {$local_user_data['id']}") -> num_rows != 0 and !in_array($local_user_data['id'], $user_blacklist)) : ?>
				<button class="button-5 requestSended">
					Заявка отправлена
				</button>
			<? else : ?>
				<button class="<? if (in_array($local_user_data['id'], $user_blacklist)) { echo 'button-5';} else {echo 'button-3';} ?> addFriend">
					Добавить в друзья
				</button>
			<? endif; ?>


			<? if (in_array($local_user_data['id'], $user_blacklist)) : ?>

				<button class="button-1 unblockUser">
					Разблокировать
				</button>
			<? else : ?>
				<button class="button-1 blockUser">
					Заблокировать
				</button>
			<? endif; ?>

			<? if (in_array($user_id, $local_user_data['reputation'])) : ?>

			<button class="button-1 remove-reputation">
				Вы выразили симпатию
			</button>
			<? else : ?>
				<button class="button-1 add-reputation">
					Выразить симпатию
				</button>
			<? endif; ?>


			

		</div>
		<? endif; ?>

	<div class="interests">
		<h3>Последние интересы <b></b></h3>
		<a class="show-all-interests" href="<?= $link ?>/interest-groups/user/?id=<?= $local_user_data['id'] ?>">Показать все</a>
		<div class="list">
		</div>
	</div>
		
	<? else : ?>
		<!-- Профиль не найден -->
		<div class="profile-error">
			<img draggable="false" src="<?=$link?>/assets/img/icons/zoom-cancel.svg">
			<h2>Профиль не найден</h2>
		</div>
	<? endif; ?>
	
	</div>


	


<? if ($user_status == 'Admin' or $user_id == $local_user_data['id']) : 
	if ($local_user_data['status'] != 'pre-deleted' and $local_user_data['status'] != 'deleted') : ?>

<div class="editPanel">
	<div class="editBlock editProfile">

		<div class="header">
			<h3>Редактирование профиля</h3>
			<img draggable="false" src="<?=$link?>/assets/img/icons/x.svg">
		</div>

		<div class="content">
			<ul class="navBar">
				<li id="navBar-basicInfo" class="selected">
					<img draggable="false" src="<?=$link?>/assets/img/icons/news.svg">
					<p>Основное</p>
				</li>
				<li id="navBar-bg" class="">
					<img draggable="false" src="<?=$link?>/assets/img/icons/photo.svg">
					<p>Фон</p>
				</li>
				<li id="navBar-privacy" class="">
					<img draggable="false" src="<?=$link?>/assets/img/icons/shield-lock.svg">
					<p>Приватность</p>
				</li>
				<li id="navBar-security" class="">
					<img draggable="false" src="<?=$link?>/assets/img/icons/lock.svg">
					<p>Безопасность</p>
				</li>
				<li id="navBar-account" class="">
					<img draggable="false" src="<?=$link?>/assets/img/icons/compass.svg">
					<p>Дополнительное</p>
				</li>
			</ul>

			<div class="info info-basicInfo">
				<h3 class="title">Основная информация</h3>
				<div class="basic-info">
					<div class="imageControl">

						<div class="imageControl-photo">
							<div class="shortinfo">
								<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg" >
								<p>Вы можете изменить положение фотографии, зажав левую кнопку мыши и перетаскивая картинку. Так же с помощью скролла можно изменить размер</p>
							</div>

							<div class="imageControl-photo-img">
								<img draggable="false" style="<?= unserialize($local_user_data['photo_style'])['ox_oy']?>; transform: scale(<?= unserialize($local_user_data['photo_style'])['scale']?>);" draggable="false" src="<?=$local_user_data['photo']?>">
							</div>
						</div>

						<div class="imageControl-inputs">
							<h5>Увеличение</h5>
							<input type="range" name="" min="0.2" max="3" value="1" step="0.05">

							<!-- <div class="upload"> -->
								<input id="profile-image" type="file" name="">
								<!-- <label for="profile-image">Выберите файл</label> -->
               					<!-- <span>или перетащите его сюда</span> -->
							<!-- </div> -->
							

							
							<button class="button-2">Сбросить положение</button>
						</div>
					</div>

					<div class="fullnameControl">
						<div class="fullnameControl-row1">

							<div class="relative">
								<h4>Учебное заведение</h4>

								<span class="education_span">
									<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
									<?
										$user_uni = $local_user_data['education_id'];
										$user_uni_title = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$user_uni' "))['short_title'];
									?>
									<input autocomplete="off" type="" value="<?= $user_uni_title ?>" name="education" placeholder="Поиск">
									<ul class="universities">
										<?
											$universities = mysqli_query($connection, "SELECT * FROM `education` ORDER BY `id`");
												$universities_li = '';
												$universities_arr = '';

												while ( $u = mysqli_fetch_assoc($universities) ) {
													$universities_li = $universities_li .  "<li title='" . $u['short_title'] . "'>" . $u['title'] . " (" . $u['short_title'] . ")" . "</li>";
													$universities_arr = $universities_arr . $u['title'] . " (" . $u['short_title'] . ") $$ ";
												}
											
											echo $universities_li;
											echo '<h5>Не нашли своего? </h5>' . '<a href="' . $link . '/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a><br>';
										?>
										
									</ul>
								</span>
							</div>
							<script type="text/javascript">
								// Фокусировка на input при нажатии на картинку
								$('.fullnameControl-row1 .education_span img').click(function () {
									$('.fullnameControl-row1 .education_span input').focus();
								})

								// Показ списка УУ при нажатии на input
								$('.fullnameControl-row1 .education_span input').on('focus', function () {
									$('.fullnameControl-row1 .education_span').addClass('education-show');
								})

								// Поиск УУ
								$('.fullnameControl-row1 .education_span input').on('input keyup', function () {
									// console.log(1)
									$('.fullnameControl-row1 .education_span ul li').removeClass('hidden')
									// $('.fullnameControl-row1 .education_span ul h5').remove();
									// $('.fullnameControl-row1 .education_span ul a').remove();

									if ($(this).val() != '') {
										result = 0;
										for (li_eq = 0; li_eq <= $('.fullnameControl-row1 .education_span ul li').length; li_eq++) {
											if ( $('.fullnameControl-row1 .education_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
												$('.fullnameControl-row1 .education_span ul li:eq(' + li_eq + ')').addClass('hidden');
											} else {
												result++;
											}
										}
										if (result == 0) {
											// изменено: эти надписи добавляются изначально
											// $('.fullnameControl-row1 .education_span ul').append('<h5>Не нашли своего? </h5>');
											// $('.fullnameControl-row1 .education_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
										}
									} 
									
								})

								// Фокус на input после нажатия на УУ
								$('.fullnameControl-row1 .education_span ul, .fullnameControl-row1 .education_span ul li').click(function () {
									$('.fullnameControl-row1 .education_span input').focus();
									// $('.col-2 .education_span').addClass('education-show');
								})

								$('.fullnameControl-row1 .education_span ul li').click(function () {
									$('.fullnameControl-row1 .education_span input').val($(this).attr('title'));
									// console.log($(this).text());
								})

								$('.fullnameControl-row1 .education_span :input').blur(function() {
								    setTimeout(function () {
							    		if ($('.fullnameControl-row1 .education_span :focus').length === 0) {
							    			$('.fullnameControl-row1 .education_span').removeClass('education-show');
							    		}
								    }, 250)
								});
							</script>
							<h4>Фамилия</h4>
							<input type="" name="" value="<?=$local_user_data['last_name']?>">
							<h4>Имя</h4>
							<input type="" name="" value="<?=$local_user_data['first_name']?>">
							<!-- <h4>Отчество</h4>
							<input type="" name="" value="<?=$local_user_data['patronymic']?>"> -->

							<div class="relative">
								<h4>Город</h4>

								<span class="city_span">
									<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
									<?
										$user_city_id = $local_user_data['city_id'];
										$user_city = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$user_city_id' "));

										if ($user_city == 0 or $user_city == '') {
											$user_city_title = '';
										} else {
											$user_country_id = $user_city['country_id'];
											$user_country = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `countries` WHERE `id` = '$user_country_id' "));
											$user_city_title = $user_city['rus_title'] . ' (' . $user_country['rus_title'] . ')';
										}

										
									?>
									<input autocomplete="disabledFunction" autocomplete="disabled" autocomplete="off" type="" value="<?= $user_city_title ?>" name="city" placeholder="Поиск">
									<ul class="cities">
										<?
											$cities = mysqli_query($connection, "SELECT * FROM `cities` ORDER BY `id`");
												$cities_li = '';
												$cities_arr = '';

												while ( $c = mysqli_fetch_assoc($cities) ) {
													$country_id = $c['country_id'];
													$country = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `countries` WHERE `id` = '$country_id' "));
													$cities_li = $cities_li .  "<li title='" . $c['rus_title'] . " (" . $country['rus_title'] . ")'>" . $c['rus_title'] . " (" . $country['rus_title'] . ")" . "</li>";
													$cities_arr = $universities_arr . $c['rus_title'] . " (" . $country['rus_title'] . ") $$ ";
												}
											
											echo $cities_li;
											// echo '<h5>Не нашли своего? </h5>' . '<a href="' . $link . '/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a><br>';
										?>
										
									</ul>
								</span>
							</div>
							<script type="text/javascript">
								// Фокусировка на input при нажатии на картинку
								$('.fullnameControl-row1 .city_span img').click(function () {
									$('.fullnameControl-row1 .city_span input').focus();
								})

								// Показ списка УУ при нажатии на input
								$('.fullnameControl-row1 .city_span input').on('focus', function () {
									$('.fullnameControl-row1 .city_span').addClass('education-show');
								})

								// Поиск УУ
								$('.fullnameControl-row1 .city_span input').on('input keyup', function () {
									// console.log(1)
									$('.fullnameControl-row1 .city_span ul li').removeClass('hidden')
									// $('.fullnameControl-row1 .city_span ul h5').remove();
									// $('.fullnameControl-row1 .city_span ul a').remove();

									if ($(this).val() != '') {
										result = 0;
										for (li_eq = 0; li_eq <= $('.fullnameControl-row1 .city_span ul li').length; li_eq++) {
											if ( $('.fullnameControl-row1 .city_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
												$('.fullnameControl-row1 .city_span ul li:eq(' + li_eq + ')').addClass('hidden');
											} else {
												result++;
											}
										}
										if (result == 0) {
											// изменено: эти надписи добавляются изначально
											// $('.fullnameControl-row1 .city_span ul').append('<h5>Не нашли своего? </h5>');
											// $('.fullnameControl-row1 .city_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
										}
									} 
									
								})

								// Фокус на input после нажатия на УУ
								$('.fullnameControl-row1 .city_span ul, .fullnameControl-row1 .city_span ul li').click(function () {
									$('.fullnameControl-row1 .city_span input').focus();
									// $('.col-2 .city_span').addClass('education-show');
								})

								$('.fullnameControl-row1 .city_span ul li').click(function () {
									$('.fullnameControl-row1 .city_span input').val($(this).attr('title'));
									// console.log($(this).text());
								})

								$('.fullnameControl-row1 .city_span :input').blur(function() {
								    setTimeout(function () {
							    		if ($('.fullnameControl-row1 .city_span :focus').length === 0) {
							    			$('.fullnameControl-row1 .city_span').removeClass('education-show');
							    		}
								    }, 250)
								});
							</script>

							<h4>Электронная почта</h4>
							<input class="readonly" readonly name="" value="<?=$local_user_data['email']?>">
							
							<div class="fullnameControl-row2">
								<p></p>
								<button class="button-1 fullnameControl-save">Сохранить изменения</button>
							</div>
						</div>
						
						
							<!-- <p></p>
							<button class="button-1">Сохранить изменения</button> -->
						
					</div>
				</div>
			</div>

			<div class="info-hiden info info-bg">
				<h3 class="title">Задний фон</h3>
				<div class="shortInfo">
					<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
					<p>Вы можете изменить положение фотографии, зажав левую кнопку мыши и перетаскивая картинку. Изменить увеличение или сдвинуть по горизонтали не получится.</p>
				</div>
				<div class="bgControl">
					<div class="bgControl-image">
						<!-- Нет обновления последнего положения -->
						<!-- Всегда центрируется -->
						<img  draggable="false" src="<?= $local_user_data['bg_image'] ?>">
					</div>

					<div class="bgControl-buttons">
						<input type="file" name="">
						<button class="button-3">Сбросить изменения</button>
						<p></p>
						<button class="button-1">Сохранить изменения</button>
					</div>
				</div>
			</div>

			<div class="info-hiden info info-privacy">
				<h3 class="title">Приватность</h3>
				<div class="privacyControl">
					<div class="privacyControl-col1">

						<h4>Доступность профиля</h4>
						<select class="closed-profile">

							<? if ($local_user_data['closed_profile'] == 1) : ?>
								<option>Закрытый</option>
								<option>Открытый</option>
							<? else : ?>
								<option>Открытый</option>
								<option>Закрытый</option>
							<? endif; ?>
						</select>
						
						<div class="shortInfo">
							<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
							<p>
								Открытый профиль: Отображается вся инфорамция.
								<br><br>
								Закрытый профиль: Отображается только задний фон, фото пользователя и последнее посещение.
							</p>
						</div>
					</div>
					<div class="privacyControl-col2">
						<h4 style="font-size: 14px;">Кто может присылать сообщения</h4>
						<select class="privacy-messages">

							<? if ($local_user_data['privacy_messages'] == 0) : ?>
								<option>Никто</option>
								<option>Только друзья</option>
								<option>Все</option>
							<? elseif ($local_user_data['privacy_messages'] == 1) : ?>
								<option>Только друзья</option>
								<option>Все</option>
								<option>Никто</option>
							<? else : ?>
								<option>Все</option>
								<option>Только	друзья</option>
								<option>Никто</option>
							<? endif; ?>
						</select>
					</div>

					<div class="privacyControl-saveButton">
						<p></p>
						<button class="button-1">Сохранить изменения</button>
					</div>
				</div>
			</div>

			<div class="info-hiden info info-security">
				<h3 class="title">Безопасность</h3>
				<div class="securityControl">
					<div class="securityControl-col1">
						
						

						<button class="logout button-1">Выйти со всех устройств</button>
					</div>

					<div class="securityControl-col2">
						<!-- <h4>Смена пароля</h4>
						<input class="readonly" readonly type="" name="" placeholder="Старый пароль" value="">
						<input class="readonly" readonly type="" name="" placeholder="Новый пароль" value="">
						<input class="readonly" readonly type="" name="" placeholder="Подтвердите пароль" value=""> -->
						<button class="logout button-5">Изменить пароль</button>
					</div>

					<div class="securityControl-activity">
						
					</div>

					<div class="securityControl-saveButton">
						<p></p>
						<button class="button-1">Сохранить изменения</button>
					</div>
				</div>
			</div>

			<div class="info-hiden info info-account">
				<h3 class="title">Дополнительное</h3>

				<div class="accountControl">
					<div class="row-1">
						<div class="col-1">
							<a href="<?= $link ?>/projects/hi-icue/connect"><button class="button-3">Подключить Hi ICUE</button></a>
						</div>
						<div class="col-2">
							<button class="button-5">sercet</button>
						</div>
					</div>
					<a href="<?= $link ?>/delete-account"><button class="deleteAccount button-1">Перейти к удалению аккаунта</button></a>
				</div>
			</div>

		</div>
	</div>
</div>

<? endif; endif; ?>

	<script type="text/javascript">
		drop_down_childs = $('.drop-down-child li').length;
		$('.drop-down-child').addClass('drop-down-child-height-' + drop_down_childs);

		function getLastOnline () {
			users = [<?= $local_user_data['id'] ?>];

			users = JSON.stringify(users);
			// console.log(users)
			$.ajax({
				url: '<?= $link ?>/inc/online.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'get-online',
					users: users
				},
				success: function (result) {
					users = JSON.parse(result);
					users_length = Object.keys(users).length;

					for (user_id in users) {
						// console.log(users[user_id])
						if ($('.profile .info-1 h5').text() != users[user_id]) {
							$('.profile .info-1 h5').text(users[user_id]);
						}
					}
				}
			})
		}
		setInterval(() => getLastOnline(), 5000);


	<? if (($user_status == 'Admin' or $user_id == $local_user_data['id']) and $user_token != '') : ?>

		// Предупреждение: основная информация не сохранена!
		$('.editProfile .info-basicInfo .basic-info .fullnameControl input, .editProfile .info-basicInfo .basic-info .imageControl input').on('input keyup', function () {
			$('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-3');
			$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('Изменения не сохранены!');
		})
		$('.editProfile .info-basicInfo .basic-info .imageControl button, .editProfile .info-basicInfo .basic-info .fullnameControl span ul li ').click(function () {
			$('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-3');
			$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('Изменения не сохранены!');
		})

		// Предупреждение: настройки приватности не сохранены!
		$('.editProfile .info-privacy select').on('input keyup', function () {
			$('.editProfile .privacyControl-saveButton button').removeClass().addClass('button-3');
			$('.editProfile .privacyControl-saveButton p').text('Изменения не сохранены!');
		})

		// Предупреждение: настройки безопасности не сохранены!
		$('.editProfile .info-security select').on('input keyup', function () {
			$('.editProfile .securityControl-saveButton button').removeClass().addClass('button-3');
			$('.editProfile .securityControl-saveButton p').text('Изменения не сохранены!');
		})

		var photo_imageX = 0;
		var photo_imageY = 0;
		// Сдвиг фото пользователя
		$('.editProfile .basic-info .imageControl-photo-img').mousedown(function(event){

			var photo_imageX = Number($('.editProfile .basic-info .imageControl-photo-img img:eq(0)').css('left').replace('px', '').replace('%', ''));
			var photo_imageY =  Number($('.editProfile .basic-info .imageControl-photo-img img:eq(0)').css('top').replace('px', '').replace('%', ''));

			var photo_stockMouseX = (event.pageX - $(this).offset().left);
			var photo_stockMouseY = (event.pageY - $(this).offset().top);

		     $(this).on('mousemove',function(event){
		        	var currentMouseX = (event.pageX - $(this).offset().left);
					var currentMouseY = (event.pageY - $(this).offset().top);

					
					photo_marginLeft = photo_imageX + (currentMouseX - photo_stockMouseX);
					photo_marginTop = (photo_imageY + (currentMouseY - photo_stockMouseY));

					photo_marginLeft = photo_marginLeft / 250 * 100;
					photo_marginTop = photo_marginTop / 250 * 100;

					$('.editProfile .basic-info .imageControl-photo-img img:eq(0)').css({'left' : photo_marginLeft + '%', 'top' : photo_marginTop + '%'});

					// Предупреждение: основная информация не сохранена!
					$('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-3');
					$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('Изменения не сохранены!');
		      })
		 }).mouseup(function(){
		    $(this).off('mousemove');
		})


		// обновляем устаревшие данные (размер изображения пользователя)
		var scroll = Number('<?= unserialize($local_user_data['photo_style'])['scale']?>');
		if (scroll == '') {
			var scroll = 1;
		}


		// Задаём изначальный скролл, чтобы сразу отобразить настройки пользователя
		$('.editProfile .imageControl .imageControl-inputs input:eq(0)').val(scroll)
		

		// Скролл размера фото пользователя
		$('.editProfile .basic-info .imageControl-photo').bind('mousewheel', function(e) {
			if (e.originalEvent.wheelDelta / 120 > 0 && scroll <= 3) {
        	    scroll += 0.05;
        	    $('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-3');
				$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('Изменения не сохранены!');
        	}
        	if (e.originalEvent.wheelDelta / 120 <= 0 && scroll >= 0.2) {
        	    scroll -= 0.05;	
        	    $('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-3');
				$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('Изменения не сохранены!');
        	}
       		$('.editProfile .basic-info .imageControl-photo-img img:eq(0)').css({'transform' : 'scale(' + scroll + ')'});
       		$('.editProfile .imageControl-inputs input:eq(0)').val(scroll);
       		// console.log(scroll)
		})

		$('.editProfile .imageControl-inputs input:eq(0)').on('input keyup', function() {
       		$('.editProfile .basic-info .imageControl-photo-img img:eq(0)').css({'transform' : 'scale(' + $(this).val() + ')'});
       		scroll = Number($(this).val());
       		// console.log(scroll)
       		
		})


		// Сброс изменения положения фото пользователя
		$('.editProfile .imageControl-inputs button').click(function () {
			$('.editProfile .imageControl-photo-img img:eq(0)').css({'left' : '0%', 'top' : '0%', 'transform' : 'scale(1)'});
			$('.editProfile .imageControl-inputs input:eq(0)').val(1);
			scroll = 1;
		})


		// Сброс изменения положения заднего фото пользователя
		$('.editProfile .bgControl-buttons button:eq(0)').click(function () {
			$('.editProfile .bgControl-image img:eq(0)').css({'top' : '0%'});
		})


		// Сдвиг картинки заднего фона
		var bg_imageY = 0;
		$('.editProfile .info-bg .bgControl-image').mousedown(function(event){

			var bg_imageY =  Number($('.editProfile .info-bg .bgControl-image img:eq(0)').css('top').replace('px', '').replace('%', ''));

			var bg_stockMouseY = (event.pageY - $(this).offset().top);

		     $(this).on('mousemove',function(event){
					var currentMouseY = (event.pageY - $(this).offset().top);

					bg_marginTop = (bg_imageY + (currentMouseY - bg_stockMouseY));

					bg_marginTop = bg_marginTop / 211 * 100;

					// console.log(bg_marginTop)
					
					// console.log((bg_imageY + (currentMouseY - bg_stockMouseY)) / 111 * 100)

					$('.editProfile .info-bg .bgControl-image img:eq(0)').css({'top' : bg_marginTop + '%'});
					$('.editProfile .info-bg .bgControl-buttons button').removeClass().addClass('button-3');
					$('.editProfile .info-bg .bgControl-buttons p').text('Изменения не сохранены!');
		      })
		 }).mouseup(function(){
		    $(this).off('mousemove');
		})






		// изменение фотки пользователя в профиле
		$('.editProfile .info-basicInfo .imageControl .imageControl-inputs input:eq(1)').on('input keyup', function () {
			fileName = $(this).val().split('/').pop().split('\\').pop();
			if (fileName != '') {
				$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Не сохранено!');



				arr = fileName.split('.');
				mime = arr[arr.length - 1];
				// console.log(mime)
				mime_arr = 'jpg jpeg png bmp jpeg 2000 svg ico gif webp jfif';
				// console.log(mime_arr.indexOf(mime))

				<? if ($user_gif_photo == 0 and $user_status != 'Admin') : ?>
					if (mime == 'gif') {
							$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Вы пока не можете загрузить GIF. Используйте: jpg, jpeg, png, bmp, webp или svg');
							return;
					}
				<? endif; ?>

				if (mime_arr.indexOf(mime.toLowerCase()) == -1) {
					$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Загрузите изображения в одном из форматов: jpg, jpeg, png, bmp, webp или svg');
					return;
				}

				if (this.files[0].size / 1024 / 1024 > 15) {
					$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Размер изобраения не может превышать 15мб');
					$(this).val('');
					return;
				}


				// полный путь к изображению
				img_href = '<?=$link?>/uploads/user_photo/user_<?=$local_user_data['id']?>.' + mime;
				// селектор аватарки на сайте
				// img_selector = '.editProfile .info-basicInfo .imageControl .imageControl-photo-img img';

				// $('.editProfile .info-basicInfo .imageControl .imageControl-photo-img img').attr('src', '<?=$link?>/uploads/user_<?=$local_user_data['id']?>.' + mime);

				files = this.files;

				// console.log("typeof files: " + typeof files);
				if( typeof files == 'undefined' ) return;
				$('.editProfile .basic-info .imageControl-photo-img').addClass('loading');

				// создадим объект данных формы
				var data = new FormData();

				// заполняем объект данных файлами в подходящем для отправки формате
				$.each( files, function( key, value ){
					data.append( key, value );
				});
				// $('.editProfile .info-basicInfo .imageControl .imageControl-photo-img img').attr('src', '');

				// добавим переменную для идентификации запроса
				data.append( 'my_file_upload', 1 );
				data.append( 'stock_filename', $(this).val().split('/').pop().split('\\').pop() );
				data.append( 'needle_filename', 'user_<?=$local_user_data['id']?>' );
				data.append( 'img_type', 'user_photo' );
				data.append( 'secret_id', '<?= md5('user_' . $local_user_data['id'] . '_uploadFiles')?>' );
				// console.log(data);

				$.ajax({
					url : '<?=$link?>/inc/uploadFiles.php',
					type : 'POST',
					data : data,
					cache : false,
					// dataType : 'json',
					processData : false,
					contentType : false, 
					success : function( mime ){
						console.log(mime)
						// console.log("success upload files user_photo: " + html);

						if (img_href) {
								// console.log('change!')
								$('.editProfile .basic-info .imageControl-photo-img').removeClass('loading');
								$('.editProfile .info-basicInfo .imageControl .imageControl-photo-img img').attr('src', '<?=$link?>/uploads/user_<?=$local_user_data['id']?>' + mime + '?youAreCool=' + Math.random(0, 10000));
								$('.editProfile .imageControl-photo-img img:eq(0)').css({'left' : '0%', 'top' : '0%', 'transform' : 'scale(1)'});
								$('.editProfile .imageControl-inputs input:eq(0)').val(1);
								scroll = 1;
							// console.log($(img_selector))
						}

					},
					error : function (html) {
						// console.log("error upload files user_photo: " + html)
					}
				});

			}
			
			// console.log(fileName);
		})

		// Сохранение информации на вкладке "Основная информация"
		$('.editProfile .info-basicInfo .fullnameControl .fullnameControl-row2 button').click(function () {
		 	input_last_name = $('.editProfile .info-basicInfo .fullnameControl input:eq(1)').val().replace(/<\/?[^>]+(>|$)/g, "");
		 	input_first_name = $('.editProfile .info-basicInfo .fullnameControl input:eq(2)').val().replace(/<\/?[^>]+(>|$)/g, "");
		 	input_city = $('.editProfile .info-basicInfo .fullnameControl input:eq(3)').val().replace(/<\/?[^>]+(>|$)/g, "");
		 	// input_patronymic = $('.editProfile .info-basicInfo .fullnameControl input:eq(3)').val().replace(/<\/?[^>]+(>|$)/g, "");
		 	education = $('.editProfile .info-basicInfo .fullnameControl-row1 span input').val().replace(/<\/?[^>]+(>|$)/g, "");

		 	// if (("<?= $universities_arr ?>").split(' $$ ').indexOf($('.editProfile .info-basicInfo .fullnameControl-row1 span ul li[title="' + education + '"]').text()) == -1 || education.replace(' ', '') == '') {
		 	// 	$('.editProfile .info-basicInfo .fullnameControl p').text('Выберите существующее учебное заведение');
		 	// 	return;
		 	// }

		 	if (input_last_name.replace(' ', '').length < 1) {
		 		$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Фамилия слишком короткая');
		 		return;
		 	}
		 	if (input_first_name.replace(' ', '').length < 1) {
		 		$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Имя слишком короткое');
		 		return;
		 	}
		 	// if (input_patronymic.replace(' ', '').length < 1) {
		 	// 	$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Отчество слишком короткое');
		 	// 	return;
		 	// }
		 	
		 	photo_x = $('.editProfile .info-basicInfo .imageControl-photo-img img:eq(0)').css('left').replace('px', '') / 250 * 100;
		 	photo_y = $('.editProfile .info-basicInfo .imageControl-photo-img img:eq(0)').css('top').replace('px', '') / 250 * 100;
		 	photo_scale = $('.editProfile .info-basicInfo .imageControl-inputs input:eq(0)').val();

		 	photo_style = 'top: ' + photo_y + '%; left:' + photo_x + '%;';

		 	// fileName = $('.editProfile .info-basicInfo .imageControl-inputs input:eq(1)').val().split('/').pop().split('\\').pop();
		 	fileName = $('.editProfile .imageControl-photo-img img:eq(0)').attr('src');
		 	arr = fileName.split('?')[0].split('.');
		 	// arr = fileName.split('.');
			mime = arr[arr.length - 1];

			mime_arr = 'jpg jpeg png bmp jpeg 2000 svg ico gif webp jfif';

			<? if ($user_gif_photo == 0 and $user_status != 'Admin') : ?>
					if (mime == 'gif') {
							$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Вы пока не можете загрузить GIF. Используйте: jpg, jpeg, png, bmp, webp или svg');
							return;
					}
				<? endif; ?>
			if (mime_arr.indexOf(mime.toLowerCase()) == -1) {
				$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Загрузите изображения в одном из форматов: jpg, jpeg, png, bmp, webp или svg');
				return;
			}

			if (!fileName == '') {
				photo_url = 'user_<?=$local_user_data['id']?>.' + mime;
			} else {
				photo_url = '';
			}

		 	$.ajax({
		 		url: '<?=$link?>/inc/profile.php',
		 		type: "POST",
		 		data: {
		 			last_name : input_last_name.replace(/<\/?[^>]+(>|$)/g, ""),
					first_name : input_first_name.replace(/<\/?[^>]+(>|$)/g, ""),
					// patronymic : input_patronymic.replace(/<\/?[^>]+(>|$)/g, ""),
					photo_style : photo_style,
					photo_scale : photo_scale,
					photo_url : photo_url,
					education : education.replace(/<\/?[^>]+(>|$)/g, ""),
					education : education.replace(/<\/?[^>]+(>|$)/g, ""),
					city: input_city,
					type: 'save-main',
					local_user_id: <?= $local_user_data['id'] ?>,
					secret_id: "<?= md5('user_' . $local_user_data['token'] . '_saveMain')?>"
		 		},
		 		cache: false,
		 		success: function (html) {
		 			// console.log('successed: ' + html);
		 			<? if ($user_id == $local_user_data['id']) : ?>
		 				$('header .header .col-2 .menu .photo img').attr('src', html);
		 				$('header .header .col-2 .menu .name h3').text(input_last_name + ' ' + input_first_name.substr(0, 1) + '. ');
		 				$('.profile .image .info-1 .col-1 h2').text(input_last_name + ' ' + input_first_name);
		 				$('header .header .col-2 .menu .photo img').css({'top' : photo_y + '%', 'left' : photo_x + '%', 'transform' : 'scale(' + scroll + ')'});
		 			<? endif; ?>

		 			$('.profile .image .avatar img').attr('src', html);


		 			$('.editProfile .info-basicInfo .basic-info .fullnameControl button').removeClass().addClass('button-1');

					$('.editProfile .info-basicInfo .basic-info .fullnameControl p').text('');
					$('.editProfile .info-basicInfo .imageControl-inputs input:eq(1)').val('')
					// console.log("save-basicInfo: " + html)
					$('.profile .avatar img').css({'top' : photo_y + '%', 'left' : photo_x + '%', 'transform' : 'scale(' + scroll + ')'});
					if (education.replace(' ', '') == '') {
						education = '-';
					}
					if ($('.editProfile .info-basicInfo .fullnameControl-row1 span ul .hidden').length != $('.editProfile .info-basicInfo .fullnameControl-row1 span ul li').length) {
						$('.profile .info-2 .col-1:eq(4) h4').text(education);
					} else {
						$('.editProfile .info-basicInfo .fullnameControl-row1 span input').val('');
						$('.editProfile .info-basicInfo .fullnameControl-row1 span ul li').removeClass('hidden');
						$('.profile .info-2 .col-1:eq(4) h4').text('-')
					}
					
		 		},
		 		error: function (html) {
		 			// console.log('error: ' + html);
		 		}

		 	})
		})


		// изменение задней картинки пользователя в профиле
		$('.editProfile .info-bg .bgControl-buttons input:eq(0)').on('input keyup', function () {
			fileName = $(this).val().split('/').pop().split('\\').pop();

			$('.editProfile .info-bg .bgControl-buttons p').text('Не сохранено!');

			if (fileName != '') {
				arr = fileName.split('.');
				mime = arr[arr.length - 1];

				mime_arr = 'jpg jpeg png bmp jpeg 2000 svg ico gif webp jfif';

				<? if ($user_gif_photo == 0 and $user_status != 'Admin') : ?>
					if (mime == 'gif') {
						$('.editProfile .info-bg .bgControl-buttons p').text('Вы пока не можете загрузить GIF. Используйте: jpg, jpeg, png, bmp, webp или svg');
						return;
					}
				<? endif; ?>

				if (mime_arr.indexOf(mime.toLowerCase()) == -1) {
					$('.editProfile .info-bg .bgControl-buttons p').text('Загрузите изображения в одном из форматов: jpg, jpg, jpeg, png, bmp, webp или svg');
					return;
				}
				if (this.files[0].size / 1024 / 1024 > 15) {
					$('.editProfile .info-basicInfo .fullnameControl-row2 p').text('Размер изобраения не может превышать 15мб');
					return;
				}

				// полный путь к изображению
				img_href = '<?=$link?>/uploads/user_bg_photo/user_<?=$local_user_data['id']?>.' + mime;

				files = this.files;
				if( typeof files == 'undefined' ) return;

				// создадим объект данных формы
				var data = new FormData();
				$('.editProfile .info-bg .bgControl').addClass('loading');

				// заполняем объект данных файлами в подходящем для отправки формате
				$.each( files, function( key, value ){
					data.append( key, value );
				});

				// добавим переменную для идентификации запроса
				data.append( 'my_file_upload', 1 );
				data.append( 'stock_filename', $(this).val().split('/').pop().split('\\').pop() );
				data.append( 'needle_filename', 'user_bg_<?=$local_user_data['id']?>' );
				data.append( 'img_type', 'user_bg_photo' );
				data.append( 'secret_id', '<?= md5('user_' . $local_user_data['id'] . '_uploadFiles')?>' );
				// console.log(data);

				$.ajax({
					url : '<?=$link?>/inc/uploadFiles.php',
					type : 'POST',
					data : data,
					cache : false,
					// dataType : 'json',
					processData : false,
					contentType : false, 
					success : function( mime ){
						// console.log("success upload files user_bg" + html);
						if (img_href) {
							$('.editProfile .info-bg .bgControl').removeClass('loading');
							$('.editProfile .info-bg .bgControl .bgControl-image img').attr('src', '<?=$link?>/uploads/user_bg_<?=$local_user_data['id']?>' + mime + '?youAreCool=' + Math.random(0, 10000));
							// console.log($(img_selector))
						}
						$('.editProfile .info-bg .bgControl-buttons button').removeClass().addClass('button-3');
						$('.editProfile .info-bg .bgControl-buttons p').text('Изменения не сохранены!');
					},
					error : function (html) {
						// console.log("error upload files user_bg" + html);
					}
				});
			}
			// console.log(fileName);
		})


		// Сохранение информации на вкладке "Фон"
		$('.editProfile .info-bg .bgControl-buttons button:eq(1)').click(function () {
		 	photo_y = $('.editProfile .info-bg .bgControl-image img:eq(0)').css('top').replace('px', '') / 111 * 100;

		 	photo_style = 'top: ' + photo_y + '%;';

		 	// fileName = $('.editProfile .info-bg .bgControl-buttons input:eq(0)').val().split('/').pop().split('\\').pop();
		 	fileName = $('.editProfile .bgControl-image img:eq(0)').attr('src');
		 	arr = fileName.split('?')[0].split('.');
			mime = arr[arr.length - 1];

			mime_arr = 'jpg jpeg png bmp jpeg 2000 svg ico gif webp jfif';
			// console.log(mime_arr.indexOf(mime.toLowerCase()))

			<? if ($user_gif_photo == 0 and $user_status != 'Admin') : ?>
					if (mime == 'gif') {
						$('.editProfile .info-bg .bgControl-buttons p').text('Вы пока не можете загрузить GIF. Используйте: jpg, jpeg, png, bmp, webp или svg');
						return;
					}
				<? endif; ?>

			if (mime_arr.indexOf(mime.toLowerCase()) == -1) {
				$('.editProfile .info-bg .bgControl-buttons p').text('Загрузите изображения в одном из форматов: jpg, jpeg, png, bmp, webp или svg');
				return;
			}

			if (!fileName == '') {
				photo_url = 'user_bg_<?=$local_user_data['id']?>.' + mime;
			} else {
				photo_url = '';
			}

		 	$.ajax({
		 		url: '<?=$link?>/inc/profile.php',
		 		type: "POST",
		 		data: {
					photo_style : photo_style,
					photo_url : photo_url,
					type: 'save-bg',
					local_user_id: <?= $local_user_data['id'] ?>,
					secret_id: "<?= md5('user_' . $local_user_data['token'] . '_saveBg')?>"
		 		},
		 		cache: false,
		 		success: function (html) {
		 			$('.profile .image .bg img').attr('src', html);
		 			$('.editProfile .info-bg .bgControl-buttons button:eq(1)').removeClass().addClass('button-1');
		 			$('.editProfile .info-bg .bgControl-buttons p').text('');
					// console.log(html)
					$('.profile .bg img').css({'top' : photo_y + '%'});
		 		}
		 	})
		})

		// Сохранение приватности
		$('.editProfile .privacyControl-saveButton button').click(function () {
			if ($('.editProfile .privacyControl .privacy-messages').val() == 'Никто') {
				privacy_messages = 0;
			} 
			else if ($('.editProfile .privacyControl .privacy-messages').val() == 'Только друзья') {
				privacy_messages = 1;
			} 
			else {
				privacy_messages = 2;
			}

			if ($('.editProfile .privacyControl .closed-profile').val() == 'Закрытый') {
				closed_profile = 1;
			} else {
				closed_profile = 0;
			}

			$.ajax({
				url: '<?= $link ?>/inc/profile.php',
				cache: false,
				type: "POST",
				data: {
					privacy_messages: privacy_messages,
					closed_profile: closed_profile,
					type: 'save-privacy',
					local_user_id: <?= $local_user_data['id'] ?>,
					secret_id: "<?= md5('user_' . $local_user_data['token'] . '_savePrivacy')?>"
				},
				success: function (result) {
					$('.editProfile .privacyControl-saveButton button').removeClass().addClass('button-1');
					$('.editProfile .privacyControl-saveButton p').text('');
					// console.log("saveSecurity: " + result);
				}
			})
		})

		// Сохранение безопасности
		$('.editProfile .securityControl-saveButton button').click(function () {
			if ($('.editProfile .securityControl select').val() == 'Открытый') {
				closed_profile = 0;
			} else {
				closed_profile = 1;
			}
			// $.ajax({
			// 	url: '<?= $link ?>/inc/profile.php',
			// 	cache: false,
			// 	type: "POST",
			// 	data: {
			// 		closed_profile: closed_profile,
			// 		type: 'save-security',
					// local_user_id: <?= $local_user_data['id'] ?>,
			// 		secret_id: "<?= md5('user_' . $local_user_data['token'] . '_saveSecurity')?>"
			// 	},
			// 	success: function (result) {
			// 		$('.editProfile .securityControl-saveButton button').removeClass().addClass('button-1');
			// 		$('.editProfile .securityControl-saveButton p').text('');
			// 		// console.log("saveSecurity: " + result);
			// 	}
			// })
		})

		$('.editProfile .info-security .securityControl .logout').click(function () {
			$.ajax({
		 		url: '<?=$link?>/inc/profile.php',
		 		type: "POST",
		 		data: {
		 			type: 'change-token',
		 			local_user_id: <?= $local_user_data['id'] ?>,
					secret_id: "<?= md5('user_' . $local_user_data['token'] . '_changeToken')?>"
		 		},
		 		cache: false,
		 		success: function () {
		 			location.reload();
		 		}
		 	})
		})

	<? endif; ?>
	
	<? if ($user_id != $local_user_data['id'] and $local_user_data['status'] != 'deleted' and $local_user_data['status'] != 'pre-deleted' and $user_token != '') : ?>

		$('body').on('click', '.addFriend', function () {
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					local_user_id: <?= $local_user_data['id']?>,
					secret_id: '<?= md5('user_' . $user_token . '_addFriend')?>',
					type: 'add-friend'
				},
				success: function (result) {
					// console.log('success: ' + result);

					if (result == 'friend_added') {
						$('.addFriend').text('Удалить из друзей').attr('class', 'removeFriend button-1');
		    			
					} if (result == 'User is blacklisted') {

					} else {
						$('.addFriend').text('Заявка отправлена').attr('class', 'requestSended button-5');
		    			
					}
				}
			})
		})

		$('body').on('click', '.removeFriend', function () {
			console.log(1)
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					local_user_id: <?= $local_user_data['id']?>,
					secret_id: '<?= md5('user_' . $user_token . '_removeFriend')?>',
					type: 'remove-friend'
				},
				success: function (result) {
					// console.log('success: ' + result);
					$('.removeFriend').text('Добавить в друзья').attr('class', 'addFriend button-3');
				}
			})
		})

		$('body').on('click', '.blockUser', function () {
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					local_user_id: <?= $local_user_data['id']?>,
					secret_id: '<?= md5('user_' . $user_token . '_blockUser')?>',
					type: 'block-user'
				},
				success: function (result) {
					console.log('success: ' + result);

					if (result == 'user_blocked') {
						$('.blockUser').text('Разблокировать').attr('class', 'unblockUser button-1');
						$('.addFriend').attr('class', 'addFriend button-5');
						$('.requestSended').text('Добавить в друзья').attr('class', 'addFriend button-5');
						$('.removeFriend').text('Добавить в друзья').attr('class', 'addFriend button-5');
		    			
					}
				}
			})
		})

		$('body').on('click', '.unblockUser', function () {
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					local_user_id: <?= $local_user_data['id']?>,
					secret_id: '<?= md5('user_' . $user_token . '_unblockUser')?>',
					type: 'unblock-user'
				},
				success: function (result) {
					console.log('success: ' + result);

					if (result == 'user_unblocked') {
						$('.unblockUser').text('Заблокировать').attr('class', 'blockUser button-3');
						$('.addFriend').attr('class', 'addFriend button-1');
		    			
					}
				}
			})
		})

		$(document).on('click', '.add-reputation', function () {
			$.ajax({
				url: '<?= $link ?>/inc/userReputation.php',
				cache: false,
				method: 'POST',
				data: {
					type: 'add-reputation',
					user_id: <?= $local_user_data['id'] ?>,
					secret_id: '<?= md5('user_' . $user_token . '_addReputation')?>',
				},
				success: function (result) {
					// console.log(result)

					if (result != 'Invalid user_id') {
						result = JSON.parse(result);


						if (result['response_text'] != '') {
							$('.add-reputation').text('Вы выразили симпатию').removeClass('add-reputation').addClass('remove-reputation');

							$('.reputation h4').text(result['reputation_count']);
						}
					}
				}
			})
		})

		$('body').on('click', '.remove-reputation', function () {
			$.ajax({
				url: '<?= $link ?>/inc/userReputation.php',
				cache: false,
				method: 'POST',
				data: {
					type: 'remove-reputation',
					user_id: <?= $local_user_data['id'] ?>,
					secret_id: '<?= md5('user_' . $user_token . '_removeReputation')?>',
				},
				success: function (result) {
					if (result != 'Invalid user_id') {
						result = JSON.parse(result);

						if (result['response_text'] != '') {
							$('.remove-reputation').text('Вразить симпатию').removeClass('remove-reputation').addClass('add-reputation');

							$('.reputation h4').text(result['reputation_count']);
						}
					}
				}
			})
		})

	<? endif; ?>



		// Копирование ссылки на профиль пользователя
		$('.options li:eq(0)').click(function () {
			var $tmp = $("<textarea>");
		    $("body").append($tmp);
		    $tmp.val(window.location.href).select();
		    document.execCommand("copy");
		    $tmp.remove();

		    // console.log($(this).parent().parent())

		    $(this).children('p').text('Скопировано!');
		    $(this).children('img').css({"transform" : "rotateY(180deg) scale(-1, 1)"})
		    $(this).children('img').attr('src', '<?= $link ?>/assets/img/icons/clipboard-check.svg');

		    setTimeout(function () {
		    	$(this).children('img').css({"transform" : "scale(0, 1)"})
		    	$(this).children('img').attr('src', '<?= $link ?>/assets/img/icons/clipboard-check.svg');
		    	setTimeout(function () {
			    	// $('.options').removeClass('drop-down-opened');
			    	setTimeout(function () {
			    		// console.log(2233)
				    	$('.options ul li:eq(0)').children('p').text('Скопировать ссылку');
		    			$('.options ul li:eq(0)').children('img').attr('src', '<?= $link ?>/assets/img/icons/clipboard.svg');
		    			$('.options ul li:eq(0)').children('img').css({"transform" : "rotateY(0deg) scale(1, 1)"})
				    }, 300)

			    }, 900)
		    }, 150)
		})


		// Открытие панели настроек
		$('.options li:eq(1)').click(function () {
			$('body').css({"overflow" : "hidden"})
			$('.editPanel').addClass('editPanel-opened');
		})

		$.ajax({
			url: '<?= $link ?>/inc/interests-groups.php',
			method: 'POST',
			cache: false,
			data: {
				type: 'get-user-records-from-profile',
				local_user_id: '<?= $local_user_data['id'] ?>'
			},
			success: function (result) {
				if (result != '') {
					result = JSON.parse(result);
					if (result['type'] != 'empty') {

						if (result['count'] > 5) {
							$('.show-all-interests').css({'display' : 'inline'});
						}
						$('.interests h3 b').text('(' + result['count'] + ')')
						
					}

					if ('<?= $local_user_data['id'] ?>' == '<?= $user_id ?>') {
						$('.show-all-interests').css({'display' : 'inline'});
					}
					
					console.log(result['count'])
					$('.interests .interests-block').remove();
					$('.interests .empty').remove();
					$('.interests .list').append(result['html']);
				}
				
			}
		})
		


		
	</script>
	<script type="text/javascript" src="<?=$link?>/assets/editPanel.js"></script>
	<?
		include_once '../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('profile');
	</script>
</body>
</html>