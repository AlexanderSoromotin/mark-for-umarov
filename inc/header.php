<header>
	<div class="header">
		<div class="hamburger_menu_button">
			<div></div>
			<div></div>
			<div></div>
		</div>
		<div class="col-1">
			<h1 class=""><a href="<?= $link ?>/">FINDCREEK</a></h1>

			<? if ($user_status == 'Admin'): ?>
			<div class="links">
				
				<!-- <a href="<?= $link ?>/interest-groups">Лента</a> -->
				<a href="<?= $link ?>/forum">Форум</a>
				<a href="<?= $link ?>/interest-groups">Интересы</a>
				<!-- <a href="<?= $link ?>/terms">Правила</a> -->
				<a href="<?= $link ?>/messenger" class="messenger">Мессенджер</a>
				<a href="<?= $link ?>/find-students">Студенты</a>
				<!-- <a href="">Соц.сети</a> -->
				<!-- <a href="">О нас</a> -->
			</div>
			<? else: ?>
			<div class="links">
				
				<!-- <a href="<?= $link ?>/interest-groups">Лента</a> -->
				<a href="<?= $link ?>">xxxxx</a>
				<a href="<?= $link ?>">xxxxxxxx</a>
				<!-- <a href="<?= $link ?>/terms">Правила</a> -->
				<a href="<?= $link ?>">xxxxxxxxx</a>
				<!-- <a href="">Соц.сети</a> -->
				<!-- <a href="">О нас</a> -->
			</div>
			<? endif; ?>

		</div>

		<div class="col-2">
			<?
				if (!$userLogged) :	
			?>
			<!-- <a href="<?= $link ?>/authorization" class="button-2">Войти</a> -->
			<!-- <a href="<?= $link ?>/registration" class="button-4">Создать аккаунт</a> -->
			<div class="auth_closed">Авторизация недоступна</div>
			<?
				else :
			?>
			<div class="menu">
				<div class="name">
					<h3><?= $user_last_name . ' ' . mb_substr($user_first_name, 0, 1) . '.' ?></h3>
					<h5><?= $user_email ?></h5>
				</div>
				<div class="photo">
					<div class="image">
						<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
					</div>
				</div>
				<img draggable="false" class="arrow-down" src="<?= $link ?>/assets/img/icons/chevron-down.svg">

				<!-- Количество вкладок в меню -->
				<? $menu_items = 5; ?>
				<ul>
					<a href="<?= $link ?>/profile">
						<li class="">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/user.svg">
							Профиль
						</li>
					</a>
					<a href="<?= $link ?>/profile/friends/">
						<li class="profile-friends <? if (mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `incoming_id` = '$user_id'") -> num_rows != 0) { echo 'notification';} ?>">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/users.svg">
							Друзья
						</li>
					</a>
					<!-- <a href="<?= $link ?>/messenger">
						<li class="messenger">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/messages.svg">
							Мессенджер
						</li>
					</a> -->
					<a href="<?= $link ?>/support/tickets">
						<li class="<? if (mysqli_query($connection, "SELECT * FROM `support_tickets` WHERE `appealer_id` = '$user_id' and `user_viewed` = 0 and `status` = 'Closed'") -> num_rows != 0) { echo 'notification';} ?>">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/message-report.svg">
							Поддержка
						</li>
					</a>
					<a href="<?= $link ?>/settings">
						<li class="">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/settings.svg">
							Настройки
						</li>
					</a>
					 <? if (false) : ?> 
						<? 
							// if $user_status == "Admin"
							$menu_items++; 
						?>
						<a href="<?= $link ?>/admin">
							<li>
								<img draggable="false" src="<?= $link ?>/assets/img/icons/database.svg">
								Админ-панель
							</li>
						</a>
					 <? endif; ?>

					<a href="<?= $link ?>/inc/logout.php">
						<li class="logout">
							<!-- <img src="<?= $link ?>/assets/img/icons/chevron-down.svg"> -->
							Выйти из аккаунта
						</li>
					</a>

				</ul>
			</div>

			<a href="<?= $link ?>/profile">
				<div class="mobile_menu menu">
					<div class="name">
						<h3><?= $user_last_name . ' ' . mb_substr($user_first_name, 0, 1) . '.' ?></h3>
						<h5><?= $user_email ?></h5>
					</div>
					<div class="photo">
						<div class="image">
							<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
						</div>
					</div>
				</div>
			</a>
			
			<div class="notifications">
				<div class="button">
					<img class="notifications-icon" draggable="false" src="<?= $link ?>/assets/img/icons/bell.svg">
				</div>
				<div class="notifications-div">
					<ul>
						<li class="empty">
							<h4>Тут пока ничего нет :с</h4>
						</li>
						
					</ul>
					<a class="notifications-view-all" href="<?= $link ?>/notifications">
						<button class="button-3">Показать все</button>
					</a>
				</div>
			</div>

			<? if ($user_status == "Admin") : ?>
				<?
					$application_add_education = mysqli_query($connection, "SELECT `id` FROM `application_add_education` WHERE `status` = 'Checking'");
					$support_tickets = mysqli_query($connection, "SELECT `id` FROM `support_tickets` WHERE `status` = 'Checking' ");

					$adminPanel_classname = '';
					if ($application_add_education -> num_rows != 0 or $support_tickets -> num_rows != 0 ) {
						$adminPanel_classname = 'notification';
					}
				?>
				<div class="right-menu">
					<!-- <div class="notifications">
						<img draggable="false" src="<?= $link ?>/assets/img/icons/bell.svg">
					</div> -->

					<a href="<?= $link ?>/admin">
						<div class="admin-panel <?= $adminPanel_classname ?>">
							<img draggable="false" src="<?= $link ?>/assets/img/icons/database.svg">
						</div>
					</a>

					
				</div>
				<? endif; ?>

			<? endif; ?>


			
		</div>

	</div>

	<div class="media_content_viewer">
		<div class="background"></div>

		<div class="image_viewer">
			<div class="close_media_content_viewer">
				<img src="<?= $link ?>/assets/img/icons/x.svg">
			</div>

			<div class="content">
				<div class="slide_button slide_to_right">
					<img src="<?= $link ?>/assets/img/icons/chevron-right.svg">
				</div>
				<div class="slide_button slide_to_left">
					<img src="<?= $link ?>/assets/img/icons/chevron-left.svg">
				</div>
			</div>
			<div class="info">
				<a href="">
					<div class="col-1">
						<div class="image">
							<img src="https://assets.website-files.com/5f204aba8e0f187e7fb85a87/5f210a533185e7434d9efcab_hero%20img.jpg">
						</div>
					</div>
				</a>
				<div class="col-2">
					<div class="user_info">
						<p class="name"><a href=""></a></p>
						<p class="date"></p>
					</div>
					<div class="control">
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		const link = '<?= $link ?>';

		function slideToLeft_imageViewer () {
			if ($('.image_viewer .slide').length > 1) {
				display_slide_eq = $('.image_viewer .display_slide').index() - 2;
				next_slide = display_slide_eq + 1;

				last_slide_eq = $('.image_viewer .slide').length - 1;

				if (last_slide_eq == display_slide_eq) {
					next_slide = 0;
				}

				$('.image_viewer .display_slide').removeClass('display_slide').css({"animation" : "none"});;
				$('.image_viewer .slide:eq(' + next_slide + ')').addClass('display_slide').addClass('display_slide').css({"animation" : "showFromRight .3s forwards"})

				$('.image_viewer .info .col-2 .control').text($('.image_viewer .display_slide').index() - 1 + ' из ' + $('.image_viewer .slide').length)
			}
			
		}

		function slideToRight_imageViewer () {
			if ($('.image_viewer .slide').length > 1) {
				display_slide_eq = $('.image_viewer .display_slide').index() - 2;
				prev_slide = display_slide_eq - 1;

				last_slide_eq = $('.image_viewer .slide').length - 1;

				if (prev_slide == -1) {
					prev_slide = last_slide_eq;
				}

				$('.image_viewer .display_slide').removeClass('display_slide').css({"animation" : "none"});
				$('.image_viewer .slide:eq(' + prev_slide + ')').addClass('display_slide').css({"animation" : "showFromLeft .3s forwards"})

				$('.image_viewer .info .col-2 .control').text($('.image_viewer .display_slide').index() - 1 + ' из ' + $('.image_viewer .slide').length)
			}
			
		}

		$('.image_viewer .slide_to_right').click(function () {
			slideToLeft_imageViewer();
			file_name = $('.image_viewer .display_slide').attr('alt');
			$('.image_viewer .date a').text(file_name).attr('href', $('.image_viewer .display_slide img').attr('src')).attr('download', file_name);
		})
		$('.image_viewer .slide_to_left').click(function () {
			slideToRight_imageViewer();
			file_name = $('.image_viewer .display_slide').attr('alt');
			$('.image_viewer .date a').text(file_name).attr('href', $('.image_viewer .display_slide img').attr('src')).attr('download', file_name);
		})

		function openImageViewer (array, start_eq) {
			$('.image_viewer .date').text('').append(array['date'] + ' загрузил' + array['user_data']['sex'] + ' файл <a></a>');
			$('.image_viewer .name a').text(array['user_data']['last_name'] + ' ' + array['user_data']['first_name']);
			$('.image_viewer .image img').attr('src', array['user_data']['photo']);
			$('.image_viewer .image img').attr('style', array['user_data']['photo_style']['ox_oy'] + 'transform: scale(' + array['user_data']['photo_style']['scale'] + ')');

			$('.media_content_viewer .image_viewer .slide').remove();

			image_count = 0;
			console.log("array['media']", array['media'])
			console.log(array);
			for (image_index in array['media']) {
				console.log()
				if ("png bmp ecw gif ico ilbm jpeg mrsid pcx tga tiff webp xbm xps rla rpf pnm jpg jfif".indexOf(array['media'][image_index]['mime']) != -1) {
					image_data = array['media'][image_index];
					image_count++;

					$('.media_content_viewer .image_viewer .content').append('<div class="slide" alt="' + image_data['name'] + '"><img src="' + link + '/uploads/user_files/' + image_data['server_name'] + '"></div>');
				}
			}

			if (image_count < 2) {
				$('.image_viewer .slide_button').css({"display" : "none"})
			} else {
				$('.image_viewer .slide_button').css({"display" : "flex"})
			}

			$('.media_content_viewer .image_viewer .content .slide[alt="' + start_eq + '"]').addClass('display_slide');
			$('.image_viewer .date a').text(start_eq).attr('href', $('.image_viewer .display_slide img').attr('src')).attr('download', start_eq);

			$('.image_viewer .info .col-2 .control').text($('.image_viewer .display_slide').index() - 1 + ' из ' + $('.image_viewer .slide').length)
			

			$('.media_content_viewer').addClass('open_media_content_viewer')
			setTimeout(function () {
				$('.image_viewer').addClass('open_image_viewer')
			}, 100)

		}

		$('.media_content_viewer .background, .media_content_viewer .close_media_content_viewer').click(function () {
			$('.image_viewer').removeClass('open_image_viewer')
			$('.media_content_viewer').removeClass('open_media_content_viewer')
		})

		$('body').bind('keyup', function (e) {
			let keyCode = e.which;
			// console.log(keyCode)

			if (keyCode == 27) {
				$('.image_viewer').removeClass('open_image_viewer')
				$('.media_content_viewer').removeClass('open_media_content_viewer')
			}

			if ($('.open_image_viewer').length != 0) {
				if (keyCode == 39 || keyCode == 102) {
					// Листание вправо
					slideToLeft_imageViewer();
					file_name = $('.image_viewer .display_slide').attr('alt');
					$('.image_viewer .date a').text(file_name).attr('href', $('.image_viewer .display_slide img').attr('src')).attr('download', file_name);
				}

				if (keyCode == 37 || keyCode == 100) {
					// Листание влево
					slideToRight_imageViewer();
					file_name = $('.image_viewer .display_slide').attr('alt');
					$('.image_viewer .date a').text(file_name).attr('href', $('.image_viewer .display_slide img').attr('src')).attr('download', file_name);
				}
			}
		})
	</script>

	

	<style type="text/css">
		
		header .menu-opened ul {
			height: <?= $menu_items * 45 - 6 ?>px !important;
		}
	</style>
