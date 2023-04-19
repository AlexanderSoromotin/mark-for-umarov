<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	
	include_once '../inc/userData.php';

	// if ($user_status != 'Admin') {
	// 	header("Location: /");
	// }
	include_once '../inc/redirect.php';

	redirect('User', '/');
	redirect('Banned', '/banned');
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/');



	if ($user_status == 'Admin'):
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Админ-панель</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>
	<?	include_once '../inc/head.php';
		include_once '../inc/header.php';
		include_once '../assets/online.php'; // Онлайн

	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?=$link?>/">Главная</a>
			<img draggable="false" src="<?= $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?=$link?>/admin">Админ-панель</a>
		</div>
	</div>

	<div class="panel">
		<div class="menu">
			<ul>
				<li id="menu-stats" class="selected"><img draggable="false" src="<?=$link?>/assets/img/icons/chart-donut-3.svg">Статистика</li>
				<li id="menu-education" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/school.svg">Учебные заведения</li>
				<li id="menu-users" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/users.svg">Пользователи</li>
				<li id="menu-themes" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/notes.svg">Темы на форуме</li>
				<li id="menu-groups" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/brush.svg">Группы по интересам</li>
				<!-- <li id="menu-reports" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/alert-octagon.svg">Жалобы</li> -->
				<li id="menu-appeals" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/message-report.svg">Обращения</li>
				<li id="menu-logs" class=""><img draggable="false" src="<?=$link?>/assets/img/icons/file-code-2.svg">Журнал действий</li>
				
				
			</ul>
		</div>

		<div class="infoblock">
			<div class="infoblock-block" id="infoblock-stats">
				<h2>Статистика</h2>
				<div class="content">
					<p>Тут пока пусто, глянь что-нибудь другое с:</p>
				</div>
			</div>
			<div class="infoblock-block" id="infoblock-education">
				<h2>Учебные заведения</h2>
				<div class="tabs">
					<ul>
						<li id="infoblock-education-tabs-statements" class="selected">Заявки</li>
						<li id="infoblock-education-tabs-add" class="">Добавление заведений</li>
						<li id="infoblock-education-tabs-redactor" class="">Управление заведениями</li>
					</ul>
				</div>

				<div class="content">
					<!-- Блок заявок на добавление нового учебного заведения -->
					<div class="content-div statements">
						<div class="list">
							
							<?
								$application_add_education = mysqli_query($connection, "SELECT * FROM `application_add_education` WHERE `status` = 'Checking' ");
								if ($application_add_education -> num_rows == 0) {
									echo '<p class="empty">Нет заявок</p>';
								}
								else {
									while ($app = mysqli_fetch_assoc($application_add_education)) {
										$email = $app['email'];
										$res = mysqli_query($connection, "SELECT `id` FROM `users` WHERE `email` = '$email' ");
										if ($res -> num_rows != 0) {
											$sender = '<h4>от: <a target="_blink" href="' . $link . '/profile?id=' . mysqli_fetch_assoc($res)['id'] . '">' . $app['email'] . '</a></h4>';
										} else {
											$sender = '<h4>от: ' . $app['email'] . '</h4>';
										}
										echo '
										<div id="application_' . $app['id'] . '" class="list-block">
											<div class="col-1">
												' . $sender . '
												<h5>Полное название учебного заведения</h5>
												<textarea class="title" placeholder="Полное название">' . $app['title'] . '</textarea>
												<h5>Короткое название учебного заведения</h5>
												<input class="shortTitle" type="" name="" placeholder="Короткое название" value="' . $app['short_title'] . '">
												<h5>Индекс отображения</h5>
												<input class="index" type="" name="" placeholder="Короткое название" value="1">
											</div>
											<div alt="' . $app['id'] . '" class="col-2">
												<button class="button-3 button-save">Добавить</button>
												<button class="button-1 button-cancel">Отклонить</button>
											</div>
										</div>
										';
									}
								}
							?>
						</div>
						
					</div>

					<!-- Блок добавления нового учебного заведения -->
					<div class="content-div add">
						<div alt="" class="block">
							<div class="col-1">
								<input title="Индекс отображения (больше = выше в списке)" value="1" class="index" type="" name="" placeholder="Индекс...">
								<label>Объединение</label>
								<select>
									<option></option>
									<?
										$united_unis = mysqli_query($connection, "SELECT * FROM `united_education`");
										while ($united_uni = mysqli_fetch_assoc($united_unis)) {
											echo '<option>' . $united_uni['title'] . '</option>';
										}
									?>
								</select>
							</div>
							<textarea title="Полное название учебного заведения" class="title" placeholder="Полное название заведения..."></textarea>
							<textarea title="Короткое название учебного заведения" class="shortTitle" placeholder="Короткое название заведения..."></textarea>

							<div class="buttons">
								<p></p>
								<button class="button-3">Добавить</button>
							</div>
						</div>
					</div>

					<!-- Блок редактора учебных заведений -->
					<div class="content-div redactor">

						<div class="search">
							<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
							<input type="" name="" placeholder="Поиск">
						</div>

						<div class="example_titles">
							<p class="id">Айди</p>
							<p class="index">Индекс</p>
							<p class="title">Полное название</p>
							<p class="shortTitle">Короткое название</p>
							<p class="buttons">Управление</p>
						</div>

						<div class="list">
							<?	
							// display_index
								$unis = mysqli_query($connection, "SELECT * FROM `education` ORDER BY `ID` DESC");
								

								while ($u = mysqli_fetch_assoc($unis)) {
									$united_id = $u['united_id'];

									$selected_united_uni = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `united_education` WHERE `id` = '$united_id'"));

									if ($selected_united_uni['id'] != '') {
										$selected_united_uni = '<option>' . $selected_united_uni['title'] . '</option>';
									}
									

									$other_united_unis = mysqli_query($connection, "SELECT * FROM `united_education` WHERE `id` != '$united_id'");

									$other_united_unis_text = '<option></option>';
									while ($other_uni = mysqli_fetch_assoc($other_united_unis)) {
										$other_united_unis_text = $other_united_unis_text . '<option>' . $other_uni['title'] . '</option>';
									}



									
									


									echo '
										<div id="education_' . $u["id"] . '" alt="(' . $u['id'] . ') ' . $u["title"] . ' (' . $u['short_title'] . ')" class="list-block">
											<div class="col-1">
												<div class="row-1">
													<p>' . $u["id"] . '</p>
													<input value="' . $u['display_index'] . '" class="index" type="" name="" placeholder="Индекс...">
												</div>
												<div class="row-2">
												<label>Объединение</label> 
													<select>
														' . $selected_united_uni . '
														' . $other_united_unis_text . '
													<select>
												</div>
											</div>
											<textarea class="title" placeholder="Полное название заведения...">' . $u["title"] . '</textarea>
											<textarea class="shortTitle" placeholder="Короткое название заведения...">' . $u["short_title"] . '</textarea>
											<div alt="' . $u["id"] . '" class="buttons">
												<p class="message"></p>
												<button class="button-save button-1">Сохранить</button>
												<button class="button-delete button-1">Удалить</button>
											</div>
										</div>
									';
								}

							?>
						</div>
					</div>
				</div>
			</div>

			<div id="infoblock-users" class="infoblock-block">
				<h2>Пользователи</h2>

				<div class="tabs">
					<ul>
						<li id="infoblock-users-tabs-active" class="selected">Активные пользователи</li>
						<li id="infoblock-users-tabs-deleted" class="">
							Удалённые пользователи
							<?
								$count = mysqli_fetch_array(mysqli_query($connection, "SELECT COUNT(*) FROM `users` WHERE `status` = 'deleted' or `status` = 'pre-deleted'"))[0];
								if ($count != 0) {
									echo '(' . $count . ')';
								}
							?>
						</li>
					</ul>
				</div>

				<div class="content">

					<div class="content-div active">
						<div class="search">
							<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
							<input type="" name="" placeholder="Поиск">
						</div>

						<div class="example_titles">
							<p class="id">Айди</p>
							<p class="photo">Фото</p>
							<p class="name">ФИО</p>
							<p class="status">Статус</p>
							<p class="buttons">Управление</p>
						</div>

						<div class="list">

							<?	
							// display_index
								$users = mysqli_query($connection, "SELECT * FROM `users` WHERE `status` != 'deleted' and `status` != 'pre-deleted' ORDER BY `ID` DESC");

								
								while ($u = mysqli_fetch_assoc($users)) {
									$listBlockClass = '';

									if ($u['status'] == 'Admin') {
										$buttons = '<button class="button-1">User</button>
											<button class="button-3">Admin</button>
											<button class="button-1">Banned</button>';
										$statusColor = '#EDFFEE';

									} else if ($u['status'] == 'Banned') {
										$buttons = '<button class="button-1">User</button>
											<button class="button-1">Admin</button>
											<button class="button-3">Banned</button>';
										$listBlockClass = 'banned';
										$statusColor = '#FFEAEA';

									} else {
										$buttons = '<button class="button-3">User</button>
											<button class="button-1">Admin</button>
											<button class="button-1">Banned</button>';
										$statusColor = '#FFF';
									}

									$usage_gif = '';
									$usage_gif_filter = '';

									if ($u['gif_user_photo'] == 1) {
										$usage_gif = 'checked';
										$usage_gif_filter = '(gif)';
									}
									echo '
									<div style="background-color: ' . $statusColor . ';" id="user_' . $u['id'] . '" alt="(' . $u['id'] . ') ' . $u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['patronymic'] . ' (' . $u['email'] . ') ' . $u['status'] . ' ' . $usage_gif_filter . '" class="block ' . $listBlockClass . '">
										<div class="row-1">
											<a class="id" target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '">' . $u['id'] . '</a>
										<a target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '">
											<div class="img">
												<img style="' . unserialize($u['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($u['photo_style'])['scale'] . ');" src="' . $u['photo'] . '">
											</div>
										</a>

											<div class="fullname">
												<p>' . $u['last_name'] . '</p>
												<p>' . $u['first_name'] . '</p>
												<!--<p>' . $u['patronymic'] . '</p>-->
												<p>' . $u['email'] . '</p>
												<p><input name="usage_gif" type="checkbox" ' . $usage_gif . '> Использование .GIF</p>
											</div>

											<div class="status">
												' . $buttons . '
											</div>

											<div alt="' . $u['id'] . '" class="buttons">
												<p></p>
												<button class="button-1">Сохранить</button>
												
											</div>
										</div>
										<div class="row-2">
											<input type="" name="ban-reason" placeholder="Опишите причину блокировки" value="' . $u['ban_reason'] . '">
										</div>
									</div>
									';
								}

							?>
							<script type="text/javascript">
								$('#infoblock-users .active .search input').on('input keyup', function () {
									$('#infoblock-users .active .list .block').css({'display' : 'flex'});

									text = $(this).val();
									count = $('#infoblock-users .active .list .block').length;
									$('#infoblock-users .active .empty').remove();
									flag = count;
									
									for (eq = 0; eq < count; eq++) {
										block = $('#infoblock-users .active .list .block:eq(' + eq + ')');
										if (block.attr('alt').toLowerCase().indexOf(text.toLowerCase()) == -1) {
											block.css({'display' : 'none'});
											flag--;
										}
									}
									if (flag == 0) {
										$('#infoblock-users .active .list').append('<p class="empty">Нет результатов</p>');
									}

								})
							</script>
						</div>
					</div>

					<div class="content-div deleted">
						<!-- <div class="search">
							<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
							<input type="" name="" placeholder="Поиск">
						</div> -->

						<div class="example_titles">
							<p class="id">Айди</p>
							<p class="photo">Фото</p>
							<p class="name">ФИО</p>
							<p class="reason">Причина</p>
							<p class="date">Планируемая дата удаления</p>
						</div>

						<div class="list">

							<?	
							// display_index
								$users = mysqli_query($connection, "SELECT * FROM `users` WHERE `status` = 'deleted' or `status` = 'pre-deleted' ORDER BY `ID` DESC");

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


								while ($u = mysqli_fetch_assoc($users)) {

									if ($u['delete_account_reason'] == '') {
										$u['delete_account_reason'] = 'Нет причины.';
									} else {
										$u['delete_account_reason'] = 'Причина: ' . $u['delete_account_reason'];
									}

									$statusColor = '#FFF';
									if ($u['status'] == 'deleted') {
										$statusColor = 'rgba(0, 0, 0, .05)';

									}

									$PreDeleted_day = substr($u['delete_account_date'], 0, 2);
									$PreDeleted_month = (int) substr($u['delete_account_date'], 3, 2);
									$PreDeleted_year = substr($u['delete_account_date'], 6, 4);

									$deleted_month = $PreDeleted_month + 6;
									$deleted_year = (int) $PreDeleted_year;

									if ($deleted_month > 12) {
										$deleted_month -= 12;
										$deleted_year++;
									}

									$date = deleteZeroes($PreDeleted_day) . ' ' . $months_accusative[addZeroes($deleted_month)] . ' ' . $deleted_year;

									echo '
									<div style="background-color: ' . $statusColor . ';" id="user_' . $u['id'] . '" alt="(' . $u['id'] . ') ' . $u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['patronymic'] . ' (' . $u['email'] . ') ' . $u['status'] . ' ' . $usage_gif_filter . '" class="block ' . $listBlockClass . '">
										<div class="row-1">
											<a target="_blink" href="' . $link . '/profile/?id=' . $u['id'] . '">' . $u['id'] . '</a>
											<div class="img">
												<img style="' . unserialize($u['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($u['photo_style'])['scale'] . ');" src="' . $u['photo'] . '">
											</div>

											<div class="fullname">
												<p>' . $u['last_name'] . '</p>
												<p>' . $u['first_name'] . '</p>
												<p>' . $u['patronymic'] . '</p>
												
											</div>

											<div class="delete_reason">
												<p>' . $u['delete_account_reason'] . '</p>
											</div>

											<div class="delete_date">
												<p>' . $date . '</p>
											</div>


											

											
										</div>
									</div>
									';
								}

							?>
							
							<script type="text/javascript">
								$('#infoblock-users .search input').on('input keyup', function () {
									$('#infoblock-users .list .block').css({'display' : 'flex'});

									text = $(this).val();
									count = $('#infoblock-users .list .block').length;
									$(' #infoblock-users .list .empty').remove();
									flag = count;
									
									for (eq = 0; eq < count; eq++) {
										block = $('#infoblock-users .list .block:eq(' + eq + ')');
										if (block.attr('alt').toLowerCase().indexOf(text.toLowerCase()) == -1) {
											block.css({'display' : 'none'});
											flag--;
										}
									}
									if (flag == 0) {
										$('#infoblock-users .list').append('<p class="empty">Нет результатов</p>');
									}

								})
							</script>
						</div>
					</div>

				</div>
				
			</div>
			<div id="infoblock-themes" class="infoblock-block">
				<h2>Темы на форуме</h2>
				<div class="content">
					<p>Тут пока пусто, глянь что-нибудь другое с:</p>
				</div>
			</div>
			<div id="infoblock-groups" class="infoblock-block">
				<h2>Группы по интересам</h2>
				<div class="content">
					<p>Тут пока пусто, глянь что-нибудь другое с:</p>
				</div>
			</div>
			<!-- <div id="infoblock-reports" class="infoblock-block">
				<h2>Жалобы</h2>
				<div class="content">
					<p>Тут пока пусто, глянь что-нибудь другое с:</p>
				</div>
			</div> -->
			<div id="infoblock-appeals" class="infoblock-block">

				<h2>Обращения</h2>

				<div class="tabs">
					<ul>
						<li id="infoblock-appeals-tabs-appeals" class="selected">
							Обращения
							<?
								$count = mysqli_fetch_array(mysqli_query($connection, "SELECT COUNT(*) FROM `support_tickets` WHERE `status` = 'Checking'"))[0];
								if ($count != 0) {
									echo '(' . $count . ')';
								}
							?>
						</li>
						<li id="infoblock-appeals-tabs-closed" class="">Закрытые тикеты</li>
					</ul>
				</div>

				<div class="content">
					<div class="content-div appeals">
						<div class="list">

							<?
								$appeals = mysqli_query($connection, "SELECT * FROM `support_tickets` WHERE `status` = 'Checking'");
								if ($appeals -> num_rows == 0) {
									echo '<p class="empty">Нет обращений</p>';
								}
								else {
									while ($app = mysqli_fetch_assoc($appeals)) {
										$email = $app['email'];
										$appealer_id = $app['appealer_id'];

										$res = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$appealer_id' ");
										$appealer = mysqli_fetch_assoc($res);

										if ($res -> num_rows != 0) {
											$sender = '<p>от: <a target="_blink" href="' . $link . '/profile?id=' . $appealer['id'] . '">' . $app['email'] . '</a></p>';
											$name = $appealer['first_name'];
											$warning = '';
										} else {
											$sender = '<h4>от: ' . $app['email'] . '</h4>';
											$name = $app['email'];
											$warning = '<h4 class="warning">У пользователя нет аккаунта! Свяжитесь с ним по почте.</h4>';
										}


										echo '
										<div class="list-block" id="appeal_' . $app['id'] . '">
											<p class="id">ID: ' . $app['id'] . '</p>
											' . $sender . '
											<h4>Тема</h4>
											<p>
												' . $app['theme'] . '
											</p>
											<h4>Сообщение</h4>
											<p>
												' . $app['message'] . '
											</p>
											<h4>Ответ</h4>
											<textarea name="">' . $name . ', мы рассмотрели ваше обращение.</textarea> 
											' . $warning . '
											<center>
												<button class="button-3">Закрыть тикет</button>
												<button class="button-1">Удалить тикет</button>
											</center>
										</div>
										';
									}
								}
							?>
						</div>
					</div>

					<div class="content-div closed">
						<div class="list">
							<?
								$closed_appeals = mysqli_query($connection, "SELECT * FROM `support_tickets` WHERE `status` = 'Closed' ORDER BY `id` DESC");
								if ($closed_appeals -> num_rows == 0) {
									echo '<p class="empty">Нет закрытых тикетов</p>';
								}
								else {
									while ($app = mysqli_fetch_assoc($closed_appeals)) {
										$email = $app['email'];
										$admin_id = $app['admin_id'];

										$res1 = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` = '$email' ");
										$res2 = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$admin_id' ");

										$appealer = mysqli_fetch_assoc($res1);
										$admin = mysqli_fetch_assoc($res2);

										if ($res1 -> num_rows != 0) {
											$sender = '<p>от: <a target="_blink" href="' . $link . '/profile?id=' . $appealer['id'] . '">' . $app['email'] . '</a></p>';
											$admin = '<p>ответил: <a target="_blink" href="' . $link . '/profile?id=' . $admin['id'] . '">' . $admin['email'] . '</a></p>';

										} else {
											$sender = '<h4>от: ' . $app['email'] . '</h4>';
											$admin = '<p>ответил: <a target="_blink" href="' . $link . '/profile?id=' . $admin['id'] . '">' . $admin['email'] . '</a></p>';
										}


										echo '
										<div class="list-block" id="appeal_' . $app['id'] . '">
											<p class="id">ID: ' . $app['id'] . '</p>
											' . $sender . '
											' . $admin . '
											<h4>Тема</h4>
											<p>
												' . $app['theme'] . '
											</p>
											<h4>Сообщение</h4>
											<p>
												' . $app['message'] . '
											</p>
											<h4>Ответ</h4>
											<p>' . $app['answer'] . '<p> 
										</div>
										';
									}
								}
							?>
						</div>
					</div>

				</div>
			</div>


			<div id="infoblock-logs" class="infoblock-block">
				<h2>Журнал действий <b>(0-300)</b></h2>
				<div class="content">
					
					<div class="search">
						<img draggable="false" src="<?= $link ?>/assets/img/icons/search.svg">
						<input type="" name="" placeholder="Поиск по ID">
					</div>

					<div class="search_shortInfo shortInfo">
						<img draggable="false" class="" src="<?= $link ?>/assets/img/icons/help.svg">
						<p>
							Поиск можно осуществить по ID пользователя или статусту, например: admin
						</p>
					</div>

					<div class="list">
						<!-- <div class="list-block" id="log_46">
							<div class="user-info">
								<a class="" href="#">
									<div class="user">
										<img src="https://sun9-87.userapi.com/impg/A-M9Y8k9abinFyYVpbhtElci9b83z0wml7XKMA/iRyPUBAk-dw.jpg?size=680x1080&quality=96&sign=e2e52ae1412cccf7135b3459cd8dfda0&type=album">
									</div>
								</a>

								<div class="first_user_shortInfo shortInfo">
									<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
									<p>
										sannekys@gmail.com
										<br>
										<i>Admin</i>
										<br>
										<b>Соромотин</b>
										<br>
										<b>Александр</b>
										<br>
										<b>Сергеевич</b>
										<br>
										ID: <a href="#">21</a>
									</p>
								</div>

								<div class="info">
									<div class="row-1">
										<p><b>sannekys@gmail.com </b>#21</p>
										<p>Изменил основную информацию своего профиля</p>
									</div>

									<div class="row-2">
										<p>5 нояб. 2021 года 12:46:31</p>
									</div>
									
								</div>

								<a class="" href="#">
									<div class="user">
										<img src="https://sun9-87.userapi.com/impg/A-M9Y8k9abinFyYVpbhtElci9b83z0wml7XKMA/iRyPUBAk-dw.jpg?size=680x1080&quality=96&sign=e2e52ae1412cccf7135b3459cd8dfda0&type=album">
									</div>
								</a>

								<div class="second_user_shortInfo shortInfo">
									<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
									<p>
										sannekys@gmail.com
										<br>
										<i>Admin</i>
										<br>
										<b>Соромотин</b>
										<br>
										<b>Александр</b>
										<br>
										<b>Сергеевич</b>
										<br>
										ID: <a href="#">21</a>
									</p>
								</div>

								<div class="second_info info">
									<div class="row-1">
										<p><b>sannekys@gmail.com </b>#21</p>
									</div>

									<button class="button-3">Посмотреть изменения</button>
									
								</div>

							</div>

							<div class="changes">
								<img src="<?=$link?>/assets/img/icons/arrow-left.svg">
								<div class="col-1">
									<p><b>Фото:</b> http://findreek.com/uploads/user_photo/user_21.jpg</p>
									<p><b>Стиль фото:</b> top: 0%; left:0%; transform:scale(1.95);</p>
									<p><b>Учебное заведение:</b> (32) КПО ПГНИУ</p>
									<p><b>ФИО:</b> Соромотин Александр Сергеевич</p>

								</div>
							</div>
						</div> -->

						<?
							$logs = mysqli_query($connection, "SELECT * FROM `logs` ORDER BY `id` DESC LIMIT 0, 1");
						// $logs = '';

							while ($l = mysqli_fetch_assoc($logs)) {
								$log_description = unserialize($l['description']);


								if ($l['function'] == 'edit_profile [basic_info]') {
									if ($l['user_id'] == $l['second_user_id']) {
										$first_user_id = $l['user_id'];
										$l['function'] = 'Изменил основную информацию своего профиля';

										$first_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$first_user_id'"));
										$second_user = $first_user;

									} else {
										$first_user_id = $l['user_id'];
										$second_user_id = $l['second_user_id'];
										$l['function'] = 'Изменил основную информацию профиля #' . $l['second_user_id'];
										
										$first_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$first_user_id'"));
										$second_user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$second_user_id'"));
									}

									$uni_id = $l['second_user_id'];

									$log_description_photo = '<p><b>Фото:</b> ' . $log_description['photo'] . '</p>';
									$log_description_photo_style = '<p><b>Стиль фото:</b> ' . unserialize($log_description['photo_style'])['ox_oy'] . 'transform:scale(' . unserialize($log_description['photo_style'])['scale'] . ');';
									$uni = mysqli_fetch_array(mysqli_query($connection, "SELECT `short_title` FROM `education` WHERE `id` = '$uni_id'"));

									$log_description_education = '<p><b>Учебное заведение:</b> ' . $log_description['education'] . '</p>';
									$log_description_name = '<p><b>ФИО:</b> ' . $log_description['last_name'] . ' ' . $log_description['first_name'] . ' ' . $log_description['patronymic'] . '</p>';
									$description = $log_description_photo . $log_description_photo_style .$log_description_education . $log_description_name;
								}

								echo '
								<div alt="(' . $first_user['status'] . '_' . $first_user['id'] . ') | (' . $second_user['status'] . '_' . $second_user['id'] . ')" class="list-block" id="log_' . $l['id'] . '">
									<div class="user-info">
										<a target="_blink" href="' . $link . '/profile/?id=' . $first_user['id'] . '">
											<div class="user">
												<img style=" ' . unserialize($first_user['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($first_user['photo_style'])['scale'] . ');" src="' . $first_user['photo'] . '">
											</div>
										</a>

										<div class="first_user_shortInfo shortInfo">
											<img draggable="false" class="" src="' . $link . '/assets/img/icons/help.svg">
											<p>
												' . $first_user['email'] . '
												<br>
												<i>' . $first_user['status'] . '</i>
												<br>
												<b>' . $first_user['last_name'] . '</b>
												<br>
												<b>' . $first_user['first_name'] . '</b>
												<br>
												<b>' . $first_user['patronymic'] . '</b>
												<br>
												ID: <a target="_blink" href="' . $link . '/profile/?id=' . $first_user['id'] . '">' . $first_user['id'] . '</a>
											</p>
										</div>

										<div class="info">
											<div class="row-1">
												<p><b>' . $first_user['email'] . ' </b>#' . $first_user['id'] . '</p>
												<p>' . $l['function'] . '</p>
											</div>

											<div class="row-2">
												<p>' . substr($l['date'], 8, 2) . ' ' . $months_short[substr($l['date'], 5, 2)] . ' ' . substr($l['date'], 0, 4) . ' года ' . substr($l['date'], 11, 8) . '</p>
											</div>
											
										</div>

										<a target="_blink" href="' . $link . '/profile/?id=' . $second_user['id'] . '">
											<div class="user">
												<img style=" ' . unserialize($second_user['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($second_user['photo_style'])['scale'] . ');" src="' . $second_user['photo'] . '">
											</div>
										</a>

										<div class="second_user_shortInfo shortInfo">
											<img draggable="false" class="" src="' . $link . '/assets/img/icons/help.svg">
											<p>
												' . $second_user['email'] . '
												<br>
												<i>' . $second_user['status'] . '</i>
												<br>
												<b>' . $second_user['last_name'] . '</b>
												<br>
												<b>' . $second_user['first_name'] . '</b>
												<br>
												<b>' . $second_user['patronymic'] . '</b>
												<br>
												ID: <a target="_blink" href="' . $link . '/profile/?id=' . $second_user['id'] . '">' . $second_user['id'] . '</a>
											</p>
										</div>

										<div class="second_info info">
											<div class="row-1">
												<p><b>' . $second_user['email'] . ' </b>#' . $second_user['id'] . '</p>
											</div>

											<button class="button-3">Посмотреть изменения</button>
											
										</div>

									</div>

									<div class="changes">
										<img src="' . $link . '/assets/img/icons/arrow-left.svg">
										<div class="col-1">
											' . $description . '

										</div>
									</div>
								</div>
									';
							}


						?>
					</div>

				</div>
				<!-- Поиск логов по ID -->
				<script type="text/javascript">
					$('#infoblock-logs .search input').on('input keyup', function () {
						$('#infoblock-logs .list .list-block').css({'display' : 'flex'});

						text = $(this).val();
						count = $('#infoblock-logs .list .list-block').length;
						$('#infoblock-logs .empty').remove();
						flag = count;
						
						for (eq = 0; eq < count; eq++) {
							block = $('#infoblock-logs .list .list-block:eq(' + eq + ')');
							if (block.attr('alt').toLowerCase().indexOf(text.toLowerCase()) == -1) {
								block.css({'display' : 'none'});
								flag--;
							}
						}
						if (flag == 0) {
							$('#infoblock-logs .list').append('<p class="empty">Нет результатов</p>');
						}

					})
				</script>
			</div>
			
			
			
		</div>
	</div>
	<script type="text/javascript">

		// Развёртывание логов
		$('#infoblock-logs .list-block .second_info button').click(function () {
			id = $(this).parent().parent().parent().attr('id');
			parent = $('#infoblock-logs #' + id);
			parent.addClass('openChanges');
		})
		// Свёртывание логов
		$('#infoblock-logs .list-block .changes img').click(function () {
			id = $(this).parent().parent().attr('id');
			parent = $('#infoblock-logs #' + id);
			// console.log(parent)
			parent.removeClass('openChanges');

		})

		$('.panel .menu ul li').click(function () {
			if (!$(this).hasClass('selected')) {
				$('.panel .menu ul .selected').removeClass('selected');
				$(this).addClass('selected');
			}
		})
		function addNotification (selector) {
			$(selector).addClass('notification');						
		}
		function removeNotification (selector) {
			$(selector).removeClass('notification');
			if ($('.panel .menu ul .notification').length == 0) {
				$('header .header .admin-panel').removeClass('notification');
			}						
		}
		function openInfoblock (name) {
			$('.infoblock-block').css({"display" : "none"});
			$('#infoblock-' + name).css({"display" : "inline"});
		}

		function openTab (parent, name) {
			$('#infoblock-' + parent + ' .tabs ul li').removeClass('selected');
			$('#infoblock-' + parent + '-tabs-' + name).addClass('selected');

			$('#infoblock-' + parent + ' .content-div').css({"display" : "none"});
			$('#infoblock-' + parent + ' .content .' + name).css({"display" : "inline"});

		}
		// openInfoblock('users');
		openInfoblock('stats');
		// openInfoblock('education');
		// openInfoblock('logs');


		// openTab('education', 'statements');
		openTab('education', 'add');
		openTab('appeals', 'appeals');
		openTab('users', 'active');


		// Открытие раздела
		$('.menu ul li').click(function () {
			openInfoblock($(this).attr('id').replace('menu-', ''));
		})

		

		// Проверка оставшихся  уведомлений, если их нет, то убираем уведомление с .admin-panel
		function checkNotifications () {
			if ($('#infoblock-education .statements .list-block').length != 0) {
				return;
			}
			if ($('#infoblock-appeals .appeals .list-block').length != 0) {
				return;
			}
			$('header .admin-panel').removeClass('notification');
		}

		// Открытие вкладки (Учебные заведения)
		$('#infoblock-education .tabs ul li').click(function () {
			openTab('education', $(this).attr('id').replace('infoblock-education-tabs-', ''));
		})
		// Открытие вкладки (Обращения)
		$('#infoblock-appeals .tabs ul li').click(function () {
			openTab('appeals', $(this).attr('id').replace('infoblock-appeals-tabs-', ''));
		})
		// Открытие вкладки (Пользователи)
		$('#infoblock-users .tabs ul li').click(function () {
			openTab('users', $(this).attr('id').replace('infoblock-users-tabs-', ''));
		})




		$('.search img').click(function () {
			$(this).parent().children('input').focus();
		})
		// поиск по учебным заведениям
		$('#infoblock-education .search input').on('input keyup', function () {
			text = $(this).val();
			$('#infoblock-education .list-block').css({'display' : 'flex'})
			$('#infoblock-education .list .empty').remove();
			// console.log(text)
			if (text.replace(' ', '') != '') {
				let count = $('#infoblock-education .list-block').length;
				flag = 0;
				// console.log(count)
				
				for (let eq = 0; eq < count; eq++) {
					if ( $('#infoblock-education .list-block:eq(' + eq + ')').attr('alt').toLowerCase().indexOf(text.toLowerCase()) == -1 ) {
						$('#infoblock-education .list-block:eq(' + eq + ')').css({'display' : 'none'})
						flag++;

					}

				}
				if (count == flag) {
					$('#infoblock-education .list').append('<p class="empty">Нет результатов</p>');
				}

			}
		})

		// Сообщение об изменении информации об УУ
		$('#infoblock-education .redactor .list-block input, #infoblock-education .redactor .list-block textarea').on('input keyup', function () {
			id = $(this).parent().attr('id');
			$('#infoblock-education .redactor #' + id + ' .buttons p').text('Не сохранено!');
		});
		// Сообщение об изменении информации об УУ
		$('#infoblock-education .redactor .list-block select').on('input change', function () {
			id = $(this).parent().parent().parent().attr('id');
			$('#infoblock-education .redactor #' + id + ' .buttons p').text('Не сохранено!');
		});

		// Сохранение учебного заведения
		$('body').on('click', '#infoblock-education .redactor .buttons .button-save', function() {
			id = $(this).parent().attr('alt');

			index = $('#infoblock-education .redactor #education_' + id + ' .index').val();
			title = $('#infoblock-education .redactor #education_' + id + ' .title').val();
			shortTitle = $('#infoblock-education .redactor #education_' + id + ' .shortTitle').val();
			united_title = $('#infoblock-education .redactor #education_' + id + ' select').val();
			// console.log(unitedTitle)
			// console.log(title)
			// console.log(shortTitle)

			if (index.replace(' ', '') == '' || title.replace(' ', '') == '' || shortTitle.replace(' ', '') == '') {
				return;
			}

			$.ajax({
				url: "<?=$link?>/inc/editEducation.php",
				type: "POST",
				cache: false,
				data: {
					id: id,
					index: index,
					title: title,
					shortTitle: shortTitle,
					united_title: united_title,
					type: 'save'
				},
				success: function (result) {
					// console.log("success: " + result)
					$('#infoblock-education .redactor #education_' + id + ' .buttons p').text('');
				},
				error: function (result) {
					// console.log("failed: " + result)
				}
			})
		})

		// Удаление учебного заведения
		$('body').on('click', '#infoblock-education .redactor .buttons .button-delete', function() {
			id = $(this).parent().attr('alt');

			

			if (confirm('Вы уверены, что хотите удалить учебное заведение с ID = ' + id + ' ?') == true) {
				parent = $(this).parent().parent()
				$(this).parent().parent().css({'margin-left' : '105%'})

				setTimeout(function () {
					parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});

					setTimeout(function () {
						parent.css({'display' : 'none'});
					}, 300)
				}, 400)

				$.ajax({
					url: "<?=$link?>/inc/editEducation.php",
					type: "POST",
					cache: false,
					data: {
						id: id,
						type: 'delete'
					},
					success: function (result) {
						// console.log("success: " + result)
						// $(this).parent().parent().css({'margin-left' : '-110%'})

					},
					error: function (result) {
						// console.log("failed: " + result)
					}
				})
			};
		})

		// Добавление учебного заведения
		$('#infoblock-education .add .buttons button').click(function () {
			index = $('#infoblock-education .add .index').val();
			title = $('#infoblock-education .add .title').val();
			shortTitle = $('#infoblock-education .add .shortTitle').val();
			united_title = $('#infoblock-education .add select').val();

			if (index.replace(' ', '') == '' || title.replace(' ', '') == '' || shortTitle.replace(' ', '') == '') {
				return;
			}

			$.ajax({
				url: "<?=$link?>/inc/editEducation.php",
				type: "POST",
				cache: false,
				data: {
					index: index,
					title: title,
					shortTitle: shortTitle,
					united_title: united_title,
					type: 'add'
				},
				success: function (id) {
					$('#infoblock-education .add .buttons p').text('Добавлено').css({'opacity' : '1'});
					setTimeout(function () {
						$('#infoblock-education .add .buttons p').css({'opacity' : '0'});
						setTimeout(function () {
							$('#infoblock-education .add .buttons p').text('');
						}, 300)
					}, 1000)

					$('#infoblock-education .redactor .list').prepend('<div id="education_' + id + '" alt="' + title + ' (' + shortTitle + ')" class="list-block"><p>' + id + '</p><input value="' + index + '" class="index" type="" name="" placeholder="Индекс..."><textarea class="title" placeholder="Полное название заведения...">' + title + '</textarea><textarea class="shortTitle" placeholder="Короткое название заведения...">' + shortTitle + '</textarea><div alt="' + id + '" class="buttons"><p class="message"></p><button class="button-save button-1">Сохранить</button><button class="button-delete button-1">Удалить</button></div></div>');

					$('#infoblock-education .add .index').val('');
					$('#infoblock-education .add .title').val('');
					$('#infoblock-education .add .shortTitle').val('');

				},
				error: function (result) {
					// console.log("failed: " + result)
				}
			})
		})

		// Одобрение заявки на добавление учебного заведения
		$('#infoblock-education .statements .col-2 .button-save').click(function () {
			id = $(this).parent().attr('alt');
			let index = $('#infoblock-education .statements #application_' + id +' .index').val();
			let title = $('#infoblock-education .statements #application_' + id +' .title').val();
			let shortTitle = $('#infoblock-education .statements #application_' + id +' .shortTitle').val();
			let user_email = $('#infoblock-education .statements #application_' + id +' h4 a').text();

			if (index.replace(' ', '') == '' || title.replace(' ', '') == '' || shortTitle.replace(' ', '') == '') {
				return;
			}
			parent = $(this).parent().parent();

			$.ajax({
				url: "<?=$link?>/inc/editEducation.php",
				type: "POST",
				cache: false,
				data: {
					index: index,
					title: title,
					shortTitle: shortTitle,
					user_email: user_email,
					id: id,
					type: 'approval'
				},
				success: function (id) {
					// console.log("success: " + id)
					
					parent.css({'margin-left' : '105%'})

					setTimeout(function () {
						parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});

						setTimeout(function () {
							parent.remove();
							if ($('#infoblock-education .statements .list-block').length == 0) {
									removeNotification('#infoblock-education-tabs-statements', '#menu-education');
									removeNotification('#menu-education');
									$('#infoblock-education .statements .list').append('<p class="empty">Нет заявок</p>');
									checkNotifications();
								}
						}, 300)
					}, 400)
					$('#infoblock-education .redactor .list').prepend('<div id="education_' + id + '" alt="' + title + ' (' + shortTitle + ')" class="list-block"><p>' + id + '</p><input value="' + index + '" class="index" type="" name="" placeholder="Индекс..."><textarea class="title" placeholder="Полное название заведения...">' + title + '</textarea><textarea class="shortTitle" placeholder="Короткое название заведения...">' + shortTitle + '</textarea><div alt="' + id + '" class="buttons"><p class="message"></p><button class="button-save button-1">Сохранить</button><button class="button-delete button-1">Удалить</button></div></div>');

					if ($('#infoblock-education .statements .list-block').length == 0) {
						removeNotification('#infoblock-education-tabs-statements', '#menu-education');
						removeNotification('#menu-education');
					}

				},
				error: function (result) {
					// console.log("failed: " + result)
				}
			})
		})

		// Отклонение заявки на добавление учебного заведения
		$('#infoblock-education .statements .col-2 .button-cancel').click(function () {

			if (confirm('Вы уверены, что хотите отклонить заявку на добавление?') == true) {
				parent = $(this).parent().parent();
				id = $(this).parent().parent().attr('id').replace('application_', '');

				$.ajax({
					url: "<?=$link?>/inc/editEducation.php",
					type: "POST",
					cache: false,
					data: {
						id: id,
						type: 'cancel'
					},
					success: function () {
						// console.log("success: " + id)
						
						parent.css({'margin-left' : '105%'})

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});

							setTimeout(function () {
								parent.remove();
								if ($('#infoblock-education .statements .list-block').length == 0) {
									removeNotification('#infoblock-education-tabs-statements', '#menu-education');
									removeNotification('#menu-education');
									$('#infoblock-education .statements .list').append('<p class="empty">Нет заявок</p>');
									checkNotifications();
								}
							}, 300)
						}, 400)

						

					},
					error: function (result) {
						// console.log("failed: " + result)
					}
				})
			}
			
		})

		// Сохранение статуса пользователя
		$('#infoblock-users .list .block .buttons button').click(function () {
			id = $(this).parent().attr('alt');
			parent = $(this).parent().parent().parent();
			// console.log(parent)
			status = $('#infoblock-users .list #user_' + id + ' .status .button-3').text();
			ban_reason = $('#infoblock-users .list #user_' + id + ' input[name="ban-reason"]').val();

			gif_user_photo = 0;
			if ($('#infoblock-users .list #user_' + id + ' input:eq(0)').is(':checked')) {
				gif_user_photo = 1;
			}
			// console.log(gif_user_photo);
			
			$.ajax({
					url: "<?=$link?>/inc/profile.php",
					type: "POST",
					cache: false,
					data: {
						local_user_id: id,
						status: status,
						gif_user_photo: gif_user_photo,
						ban_reason: ban_reason,
						type: 'change-status',
						secret_id: '<?= md5('user_' . $user_token . '_changeStatus')?>'
					},
					success: function (result) {
						console.log("success: " + result)
						if (status == "Admin") {
							$('#infoblock-users .list #user_' + id).css({'background-color' : '#EDFFEE'})
							parent.removeClass('banned')
						}
						if (status == "Banned") {
							$('#infoblock-users .list #user_' + id).css({'background-color' : '#FFEAEA'})
							parent.addClass('banned')
						}
						if (status == "User") {
							$('#infoblock-users .list #user_' + id).css({'background-color' : '#FFF'})
							parent.removeClass('banned')
						}
						$('#infoblock-users .list #user_' + id +' .buttons p').text('');
					},
					error: function (result) {
						// console.log("failed: " + result)
					}
				})

		})

		// Закрытие тикета
		$('#infoblock-appeals .list .list-block .button-3').click(function () {
			id = $(this).parent().parent().attr('id').replace('appeal_', '');
			parent = $(this).parent().parent();
			// console.log(id)
			answer = $('#infoblock-appeals .appeals .list #appeal_' + id + ' textarea').val();
			theme = $('#infoblock-appeals .appeals .list #appeal_' + id + ' p:eq(2)').text();
			message = $('#infoblock-appeals .appeals .list #appeal_' + id + ' p:eq(3)').text();
			email = $('#infoblock-appeals .appeals .list #appeal_' + id + ' p:eq(1) a').text();
			// console.log(answer)
			// return;

			$.ajax({
					url: "<?=$link?>/inc/support.php",
					type: "POST",
					cache: false,
					data: {
						id: id,
						answer: answer,
						email: email,
						type: 'close-ticket'
					},
					success: function (sender_admin) {
						count_appeals = $('#infoblock-appeals .appeals .list-block').length;
						
						count_appeals--;
						if (count_appeals == 0) {
							$('#infoblock-appeals .tabs #infoblock-appeals-tabs-appeals').text('Обращения')
						} else {
							$('#infoblock-appeals .tabs #infoblock-appeals-tabs-appeals').text('Обращения (' + count_appeals + ')')
						}

						// console.log("success")
						parent.css({'margin-left' : '105%'})

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});

							setTimeout(function () {
								parent.remove();
								if ($('#infoblock-appeals .appeals .list-block').length == 0) {
									removeNotification('#infoblock-appeals-tabs-appeals');
									removeNotification('#menu-appeals');

									$('#infoblock-appeals .appeals .list').append('<p class="empty">Нет обращений</p>');
									checkNotifications();
								}
							}, 300)
						}, 400)

						$('#infoblock-appeals .closed .list').prepend('<div class="list-block" id="appeal_' + id + '"> <p class="id">ID: ' + id + '</p> ' + sender_admin +' <h4>Тема</h4><p>' + theme + '</p><h4>Сообщение</h4><p>' + message + '</p><h4>Ответ</h4><p>' + answer + '<p> </div>');

					},
					error: function (result) {
						// console.log("failed: " + result)
					}
				})

		})

		// Удаление тикета
		$('#infoblock-appeals .list .list-block .button-1').click(function () {
			id = $(this).parent().parent().attr('id').replace('appeal_', '');
			parent = $(this).parent().parent();
			// console.log(id)

			$.ajax({
					url: "<?=$link?>/inc/support.php",
					type: "POST",
					cache: false,
					data: {
						id: id,
						type: 'delete-ticket'
					},
					success: function (result) {
						count_appeals = $('#infoblock-appeals .appeals .list-block').length;
						count_appeals--;
						if (count_appeals == 0) {
							$('#infoblock-appeals .tabs #infoblock-appeals-tabs-appeals').text('Обращения')
						} else {
							$('#infoblock-appeals .tabs #infoblock-appeals-tabs-appeals').text('Обращения (' + count_appeals + ')')
						}

						// console.log("success: " + result)
						parent.css({'margin-left' : '105%'})

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});

							setTimeout(function () {
								parent.remove();
								if ($('#infoblock-appeals .appeals .list-block').length == 0) {
									removeNotification('#infoblock-appeals-tabs-appeals');
									removeNotification('#menu-appeals');

									$('#infoblock-appeals .appeals .list').append('<p class="empty">Нет обращений</p>');
									checkNotifications();
								}
							}, 300)
						}, 400)
					},
					error: function (result) {
						// console.log("failed: " + result)
					}
				})

		})

		// Выбор статуса пользователя
		$('#infoblock-users .list .block .status button').click(function () {
			id = $(this).parent().parent().parent().attr('id');
			$('#infoblock-users .list #' + id +' .status button').removeClass('button-3').addClass('button-1')
			$('#infoblock-users .list #' + id +' .buttons p').text('Не сохранено!');
			$(this).addClass('button-3');
		})

		// Оповещение о том, что изменения не сохранены (флажок выбора gif)
		$('#infoblock-users .list .block input[name="usage_gif"]').click(function () {
			id = $(this).parent().parent().parent().parent().attr('id');
			console.log(id)
			$('#infoblock-users .list #' + id +' .buttons p').text('Не сохранено!');
		})

		// Оповещение о том, что изменения не сохранены (Поле причины блокировки)
		$('#infoblock-users .list .block input:eq(1)').on('input keyup', function () {
			id = $(this).parent().parent().attr('id');
			// console.log(id)
			$('#infoblock-users .list #' + id +' .buttons p').text('Не сохранено!');
		})








		if ($('#infoblock-education .statements .list-block').length != 0) {
			addNotification('#infoblock-education-tabs-statements');
			addNotification('#menu-education');
		}
		if ($('#infoblock-appeals .appeals .list .list-block').length != 0) {
			addNotification('#infoblock-appeals-tabs-appeals');
			addNotification('#menu-appeals');
		}
	</script>





</body>
<? include_once '../inc/footer.php'; ?>
</html>
<? endif; ?>