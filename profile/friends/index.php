<?
	include_once '../../inc/info.php';
	include_once '../../inc/db.php';
	include_once '../../inc/userData.php';

	include_once '../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('Banned', '/banned');

	include_once '../../inc/head.php';

	
	if ($_GET['id'] != '') {
		$local_user_id = $_GET['id'];
		$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");

		if ($local_user_data -> num_rows == 0) {
			header("Location: " . $link . '/profile/friends');
		}
		$local_user_data = mysqli_fetch_assoc($local_user_data);

		if ($local_user_data['id'] != $user_id) {
				
			if ($local_user_data['status'] == 'deleted' and $local_user_data['status'] == 'pre-deleted') {
				header("Location: " . $link . '/profile/friends');
			}

			$local_user_friends = unserialize($local_user_data['friends']);
			if ($local_user_friends == '') {
				$local_user_friends = array();
			}

			$friendsOfAnotherUser = true;
		}
	} else {
		$friendsOfAnotherUser = false;
		redirect('unlogged', '/authorization');

	}
?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Друзья</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

	<?
		include_once '../../inc/header.php'; // Шапка
		include_once '../../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/profile">Профиль</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/profile/friends">Друзья</a>
		</div>
	</div>

	<div class="main">
		<div class="menu">
			<ul>
				<? if (!$friendsOfAnotherUser) :?>
					<li id="menu-friends" class=""><img draggable="false" src="<?= $link ?>/assets/img/icons/users.svg"> Мои друзья</li>
					<li id="menu-requests" class=""><img draggable="false" src="<?= $link ?>/assets/img/icons/user-plus.svg">Заявки в друзья</li>
					<li id="menu-search-friends" class=""><img draggable="false" src="<?= $link ?>/assets/img/icons/search.svg">Поиск друзей</li>
				<? else : ?>
					<li id="menu-anotherUser-friends" class="selected"><img draggable="false" src="<?= $link ?>/assets/img/icons/users.svg">Друзья</li>
				<? endif; ?>
			</ul>
		</div>

		<div class="content">
			<? if (!$friendsOfAnotherUser) :?>

			<div class="friends content-div">
				<h2>Список друзей</h2>
				<div class="list">
					<div class="empty">Поиск...</div>
				</div>
				
			</div>

			<div class="requests content-div">
				<h2>Заявки в друзья</h2>
				<div class="list">
					<?	
						$requests = mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `incoming_id` = '$user_id'");

						if ($requests -> num_rows == 0) {
							echo '<div class="empty">Тут пусто :с</div>';
						}
						while ($r = mysqli_fetch_assoc($requests)) {
							$incoming_id = $r['outgoing_id'];

							$friend = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$incoming_id'"));

							$education_id = $friend['education_id'];
							$education_title = mysqli_fetch_array(mysqli_query($connection, "SELECT `title` FROM `education` WHERE `id` = '$education_id'"))[0];
							
							echo '
								<div class="list-block" id="request_' . $friend['id'] . '">
									<a target="_blink" href="' . $link . '/profile/?id=' . $friend['id'] . '">
										<div class="photo online">
											<div class="image">
												<img style="' . unserialize($friend['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($friend['photo_style'])['scale'] . ');" src="' . $friend['photo'] . '">
											</div>
										</div>
									</a>
									<div class="name">
										<a target="_blink" href="' . $link . '/profile/?id=' . $friend['id'] . '"><p>' . $friend['last_name'] . ' ' . $friend['first_name'] . '</p></a>
										<p>' . $education_title . '</p>
									</div>
									<div class="buttons">
										<button class="button-3 addFriend">Добавить в друзья</button>
										<button class="button-1 cancelRequest">Отклонить заявку</button>
									</div>
								</div>

							';
						}
					?>
				</div>
			</div>

			<div class="search-friends content-div">
				<h2>Поиск друзей</h2>
				<div class="list">

					<div class="search">
						<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
						<input type="" name="" placeholder="Поиск">
					</div>

					<div class="empty">Поиск...</div>
				</div>
			</div>

			<? else : ?>

			<div class="anotherUser-friends content-div">
				<h2>Друзья пользователя <?= $local_user_data['last_name'] . ' ' . mb_substr($local_user_data['first_name'], 0, 1) . '. ' . mb_substr($local_user_data['patronymic'], 0, 1) . '.' ?></h2>
				<div class="list">
					<?	
						if (count($local_user_friends) == 0) {
							echo '<div class="empty">Тут пусто :с</div>';
						}

						// Перебор друзей пользователя
						foreach ($local_user_friends as $key => $value) {
							$friend = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM users WHERE id = {$value}"));

							if ($user_token != '') {
								$addFriend_button = '<button class="button-3 addFriend">Добавить в друзья</button>';

								// Если от нас уже отправлен запрос к этому пользователю
								if (mysqli_query($connection, "SELECT * FROM `friend_requests` WHERE `outgoing_id` = '$user_id' and `incoming_id` = '$value'") -> num_rows != 0) {
									$addFriend_button = '<button class="button-3">Запрос отправлен</button>';
								}
								// Ищем пользователя у себя в друзях
								foreach ($user_friends as $key2 => $value2) {
									if ($value2 == $value) {
										$addFriend_button = '<button class="button-3">У вас в друзьях</button>';
									}
								}

								// Проверяем, не мы ли это
								if ($user_id == $value) {
									$addFriend_button = '<button class="button-3"> Это Вы</button>';
								}
							} else {
								$addFriend_button = '';
							}
							

							$education_id = $friend['education_id'];
							$education_title = mysqli_fetch_array(mysqli_query($connection, "SELECT `title` FROM `education` WHERE `id` = '$education_id'"))[0];
							if ($education_id == '') {
								$education_title = '-';
							}
							
							echo '
								<div class="list-block" id="user_' . $friend['id'] . '">
									<a target="_blink" href="' . $link . '/profile/?id=' . $friend['id'] . '">
										<div class="photo online">
											<div class="image">
												<img style="' . unserialize($friend['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($friend['photo_style'])['scale'] . ');" src="' . $friend['photo'] . '">
											</div>
										</div>
									</a>
									<div class="name">
										<a target="_blink" href="' . $link . '/profile/?id=' . $friend['id'] . '"><p>' . $friend['last_name'] . ' ' . $friend['first_name'] . '</p></a>
										<p>' . $education_title . '</p>
									</div>
									<div class="buttons">
										' . $addFriend_button . '
										<a target="_blink" href="' . $link . '/profile/?id=' . $friend['id'] . '"><button class="button-1">Открыть профиль</button></a>
									</div>
								</div>

							';
						}
					?>
				</div>	
			</div>
			<? endif; ?>
		</div>
	</div>


		
	<script type="text/javascript">
		// Получение сведений об онлайне пользователей
		function getLastOnline (type) {
			users = [];

			if (type == 'friends') {
				users_length = $('.friends .list-block').length;
			
				for (let i = 0; i < users_length; i++) {
					users.push( Number($('.friends .list-block:eq(' + i + ')').attr('id').replace('friend_', '')) );
				}
			} else if (type == 'rejects') {
				users_length = $('.requests .list-block').length;
			
				for (let i = 0; i < users_length; i++) {
					users.push( Number($('.requests .list-block:eq(' + i + ')').attr('id').replace('request_', '')) );
				}
			} else if (type == 'search-friends') {
				users_length = $('.search-friends .list-block').length;
			
				for (let i = 0; i < users_length; i++) {
					users.push( Number($('.search-friends .list-block:eq(' + i + ')').attr('id').replace('user_', '')) );
				}
			}
			else if (type == 'anotherUser-friends') {
				users_length = $('.anotherUser-friends .list-block').length;
			
				for (let i = 0; i < users_length; i++) {
					users.push( Number($('.anotherUser-friends .list-block:eq(' + i + ')').attr('id').replace('user_', '')) );
				}
			} else {
				return;
			}
			
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

					classname = '';
					if (type == 'friends') {
						classname = 'friend';
					}
					if (type == 'rejects') {
						classname = 'request';
					}
					if (type == 'search-friends') {
						classname = 'user';
					}
					if (type == 'anotherUser-friends') {
						classname = 'user';
					}

					for (user_id in users) {

						if (users[user_id] == 'Онлайн') {
							$('#' + classname +'_' + user_id + ' .photo').addClass('online_active');
							$('#' + classname +'_' + user_id + ' .photo').addClass('online_active');
						} else {
							$('#' + classname +'_' + user_id + ' .photo').removeClass('online_active');
							$('#' + classname +'_' + user_id + ' .photo').removeClass('online_active');
						}
					}
				}
			})
		}
		setInterval(() => getLastOnline('friends'), 5000);
		setInterval(() => getLastOnline('rejects'), 5000);
		setInterval(() => getLastOnline('search-friends'), 5000);
		setTimeout(() => getLastOnline('search-friends'), 500)

		// Получение списка друзей
		function getFriendsList () {
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'get-friends-list',
					secret_id: '<?= md5('user_' . $user_token . '_getFriendsList')?>',
					html: true
				},
				success: function (result) {
					// console.log(result);
					$('.main .content .friends .list-block').remove();
					$('.main .content .friends .empty').remove();
					$('.main .content .friends .list').append(result);
					getLastOnline('friends');
				}
			})
		}
		getFriendsList();

		// Получение списка запросов в друзья
		function getFriendRequests () {
			$.ajax({
				url: '<?= $link ?>/inc/friends.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'get-friend-requests',
					secret_id: '<?= md5('user_' . $user_token . '_getFriendRequests')?>',
					html: true
				},
				success: function (result) {
					// console.log(result);
					checkCountFriendRequests();
					$('.main .content .requests .list-block').remove();
					$('.main .content .requests .empty').remove();
					$('.main .content .requests .list').append(result);
					getLastOnline('rejects');
				}
			})
		}
		getFriendRequests();

		function openTab (classname) {
			$('.main .menu ul li').removeClass('selected');
			$('.main .menu ul #menu-' + classname).addClass('selected');

			$('.main .content .content-div').css({'display' : 'none'});
			$('.main .content .' + classname).css({'display' : 'inline'});
		}

		let scrollBlock = 0;

		window.addEventListener('scroll', function () {
			value_scrollY = window.scrollY;

			blocksLength = $('.search-friends .list .list-block').length - 1;

			// console.log($('.search-friends .list .list-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight))
			if ($('.search-friends .list .list-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight) <= 300 && scrollBlock == 0 && $('.search-friends').css('display') != 'none') {
				scrollBlock = 1;
				limitTo += 10;
				getUsers('scroll');
			}
		})

		// let limitFrom = 0;
		let limitTo = 20;
		let responses_array = [];
		function getUsers (method) {
			if (method != 'scroll') {
				$('.search-friends .list-block, .search-friends .empty').css({'opacity' : '.5'});
			}

			search_text = $('.search-friends .search input').val();
			// console.log(search_text)

			if (method == 'scroll') {
				limitFrom = $('.search-friends .list-block').length;
				if (responses_array.length > 2) {
					return;
				}
			} else {
				responses_array = [];
				limitFrom = 0;
				limitTo = 20;
			}
			
			
			// console.log('---------------');
			// console.log('limitFrom: ' + limitFrom);
			// console.log('limitTo: ' + limitTo);
			$.ajax({
				url: '<?= $link ?>/inc/get-users.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'search-users',
					search_text: search_text,
					limitFrom: limitFrom,
					limitTo: limitTo,
					secret_id: '<?= md5('user_' . $user_token . '_searchUsers')?>',
				},
				success: function (result) {
					setTimeout(function () {
						// console.log(result)

						if (method == 'scroll') {
							$('.main .content .search-friends .list .empty').remove();
							$('.main .content .search-friends .list').append(result);
							
							if (result.length < 50) {
								responses_array.push('empty');
							}
						} else {
							$('.search-friends .list-block, .search-friends .empty').css({'opacity' : '1'});
							$('.main .content .search-friends .list .empty').remove();
							$('.main .content .search-friends .list .list-block').remove();
							$('.main .content .search-friends .list').append(result);
						}
						scrollBlock = 0;
						if ($('.search-friends .list-block	').length != 0) {
							$('.search-friends .empty').remove();
						}
						

						blocksLength = $('.search-friends .list .list-block').length;
						console.log('Всего блоков: ' + blocksLength);
						// console.log($('.search-friends .list .list-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight))
						if ($('.search-friends .list .list-block:eq(' + (blocksLength - 1) + ')').offset().top - (value_scrollY + window.innerHeight) <= 300 && scrollBlock == 0 && $('.search-friends').css('display') != 'none') {
							scrollBlock = 1;
							limitTo += 10;
							getUsers('scroll');
						}
					}, 200)
					
				}
			})
		}
		getUsers();

		// Клик на пункт меню
		$('.main .menu ul li').click(function () {
			if (!$(this).hasClass('selected')) {
				openTab($(this).attr("id").replace('menu-', ''));
			}
		})

		<? 
		if ($friendsOfAnotherUser) {
			echo "openTab('anotherUser-friends'); setInterval(() => getLastOnline('anotherUser-friends'), 5000);";	
			echo '$("document").ready(function () {
				getLastOnline("anotherUser-friends");
			})';
				 
		}
		else {
			if ($_GET['act'] == 'incoming-requests') {
				echo "openTab('requests');";
			} else if ($_GET['act'] == 'search-friends') {
				echo "openTab('search-friends');";
			} else {
				echo "openTab('friends');";
				// echo "openTab('search-friends');";
			}
		}  
		?>

		// Проверка количества запросов в друзья
		// Если 0, то убираем уведомление из шапки
		function checkCountFriendRequests () {
			if ($('.main .content .requests .list-block').length == 0) {
				$('header .col-2 .menu ul .profile-friends').removeClass('notification');
				checkHeaderMenuNotifications();
			}
		}

		function addNotification (selector) {
			$(selector).addClass('notification');						
		}
		function removeNotification (selector) {
			$(selector).removeClass('notification');
			if ($('.panel .menu ul .notification').length == 0) {
				$('header .header .admin-panel').removeClass('notification');
			}						
		}

		<? if (!$friendsOfAnotherUser): ?>

			$('.content .search-friends .search input').on('input keyup', function () {
				// $('.content .search-friends .list .list-block').css({'display' : 'flex', 'border-bottom' : '1px solid #ccc'});


				// text = $(this).val();
				// count = $('.content .search-friends .list .list-block').length;
				// $('.empty').remove();
				// flag = count;
				
				// lastBlock = 0;
				// for (eq = 0; eq < count; eq++) {
				// 	block = $('.content .search-friends .list .list-block:eq(' + eq + ')');
				// 	if (block.attr('alt').toLowerCase().indexOf(text.toLowerCase()) == -1) {
				// 		block.css({'display' : 'none'});
				// 		flag--;
				// 	} else {
				// 		lastBlock = eq;
				// 	}
				// }
				// $('.content .search-friends .list .list-block:eq(' + lastBlock + ')').css({'border-bottom' : '1px solid rgba(0, 0, 0, 0)'});

				// if (flag == 0) {
				// 	$('.content .search-friends .list').append('<p class="empty">Нет результатов</p>');
				// } 
				getUsers();

			})

			$('.search img').click(function () {
				$(this).parent().children('input').focus();
			})

			// Проверка наличия запросов на дружбу
			if ($('.content .requests .list-block').length != 0) {
				addNotification('#menu-requests');
			}

			// Добавление друга (Одобрение заявки)
			$('body').on('click', '.content .requests .buttons .addFriend', function () {
				id = $(this).parent().parent().attr('id').replace('request_', '');
				parent = $(this).parent().parent();
				block = '.content .requests #request_' + id;

				$.ajax({
					url: '<?= $link ?>/inc/friends.php',
					type: 'POST',
					cache: false,
					data: {
						local_user_id: id,
						secret_id: '<?= md5('user_' . $user_token . '_addFriend')?>',
						type: 'add-friend'
					},
					success: function (result) {
						// console.log('success: ' + result);
						if (result == 'friend_added') {
							parent.css({'margin-left' : '105%'})
							
							getFriendsList();
						}
						

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});
							
							setTimeout(function () {
								parent.remove();
								// Проверяем, остались ли ещё заявки, если нет, то убираем уведомление из шапки
								checkCountFriendRequests()

							}, 300)
							if ($('.content .requests .list-block').length - 1 == 0) {
								$('.content .requests .list').append('<div class="empty">Тут пусто :с</div>');
								removeNotification('#menu-requests')
							}
						}, 400);
					}
				})
			})

			// Добавление друга (Через поиск друзей)
			$('body').on('click', '.content .search-friends .buttons .addFriend', function () {
				id = $(this).parent().parent().attr('id').replace('user_', '');
				parent = $(this).parent().parent();
				block = '.content .requests #request_' + id;

				$.ajax({
					url: '<?= $link ?>/inc/friends.php',
					type: 'POST',
					cache: false,
					data: {
						local_user_id: id,
						secret_id: '<?= md5('user_' . $user_token . '_addFriend')?>',
						type: 'add-friend'
					},
					success: function (result) {
						// console.log('success: ' + result);
						if (result == 'friend_added') {
							$('.content .search-friends #user_' + id + ' .buttons .addfriend').text('В друзьях');
							getFriendsList();
						} else {
							$('.content .search-friends #user_' + id + ' .buttons .addfriend').text('Запрос отправлен');
						}
					}
				})
			})

			// Отклонение заявки в друзья
			$('body').on('click', '.content .requests .buttons .cancelRequest', function () {
				id = $(this).parent().parent().attr('id').replace('request_', '');
				parent = $(this).parent().parent();

				$.ajax({
					url: '<?= $link ?>/inc/friends.php',
					type: 'POST',
					cache: false,
					data: {
						local_user_id: id,
						secret_id: '<?= md5('user_' . $user_token . '_rejectRequest')?>',
						type: 'reject-request'
					},
					success: function (result) {
						// console.log('success: ' + result);
						parent.css({'margin-left' : '105%'})

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});
							
							setTimeout(function () {
								parent.remove();
								// Проверяем, остались ли ещё заявки, если нет, то убираем уведомление из шапки
								checkCountFriendRequests()

							}, 300)
							if ($('.content .requests .list-block').length - 1 == 0) {
									$('.content .requests .list').append('<div class="empty">Тут пусто :с</div>');
									removeNotification('#menu-requests')
								}
						}, 400);
					}
				})
			})

			// Удаление из друзей
			$('body').on('click', '.content .friends .buttons .deleteFriend', function () {
				id = $(this).parent().parent().attr('id').replace('friend_', '');
				parent = $(this).parent().parent();

				// console.log(id)

				$.ajax({
					url: '<?= $link ?>/inc/friends.php',
					type: 'POST',
					cache: false,
					data: {
						local_user_id: id,
						secret_id: '<?= md5('user_' . $user_token . '_removeFriend')?>',
						type: 'remove-friend'
					},
					success: function (result) {
						console.log('success: ' + result);
						parent.css({'margin-left' : '105%'})

						setTimeout(function () {
							parent.css({'height' : '0px', 'padding' : '0px', 'margin' : '0px 0px 0px 105%'});
							
							setTimeout(function () {
								parent.remove();
							}, 300);
							if ($('.content .friends .list-block').length - 1 == 0) {
								$('.content .friends .list').append('<div class="empty">Тут пусто :с</div>');
							}
						}, 300)
					}
				})
			})
		<? else : ?>
			// Добавление друга (Через список друзей другого пользователя)
			$('.content .anotherUser-friends .buttons .addFriend').click(function () {
				id = $(this).parent().parent().attr('id').replace('user_', '');
				parent = $(this).parent().parent();

				$.ajax({
					url: '<?= $link ?>/inc/friends.php',
					type: 'POST',
					cache: false,
					data: {
						local_user_id: id,
						secret_id: '<?= md5('user_' . $user_token . '_addFriend')?>',
						type: 'add-friend'
					},
					success: function (result) {
						console.log('success: ' + result);
						if (result == 'friend_added') {
							$('.content .anotherUser-friends #user_' + id + ' .buttons .addfriend').text('В друзьях');
						} else {
							$('.content .anotherUser-friends #user_' + id + ' .buttons .addfriend').text('Запрос отправлен');
						}
					}
				})
			})
		<? endif; ?>


	</script>
	
	<?
		include_once '../../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('profile');
	</script>
</body>
</html>