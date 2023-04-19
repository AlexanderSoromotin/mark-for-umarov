<?
	include_once '../inc/config.php';
	$cache_ver = '?v=11';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>MARK: Авторизация</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<!-- <link rel="stylesheet" type="text/css" href="mobile.css<?= $cache_ver ?>"> -->
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<?
		include_once '../inc/head.php';
	?>
</head>
<body>
	<script type="text/javascript">
		let vw = window.innerWidth * 0.01;
        document.documentElement.style.setProperty('--vw', `${vw}px`);
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vw', `${vw}px`);

	</script>

	<?
		$google_oauth_params = array(
			'client_id'     => '483285812826-804r69bk46vk1kvhr3htqova7hn3753o.apps.googleusercontent.com',
			'redirect_uri'  => 'https://mark.findcreek.com/api/web_authorization.php?provider=google',
			'response_type' => 'code',
			'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
			'state'         => '111'
		);
 
		$auth_with_google_link = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($google_oauth_params));

		$vk_auth_params = array(
			'client_id'     => '8173506',
			'redirect_uri'  => 'https://mark.findcreek.com/api/web_authorization.php?provider=vk',
			'scope'         => 'email',
			'response_type' => 'code',
			'state'         => '222'
		);
 
		$auth_with_vk_link = 'https://oauth.vk.com/authorize?' . urldecode(http_build_query($vk_auth_params));


	?>

	<div class="form mobile_form">
		<div class="auth_step check_email">
			<h1>Рады видеть тебя в системе MARK!</h1>
		
			<div>
				<input type="email" name="email" autocomplete="on" placeholder="Введи свою почту">
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3 check_email_button">Продолжить<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>
			</center>

			<div class="auth_with">
				<div class="title">Авторизация через платформы</div>
				<div class="services">
					<a href="<?= $auth_with_google_link ?>">
						<div class="service service_google">
							<img src="<?= $link ?>/assets/img/services/google.png">
						</div>
					</a>

					<a href="<?= $auth_with_vk_link ?>">
						<div class="service service_vk">
							<img src="<?= $link ?>/assets/img/services/vk.png">
						</div>
					</a>
				</div>
			</div>
		</div>


		<div class="auth_step enter hidden">
			<form method="post" action="<?= $link ?>/api/web_authorization.php">
				<input type="hidden" name="step" value="authorization">
				<input type="hidden" name="email" value="">
				<h1>Мы с нетерпением ждали твоего возвращения!</h1>
		
				<div>
					<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
					<input class="auth_password_input" type="password" name="password" autocomplete="on" placeholder="Введи свой пароль">
				</div>

				<p></p>
				<center>
					<button type="button" class="button-3 auth">Войти<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>

					<div class="recovery_password">
						<a href="<?= $link ?>/recovery-password?email=">Я не помню пароль</a>
					</div>
				</center>
				
			</form>

		</div>

		<div class="auth_step registration hidden">
			<form method="post" action="<?= $link ?>/api/web_authorization.php">
				<input type="hidden" name="step" value="registration">
				<input type="hidden" name="email" value="">
				<h1>Кажется, ты новенький с:</h1>
			
				<div>
					<input type="text" name="first-name" autocomplete="on" placeholder="Введи своё имя">
				</div>
				<div>
					<input type="text" name="surname" autocomplete="on" placeholder="Введи свою фамилию">
				</div>
				<div>
					<img src="<?= $link ?>/assets/img/icons/eye.svg">
					<input class="password_input" type="text" name="password" autocomplete="on" placeholder="Придумай пароль">
				</div>

				<p></p>
				<center>
					<button type="button" class="button-3 register">Зарегистрироваться<img src="<?= $link ?>/assets/img/icons/arrow-right.svg"></button>
				</center>
			</form>

		</div>
	</div>

	<!-- <a class="back" href="../"><img src="<?= $link ?>/assets/img/icons/arrow-left.svg">FINDCREEK</a> -->

	<script type="text/javascript">
		$('.check_email p').text('<?= $_COOKIE['findstudents-auth-error']?>');
		$('.check_email	 input[name="email"]').val('<?= $_COOKIE['findstudents-auth-error-email']?>');

		function animateInputError (name) {
			$('input[name="' + name  + '"]').css({'border-bottom': '1px solid rgb(231,154,145)'});

				setTimeout(function () {
					$('input[name="' + name  + '"]').css({'border-bottom': '1px solid #ccc'});
				}, 2000)
		}

		$('.form div img').click(function () {
			if ($(this).parents('.auth_with').length != 0) {
				return;
			}
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
			let pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			let email = $('.check_email input').val();

			if (email.replace(' ', '') == '' || !pattern.test(email)) {
				animateInputError('email')
				return;
			}

			$.ajax({
				url: '<?= $link ?>/api/web_authorization.php',
				type: 'POST',
				cache: false,
				data: {
					step: 'check_email',
					email: $('input[name="email"]').val()
				},
				success: function (result) {
					console.log(result)
					if (result != '') {
						result = JSON.parse(result);

						if (result['response'] == 'registered email') {
							$('.check_email').addClass('deleted');
							$('.enter').removeClass('hidden');
							$('.enter input[name="email"]').val(email);
							$('.recovery_password a').attr('href', "<?= $link ?>/recovery-password?email=" + email);
						}
						if (result['response'] == 'unregistered email') {
							$('.check_email').addClass('deleted');
							$('.registration').removeClass('hidden');
							$('.registration input[name="email"]').val(email);
						}
					}
				}
			})				
		})

		$('.register').click(function () {
			name = $('input[name="first-name"]').val()
			surname = $('input[name="surname"]').val()
			password = $('.password_input').val()

			if (name.length < 2) {
				animateInputError('first-name');
				return;
			}

			if (surname.length < 2) {
				animateInputError('surname');
				return;
			}

			if (password.length < 2) {
				animateInputError('password');
				return;
			}
			$('.registration form').submit();

		})

		$('.auth').click(function () {
			password = $('.auth_password_input').val()

			if (password.length < 2) {
				animateInputError('password');
				return;
			}
			$('.enter form').submit();

		})

		history.pushState(null, null, location.href);
		window.onpopstate = function(event) {
		    history.go(1);
		};

	</script>
</body>
</html>