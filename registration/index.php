<?
	header('Location: ' . $link . '/authorization');
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/regirect.php';
?>
	<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Регистрация</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<?
		include_once '../inc/head.php';
	?>
	
</head>
<body>
	<a class="back" href="../"><img src="<?= $link ?>/assets/img/icons/arrow-left.svg">FINDCREEK</a>
	<div class="form">
		<form class="log" method="post" action="../inc/registration.php">
			<h1>Регистрация</h1>
			<br>
			<div class="rows">
				<div class="row">
					<div>
						<label>Имя</label>
						<input autocomplete="off" type="" name="first-name" placeholder="Имя">
					</div>

					<div>
						<label>Фамилия</label>
						<input autocomplete="off" type="email" name="last-name" placeholder="Фамилия">
					</div>
				</div>

				<div>
					<label>Почта</label>
					<input type="email" name="email" placeholder="Электронная почта">
				</div>
				
				<div>
					<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
					<label>Пароль</label>
					<input type="password" autocomplete="new-password" name="password" placeholder="Пароль">
				</div>

				<div>
					<img src="<?= $link ?>/assets/img/icons/eye-off.svg">
					<label>Пароль повторно</label>
					<input type="password" autocomplete="new-password" name="second-password" placeholder="Подтверждение пароля">
				</div>
			</div>

			<p></p>
			<center>
				<button type="button" class="button-3">Зарегистрироваться</button>
			</center>
			<br>
			<h4>Уже зарегистрированы? - <a href=" <?= $link ?>/authorization">Войдите</a></h4>
		</form>
	</div>



	<?
		include_once '../inc/footer.php';
	?>

	<script type="text/javascript">
		function animateInputError (name) {
			$('.form input[name="' + name + '"]').css({"background-color" : "rgba(255,30,10, .07)"});
			setTimeout(function () {
				$('.form input[name="' + name + '"]').css({"background-color" : "unset"})
			}, 1000);
		}

		$('.form .rows div img').click(function () {
			input_type = $(this).parent().children('input').attr('type');
			if (input_type == 'password') {
				$(this).parent().children('input').attr('type', 'text');
				$(this).attr('src', '<?= $link ?>/assets/img/icons/eye.svg')
			} else {
				$(this).parent().children('input').attr('type', 'password');
				$(this).attr('src', '<?= $link ?>/assets/img/icons/eye-off.svg')
			}
		})

		$('form button').click(function () {
			let first_name = $('.form input[name="first-name"]').val(); 
			let last_name = $('.form input[name="last-name"]').val();
			let password = $('.form input[name="password"]').val();
			let email = $('.form input[name="email"]').val(); 
			let second_password = $('.form input[name="second-password"]').val();

			
			if (first_name.length == 0) {
				// $('form p').text('Вы должны указать своё имя');
				animateInputError('first-name');
				return;
			}
			if (first_name.length > 255) {
				// $('form p').text('Длина имени не должна превышать 255 символов');
				animateInputError('first-name');
				return;
			}

			if (last_name.length == 0) {
				// $('form p').text('Вы должны указать свою фамилию');
				animateInputError('last-name');
				return;
			}
			if (last_name.length > 255) {
				// $('form p').text('Длина фамилии не должна превышать 255 символов');
				animateInputError('last-name');
				return;
			}

			if (email.length == 0) {
				// $('form p').text('Вы должны указать свою электронную почту');
				animateInputError('email');
				return;
			}
			if (email.length > 4000) {
				// $('form p').text('Длина почты не должна превышать 4000 символов');
				animateInputError('email');
				return;
			}
			var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			if (!pattern.test(email)) {
				// $('#valid').text('Верно');
				// $('form p').text('Некорректная электронная почта');
				animateInputError('email');
				return;
			}


			if (password.length == 0) {
				// $('form p').text('Вы должны указать/придумать пароль');
				animateInputError('password');
				return;
			}
			if (password.length < 8) {
				// $('form p').text('Пароль должен содержать минимум 8 символов');
				animateInputError('password');
				return;
			}
			if (second_password.length == 0) {
				// $('form p').text('Вы должны повторить указанный пароль');
				animateInputError('second-password');
				return;
			}
			if (second_password != password) {
				// $('form p').text('Пароли не совпадают');
				animateInputError('password');
				animateInputError('second-password');
				return;
			}
			    
			
			// $('.form').css({'transform' : 'scale(100)'});
			// console.log('AJAX');
			$.ajax({
				type: "POST",
				url: "<?= $link ?>/inc/registration.php",
				cache: false,
				data: {
					email: email,
					type: 'check'
				},
				success: function (html) {
					// console.log('result: ' + html)
					if (html == 'true') {
						
						$('form p').text('Пользователь с такой почтой уже зарегистрирован');
						animateInputError('email');
					} else {
						$('form').submit();
					}
				}
			})
			history.pushState(null, null, location.href);
			window.onpopstate = function(event) {
			    history.go(1);
			};
		})
		// Фокусировка на input при нажатии на картинку
		$('.col-2 span img').click(function () {
			$('.col-2 span input').focus();
		})
		
		// Показ списка УУ при нажатии на input
		$('.col-2 span input').on('focus', function () {
			$('.col-2 span').addClass('education-show');
		})

		// Поиск УУ
		$('.col-2 span input').on('input keyup', function () {
			// console.log(1)
			$('.col-2 span ul li').css({'display' : 'block'})
			$('.col-2 span ul h5').remove();
			$('.col-2 span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.col-2 span ul li').length; li_eq++) {
					if ( $('.col-2 span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.col-2 span ul li:eq(' + li_eq + ')').css({'display': 'none'});
					} else {
						result++;
					}
				}
				if (result == 0) {
					$('.col-2 span ul').append('<h5>Нет результатов! </h5>');
					$('.col-2 span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.col-2 span ul, .col-2 span ul li').click(function () {
			$('.col-2 span input').focus();
			// $('.col-2 span').addClass('education-show');
		})

		$('.col-2 span ul li').click(function () {
			$('.col-2 span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.col-2 span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.col-2 span :focus').length === 0) {
	    			$('.col-2 span').removeClass('education-show');
	    		}
		    }, 100)
		});



		// Фокусировка на input при нажатии на картинку
		$('.col-1 span img').click(function () {
			$('.col-1 span input').focus();
		})
		
		// Показ списка Городов при нажатии на input
		$('.col-1 span input').on('focus', function () {
			$('.col-1 span').addClass('cities-show');
		})

		// Поиск Города
		$('.col-1 span input').on('input keyup', function () {
			// console.log(1)
			$('.col-1 span ul li').css({'display' : 'block'})
			$('.col-1 span ul h5').remove();
			$('.col-1 span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.col-1 span ul li').length; li_eq++) {
					if ( $('.col-1 span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.col-1 span ul li:eq(' + li_eq + ')').css({'display': 'none'});
					} else {
						result++;
					}
				}
				if (result == 0) {
					$('.col-1 span ul').append('<h5>Нет результатов! </h5>');
					$('.col-1 span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.col-1 span ul, .col-1 span ul li').click(function () {
			$('.col-1 span input').focus();
			// $('.col-1 span').addClass('cities-show');
		})

		$('.col-1 span ul li').click(function () {
			$('.col-1 span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.col-1 span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.col-1 span :focus').length === 0) {
	    			$('.col-1 span').removeClass('cities-show');
	    		}
		    }, 100)
		});
	</script>
</body>
</html>