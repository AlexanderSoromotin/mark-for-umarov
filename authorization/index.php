<?	
	header("Location: " . $link . '/find-students');
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	// include_once '../inc/redirect.php';
	$styles_ver = '?v=2';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Авторизация</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $styles_ver ?>">
	<link rel="stylesheet" type="text/css" href="mobile.css<?= $styles_ver ?>">
	<?
		include_once '../inc/head.php';
	?>
</head>
<body>
	<script type="text/javascript">
		let vw = window.innerWidth * 0.01;
        document.documentElement.style.setProperty('--vw', `${vw}px`);

	</script>
	<div class="form desktop_form">
		<form class="log" method="post" action="../inc/authorization.php">
			<h1>Авторизация</h1>

			<br>
			<div>
				<label>Почта</label>
				<input type="email" name="email" autocomplete="on" placeholder="Введи свою почту">
			</div>

			
			<div>
				<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
				<label>Пароль</label>
				<input type="password" autocomplete="on" name="password" placeholder="Введи свой пароль">
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3">Войти</button>
			</center>
			<br>
			<h4>Нет аккаунта? <a href=" <?= $link ?>/registration">Зарегистрируйся</a></h4>
		</form>
	</div>

	<!-- <div class="form mobile_form">
		<div class="auth_step check_email">
			<h1>Рады видеть тебя на FINDCREEK!</h1>
		
			<div>
				<input type="email" name="email" autocomplete="on" placeholder="Введи свою почту">
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3 check_email_button">Продолжить<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>
			</center>

		</div>

		<div class="auth_step enter hidden">
			<h1>Мы с нетерпением ждали твоего возвращения!</h1>
		
			<div>
				<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
				<input type="password" name="password" autocomplete="on" placeholder="Введи свой пароль">
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3 check_email_button">Войти<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>
			</center>

		</div>

		<div class="auth_step registration hidden">
			<h1>Кажется, ты новенький с:</h1>
			
			<div>
				<input type="text" name="first-name" autocomplete="on" placeholder="Введи своё имя">
			</div>
			<div>
				<input type="text" name="last-name" autocomplete="on" placeholder="Введи свою фамилию">
			</div>
			<div>
				<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
				<input type="password" name="password" autocomplete="on" placeholder="Придумай пароль">
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3 check_email_button">Зарегистрироваться<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>
			</center>

		</div>
	</div> -->

	<a class="back" href="../"><img src="<?= $link ?>/assets/img/icons/arrow-left.svg">FINDCREEK</a>


	<?
		include_once '../inc/footer.php';
	?>

	<script type="text/javascript">
		$('.form p').text('<?= $_COOKIE['auth-error']?>');
		$('.form input[name="email"]').val('<?= $_COOKIE['auth-email']?>');
		<?
			// setcookie('auth-error', '', time() * 0, "/");
			// setcookie('auth-email', '', time() * 0, "/");
		?>

		function animateInputError (name) {
			$('.desktop_form input[name="' + name + '"]').css({"background-color" : "rgba(255,30,10, .07)"});
			setTimeout(function () {
				$('.desktop_form input[name="' + name + '"]').css({"background-color" : "unset"})
			}, 1000);
		}

		$('.form div img').click(function () {
			input_type = $(this).parent().children('input').attr('type');
			if (input_type == 'password') {
				$(this).parent().children('input').attr('type', 'text');
				$(this).attr('src', '<?= $link ?>/assets/img/icons/eye.svg')
			} else {
				$(this).parent().children('input').attr('type', 'password');
				$(this).attr('src', '<?= $link ?>/assets/img/icons/eye-off.svg')
			}
		})

		$('.check_email_button').click(function () {
			console.log($('.check_email input').val())
			if ($('.check_email input').val().replace(' ', '') == '') {
				$('.check_email input').css({'border-bottom': '5px solid rgb(231,154,145)'});

				setTimeout(function () {
					$('.check_email input').css({'border-bottom': '1px solid #ccc'});
				}, 4000)
			} else {
				$('.check_email').addClass('deleted');
				// $('.enter').removeClass('hidden');
				$('.registration').removeClass('hidden');
			}
		})

		$('.desktop_form button').click(function () {
			let email = $('.desktop_form input[name="email"]').val(); 
			let password = $('.desktop_form input[name="password"]').val();

			$('desktop_form p').text('');

			if (email.length == 0) {
				$('desktop_form p').text('Вы должны указать электронную почту');
				animateInputError('email');
				return;
			}
			if (email.length > 0 && email.length < 4) {
				$('desktop_form p').text('Недопустимая почта');
				animateInputError('email');
				return;
			}
			if (email.length > 255) {
				$('desktop_form p').text('Длина почты слишком велика');
				animateInputError('email');
				return;
			}
			if (password.length == 0) {
				$('desktop_form p').text('Вы должны указать пароль');
				animateInputError('password');
				return;
			}
			if (password.length > 255) {
				$('desktop_form p').text('Длина почты слишком велика');
				animateInputError('password');
				return;
			}
			$.ajax({
				type: "POST",
				url: "<?= $link ?>/inc/authorization.php",
				cache: false,
				data: {
					email: email,
					type: 'check'
				},
				success: function (result) {
					console.log(result)
					if (result == 'false') {
						
						$('.desktop_form p').text('Пользователь с такой почтой не зарегистрирован');
						animateInputError('email');
					} else {
						$('.desktop_form form').submit();
					}
				}
			})
		})

		history.pushState(null, null, location.href);
		window.onpopstate = function(event) {
		    history.go(1);
		};

		// selectTab('messenger');
		
		$(document).bind("drop dragover", function(e){
		    if(e.target.type != "file"){
		        e.preventDefault();
		    }
		});
	</script>
</body>
</html>