</header>

<div class="hamburger_menu">
	<div class="background">
		
	</div>
	
	<div class="hamburger_menu_tabs">
		<ul>
			<li><a href="<?= $link ?>/forum">Форум</a></li>
			<li><a href="<?= $link ?>/interest-groups">Группы интересов</a></li>
			<li><a href="<?= $link ?>/terms">Правила площадки</a></li>
			<li><a href="<?= $link ?>/#">О нас</a></li>
			<li><a href="<?= $link ?>/support">Поддержка</a></li>
		</ul>
	</div>

</div>

<script type="text/javascript"> 
	var notificationAudio = new Howl({
      src: ['<?= $link ?>/assets/audio/message.wav'],
      volume: 3
    });

    function playNotificationAudio () {
    	notificationAudio.play();
    }

    $('.hamburger_menu_button').click(function () {
		if ($(this).hasClass('hamburger_menu_active_button')) {
			$(this).removeClass('hamburger_menu_active_button');
			$('.hamburger_menu').removeClass('hamburger_menu_active');
		} else {
			$(this).addClass('hamburger_menu_active_button');
			$('.hamburger_menu').addClass('hamburger_menu_active');
		}
	})

    // messageAudio.play()

	function selectTab (tabname) {
		if (tabname == 'forum') {
			$('header .header .links a:eq(1)').addClass('selected');
		}
		if (tabname == 'interest-groups') {
			$('header .header .links a:eq(0)').addClass('selected');
		}
		// if (tabname == 'terms') {
		// 	$('header .header .links a:eq(2)').addClass('selected');
		// }
		if (tabname == 'messenger') {
			$('header .header .links a:eq(2)').addClass('selected');
		}
	}
	






	<? if ($_COOKIE['token'] != '') : ?>
		<?
			$url = $_SERVER['REQUEST_URI'];
			if (strpos($url, '/messenger/') === false) :
		?>
	<? endif; ?>

	
	


	$(document).click(function(event) {
	  	if ($(event.target).parents('.menu').length == 0 && !$(event.target).hasClass('menu')) {
	  		$('header .menu').removeClass('menu-opened');
	  	}

	  	if ($(event.target).parents('.notifications').length == 0) {
	  		$('header .notifications').removeClass('notifications-opened');
	  	}

	  	if ($(event.target).parents('.drop-down-parent').length == 0) {
	  		$('.profile .drop-down-parent').removeClass('drop-down-opened');
	  	}
	});

	$('header .menu').click(function () {
		if ($('header .menu').hasClass('menu-opened')) {
			$('header .menu').removeClass('menu-opened');
		} else {
			$('header .menu').addClass('menu-opened');
		}
	})

	$('header .notifications img').click(function () {
		// $('header .notifications').removeClass('notification');
		
		if ($('header .notifications').hasClass('notifications-opened')) {
			$('header .notifications').removeClass('notifications-opened');
		} else {
			$('header .notifications').addClass('notifications-opened');

			if ($('header .col-2 .notifications-div ul .not-viewed').length != 0) {
				$.ajax({
					url: '<?= $link ?>/inc/notifications.php',
					type: 'POST',
					cache: false,
					data: {
						type: 'viewed-notifications',
						secret_id: '<?= md5('user_' . $user_token . '_viewedNotifications')?>'
					},
					success: function() {
						// $('header .col-2 .notifications-div ul .not-viewed').removeClass('not-viewed');
						// checkUserNotifications();
					}
				})
			}
			// viewingNotifications();
		}
	})
	<?
		endif;
	?>
	
</script>


