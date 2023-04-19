<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>Настройки</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="mobile.css">
</head>
<body>

	<?
		include_once '../inc/header.php'; // Шапка
		include_once '../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?= $link ?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?= $link ?>/settings">Настройки</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			<div class="menu">
				<ul>
					<li class="menu-setts-1"><img draggable="false" src="<?= $link ?>/assets/img/icons/message-report.svg">1 вкладка</li>
					<li class="menu-setts-1"><img draggable="false" src="<?= $link ?>/assets/img/icons/message-report.svg">3 вкладка</li>
					<li class="menu-setts-1"><img draggable="false" src="<?= $link ?>/assets/img/icons/message-report.svg">4 вкладка</li>
				</ul>
			</div>
		</div>

		<div class="form">
			<div class="setts-1 formBlock">
				<h1>1 вкладка</h1>
			</div>

			<div class="setts-2 formBlock">
				<h1>2 вкладка</h1>
			</div>

			<div class="setts-3 formBlock">
				<h1>3 вкладка</h1>
			</div>
		</div>

		<div class="mobile_settings">
			<ul>
				<a href="<?= $link ?>/profile"><li>
					<img src="<?= $link ?>/assets/img/icons/user-circle.svg" alt="">
					Профиль
				</li></a>

				<a href="<?= $link ?>/friends"><li>
					<img src="<?= $link ?>/assets/img/icons/users.svg" alt="">
					Друзья
				</li></a>

				<a href="#"><li>
					<img src="<?= $link ?>/assets/img/icons/settings.svg" alt="">
					Настройки
				</li></a>

				<a href="#"><li>
					<img src="<?= $link ?>/assets/img/icons/info-circle.svg" alt="">
					О нас
				</li></a>

				<a href="<?= $link ?>/support"><li>
					<img src="<?= $link ?>/assets/img/icons/help.svg" alt="">
					Поддрежка
				</li></a>

				<a href="<?= $link ?>/inc/logout.php"><li>
					<img src="<?= $link ?>/assets/img/icons/logout.svg" alt="">
					Выйти из аккаунта
				</li></a>
			</ul>
		</div>

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
				echo "openTab('setts-1')";
			}
		?>
	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('settings');
	</script>
</body>
</html>