<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	// redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Поддержка</title>
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
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/support">Поддержка</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			<div class="menu">
				<ul>
					<li class="menu-appeal"><img draggable="false" src="<?= $link ?>/assets/img/icons/message-report.svg"> Обращение</li>
					<? if ($user_token != '') :?>
						<li class="menu-education"><img draggable="false" src="<?= $link ?>/assets/img/icons/school.svg">Учебное заведение</li>
					<? endif; ?>
				</ul>
			</div>
			
			<? if ($user_token != '') :?>
				<a href="<?= $link ?>/support/tickets">
					<button class="button-3">Мои обращения</button>
				</a>
			<? endif; ?>
		</div>

		<div class="form">
			<div class="appeal formBlock">
				<h1>Обращение в поддержку</h1>
				<form method="post" action="<?= $link ?>/inc/support.php">
					<p>Тема обращения</p>
					<input autocomplete="off" name="theme" value="<?= $theme?>" placeholder="">
					<br><br>
					<p>Электронная почта</p>
					<input autocomplete="off" name="email" value="<?= $user_email?>" placeholder="">
					<br><br>
					<p>Опишите свою проблему</p>
					<textarea autocomplete="off" name="message" placeholder=""></textarea>
					<input type="hidden" name="type" value="appeal">
				</form>
				<p class="message"></p>
				<button type="button" class="button-3">Отправить на рассмотрение</button>
			</div>

			<div class="education formBlock">
				<h1>Добавление учебного заведения</h1>
				<form method="post" action="<?= $link ?>/inc/support.php">
					<p>Электронная почта</p>
					<input autocomplete="off" name="email" value="<?= $user_email?>" placeholder="">
					<br><br>
					<p>Полное название (обязательно укажите город)</p>
					<textarea autocomplete="off" name="title" value="<?= $theme?>" placeholder="Например: Пермский Государственный Национально-Исследовательский Университет"></textarea>
					<br><br>
					<p>Короткое название</p>
					<input autocomplete="off" name="short_title" placeholder="Например: ПГНИУ">
					<input type="hidden" name="type" value="education">
				</form>
				<p class="message"></p>
				<button type="button" class="button-3">Отправить на рассмотрение</button>
			</div>
		</div>
		<!-- <p class="info">Ответ от службы поддержки придёт на почту и в уведомления вашего аккаунта. Обычно, это занимает не более суток.</p> -->
	</div>


		
	<script type="text/javascript">
		// Открытие вкладки
		function openTab (classname) {
			$('.main .menu ul li').removeClass('selected');
			$('.main .menu ul .menu-' + classname).addClass('selected');

			$('.main .form .formBlock').css({'display' : 'none'});
			$('.main .form .' + classname).css({'display' : 'inline'});
		}

		// Клик на пункт меню
		$('.main .menu ul li').click(function () {
			if (!$(this).hasClass('selected')) {
				openTab($(this).attr("class").replace('menu-', ''));
			}
		})

		$('.appeal .button-3').click(function () {
			let theme = $('.appeal input[name="theme"]').val();
			let email = $('.appeal input[name="email"]').val();
			let message = $('.appeal textarea').val();

			if (theme.replace(' ', '') == '' || email.replace(' ', '') == '' || message.replace(' ', '') == '') {
				return;
			}
			if (theme.length > 2000) {
				$('.form .appeal .message').text('Тема обращения должна быть короче 2000 символов');
				return;
			} if (email.length > 300) {
				$('.form .appeal .message').text('Электронная почта должна быть короче 300 символов');
				return;
			} if (message.length > 4000) {
				$('.form .appeal .message').text('Текст обращения должен быть короче 4000 символов');
				return;
			}
			$('.appeal form').submit();
		})

		$('.education .button-3').click(function () {
			let email = $('.education input[name="email"]').val();
			let title = $('.education textarea[name="title"]').val();
			let short_title = $('.education input[name="short_title"]').val();

			if (email.replace(' ', '') == '' || title.replace(' ', '') == '' || short_title.replace(' ', '') == '') {
				return;
			}
			$('.education form').submit();
		})

		<?
			if ($_GET['v'] == 'add_new_uni') {
				echo "openTab('education')";
			} else {
				echo "openTab('appeal')";
			}
		?>
	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
</body>
</html>