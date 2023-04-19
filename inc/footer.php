<?
	include_once "info.php";
?>
<script type="text/javascript" src="<?= $link ?>/assets/main.js"></script>

<footer>
	<div class="content">
		<div class="row-1">
			<div class="col-1">
				<div class="links">
					<? if ($user_status == 'Admin'): ?>
					<ul>
						<a href="#"><li>Форум</li></a>
						<a href="#"><li>Группы интересов</li></a>
						<a href="<?= $link ?>/terms"><li>Правила</li></a>
						<a href="#"><li>О нас</li></a>
					</ul>
					<? else: ?>
						<ul>
						<a href="#"><li>xxxxx</li></a>
						<a href="#"><li>xxxxxx xxxxxxxxx</li></a>
						<a href="#"><li>xxxxxxx</li></a>
						<a href="#"><li>x xxx</li></a>
					</ul>
					<? endif; ?>
				</div>
				<div class="logo">
					<a href="<?= $link ?>"><h1>FindCreek</h1></a>
					<ul>
						<a href="https://vk.com/findcreek"><li><img src="<?= $link ?>/assets/img/icons/brand-vk.svg"></li></a>
						<!-- <a href="#"><li><img src="<?= $link ?>/assets/img/icons/brand-instagram.svg"></li></a> -->
					</ul>
				</div>

				<div class="copyright">
					<p><b>©</b> 2022, FindCreek.com</p>
					<a>Лицензионное соглашение</a>
				</div>
			</div>

			<div class="col-2">
				<h4>Email:</h4>
				<p>findcreek@gmail.com</p>

				<a href="<?= $link ?>/support"><button class="button-1">Написать в поддержку</button></a>

			</div>
		</div>
	</div>
</footer>

<? if ($_COOKIE['token'] != '') : ?>

<div class="mobile_footer">
	<ul>
		<li class="mobile_footer_tab_forum">
			<a href="<?= $link ?>/forum">
				<img src="<?= $link ?>/assets/img/icons/template.svg">
				<p>Форум</p>
			</a>
		</li>

		<li class="mobile_footer_tab_interests">
			<a href="<?= $link ?>/interest-groups">
				<img src="<?= $link ?>/assets/img/icons/yin-yang.svg">
				<p>Интересы</p>
			</a>
		</li>

		<li class="mobile_footer_tab_messenger">
			<a href="<?= $link ?>/messenger">
				<img src="<?= $link ?>/assets/img/icons/messages.svg">
				<p>Мессенджер</p>
			</a>
		</li>

		<li class="mobile_footer_tab_notifications">
			<a href="<?= $link ?>/notifications">
				<img src="<?= $link ?>/assets/img/icons/bell.svg">
				<p>Уведомления</p>
			</a>
		</li>

		<!--<li class="mobile_footer_tab_profile">-->
		<!--	<a href="<?= $link ?>/profile">-->
		<!--		<img src="<?= $link ?>/assets/img/icons/user-circle.svg">-->
		<!--		<p>Профиль</p>-->
		<!--	</a>-->
		<!--</li>-->
		
		<li class="mobile_footer_tab_settings">
			<a href="<?= $link ?>/settings">
				<img src="<?= $link ?>/assets/img/icons/settings.svg">
				<p>Настройки</p>
			</a>
		</li>

		
	</ul>
</div>

<? endif; ?>
<script type="text/javascript">
	function checkHeaderMenuNotifications () {
		if ($('header .col-2 .menu ul .notification').length != 0) {
			$('header .col-2 .menu .photo').addClass('notification');
		} else {
			$('header .col-2 .menu .photo').removeClass('notification');
		}
	}
	function checkUserNotifications () {
		if ($('header .col-2 .notifications-div ul .not-viewed').length != 0) {
			$('header .col-2 .notifications').addClass('notification');
		} else {
			$('header .col-2 .notifications').removeClass('notification');
		}
	}

	function select_mobile_footer_tab (name) {
		$('.mobile_footer_tab_' + name).addClass('selected');
	}

	<? if ($_COOKIE['token'] != '') : ?>
		<?
			$url = $_SERVER['REQUEST_URI'];
			if (strpos($url, '/messenger/') === false) :
		?>

			// Получение сообщений в онлайне
			function getMessages () {
				$.ajax({
					url: '<?= $link ?>/inc/messages.php',
					type: 'POST',
					cache: false,
					data : {
						type: 'check-new-messages',
						secret_id: '<?= md5('user_' . $user_token . '_checkNewMessages')?>'
					},
					success: function (result) {
						// console.log(result)
						if (result == 'new') {
							playNotificationAudio();
							if (!$('.header .messenger').hasClass('notification')) {
								$('.header .messenger').addClass('notification');
							}
							if (!$('.mobile_footer_tab_messenger').hasClass('notification')) {
								$('.mobile_footer_tab_messenger').addClass('notification');
							}
						}
						if (result == 'exist') {
							if (!$('.header .messenger').hasClass('notification')) {
								$('.header .messenger').addClass('notification');
							}
							if (!$('.mobile_footer_tab_messenger').hasClass('notification')) {
								$('.mobile_footer_tab_messenger').addClass('notification');
							}
						}
						if (result == 'null') {
							$('.header .messenger').removeClass('notification');
							$('.mobile_footer_tab_messenger').removeClass('notification');
						}
						checkHeaderMenuNotifications();
					}
				})
			}

			getMessages();
			setInterval(() => getMessages(), 2000);
		<? endif; ?>
	<? endif; ?>

	

	$.ajax({
    	url: '<?= $link ?>/inc/notifications.php',
    	cache: false,
    	type: "POST",
    	data: {
    		secret_id: '<?= md5('user_' . $user_token . '_getNotifications')?>',
    		type: 'get-all',
    		limit: 20
    	},
    	success: function (result) {
    		// console.log(result);
    		if (result != '') {
    			$('header .col-2 .notifications .notifications-div ul .empty').remove();
    		}
    		// console.log(result);
    		checkUserNotifications();
    		$('header .col-2 .notifications .notifications-div ul').prepend(result);
    		// console.log('loaded all notifications');

    		setInterval(() => getNotifications(), 2000);
			getNotifications();
    	}
    })

	function getNotifications () {
		$.ajax({
	    	url: '<?= $link ?>/inc/notifications.php',
	    	cache: false,
	    	type: "POST",
	    	data: {
	    		secret_id: '<?= md5('user_' . $user_token . '_getNotifications')?>',
	    		type: 'get-new'
	    	},
	    	success: function (result) {
	    		// console.log('notifications getted.');
	    		// console.log(result);
	    		if (result != '') {
	    			playNotificationAudio();
	    			$('header .col-2 .notifications .notifications-div ul .empty').remove();
	    		}
	    		$('header .col-2 .notifications .notifications-div ul').prepend(result);
	    		checkUserNotifications();
	    		setTimeout(function () {
	    			$('header .col-2 .notifications .notifications-div ul li').removeClass('hidden');
	    		}, 100)
	    	}
	    })
	}

	checkUserNotifications();
	checkHeaderMenuNotifications();

</script>

<?php
	mysqli_close($connection);
?>