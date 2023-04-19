<?	
	$lang = 'rus';
	include_once '../../inc/info.php';
	include_once '../../inc/db.php';
	include_once '../../inc/userData.php';

	include_once '../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('Banned', '/banned');
	// redirect('unlogged', '/');

	include_once '../../inc/head.php';


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
$timezone = $_SESSION['user_timezone'] - $_SESSION['server_timezone'];

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

// Подсчёт последнего онлайна пользователя
function calcTime ($date) {
	// Разница между часовыми поясами сервера и пользователя
	global $timezone;

	// Имеем client_current - время на данный момент по часовому поясу клиента
	global $client_current_year;
	global $client_current_month;
	global $client_current_day;
	global $client_current_minutes;

	global $months_accusative;

	// Дата последнего посещения по часовому поясу сервера
	$server_last_online_year = (int) mb_substr($date, 0, 4);
	$server_last_online_month = (int) mb_substr($date, 5, 2);
	$server_last_online_day = (int) mb_substr($date, 8, 2);

	$server_last_online_hour = (int) mb_substr($date, 11, 2);
	$server_last_online_minute = (int) mb_substr($date, 14, 2);

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

	if ($client_current_year != $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		// return $client_current_year;
		return addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}

	if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
		// return $client_current_minutes . ' ' . $client_last_online_minutes;
		return 'Только что';

	}

	if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
		return caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

	}
	if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return 'Вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return 'Сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
	if ($client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
	}
}


$topic_id = $_GET['id'];
$topic_data = mysqli_query($connection, "SELECT * FROM `forum_topics` WHERE `id` = '$topic_id'");
if ($topic_data -> num_rows != 0) {
	$topic = mysqli_fetch_assoc($topic_data);
	
	$topic_title = $topic['title'];
	$topic_body = $topic['body'];
	$topic_loc = $topic['loc'];
	$topic_city_id = $topic['city_id'];
	$topic_date = $topic['date'];
}


?>

<html>
<head>
	<meta charset="utf-8">
	<title><?= $topic_title ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?
		include_once '../../inc/header.php'; // Шапка
		include_once '../../assets/online.php'; // Онлайн

		
	?>

	

	
		<? if ($topic_id == '' or $topic_data -> num_rows == 0) : ?>

			<!-- Хронология -->
			<div class="history">
				<div class="block">
					<a href="<?= $link ?>/">Главная</a>
					<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
					<a href="<?= $link ?>/forum">Темы</a>
				</div>
			</div>

			<div class="main">
				<div class="record-not-found">
					<img draggable="false" src="<?= $link ?>/assets/img/icons/zoom-cancel.svg">
					<h2>Запись не найдена</h2>
				</div>
			</div>
		<? else : ?>
		<!-- Хронология -->
			<div class="history">
				<div class="block">
					<a href="<?$link?>/">Главная</a>
					<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
					<a href="<?$link?>/forum">Группы интересов</a>
				</div>
			</div>

			<?
				// $topic = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `interests_records` WHERE `id` = '$topic_id'"));
				$topic_user_id = $topic['user_id'];
				$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$topic_user_id'"));

			?>
		
			<div class="main">
				<div class="messageList">
					<div class="firstPost post" alt="user_<?= $topic_user_id ?>">
						<a href="<?= $link ?>/forum/user/?edit=<?= $topic['id'] ?>">
							<div class="edit-record">
								<img src="<?= $link ?>/assets/img/icons/edit.svg">
								<p>Редактировать</p>
							</div>
						</a>
						<div class="row-1">
							<div class="col-1">
								<a href="<?= $link ?>/profile/?id=<?= $user_id ?>">
									<div class="image">
										<img src="<?= $local_user_data['photo'] ?>" style="<?= unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');'?>">
									</div>
								</a>
							</div>

							<div class="col-2">
								<div class="title">
									<h2><?= $topic['title'] ?></h2>
								</div>
								<div class="body">
									<?= $topic['body'] ?>
								</div>
							</div>
						</div>

						<div class="name">
							<a href="<?= $link ?>/profile/?id=<?= $user_id ?>">
								<b><?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></b> 
							</a>

								<div class="bullet-point"></div><div class="date"><?= calcTime($topic['date']) ?></div>

								<? if ($topic['last_redactor'] != 0) : ?>
									<div title="Эта тема была отредактирована автором или одним из участников админ-состава" class="edit-mark">ред.</div>
								<? endif; ?>
						</div>
					</div>

					<div class="replies">
						<!-- <div class="empty">Ваш ответ будет первый</div> -->


						<?
							if ($topic['replies'] != '') {
								$replies = unserialize($topic['replies']);

								if (count($replies) != 0) {
									foreach ($replies as $reply_id => $reply_data) {
										$reply_user_id = $reply_data['user_id'];
										$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$reply_user_id;'"));

										$author = '';
										if ($topic['user_id'] == $reply_data['user_id']) {
											$author = '<div class="author">
											Автор темы
										</div>';
										}
										echo '<div alt="reply_' . $reply_id . '" class="reply reply_user_' . $reply_data['user_id'] . '">

										<div id="reply_' . $reply_id . '" alt="user_' . $reply_data['user_id'] . '" class="post">
											<div class="functions">
												<img class="add-reply-img" src="' . $link . '/assets/img/icons/message-plus.svg">
												<img class="add-reply-img" src="' . $link . '/assets/img/icons/heart.svg">
											</div>
											<div class="col-1">
											<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
												<div class="image">
													
													<img src="' . $local_user_data['photo'] . '" style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $local_user_data['photo'] . '">
													

												</div>
												</a>
											</div>
											<div class="col-2">
												<div class="name">
													<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
														<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
													</a>

													' . $author . '

														<div class="bullet-point"></div><div class="date">' . calcTime($reply_data['date']) . '</div>
												</div>

												<div class="body">
													' . $reply_data['message'] . '
												</div>
											</div>
										</div>
										';



										foreach ($reply_data['replies'] as $reply_id => $reply_data) {
											$reply_user_id = $reply_data['user_id'];
										$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$reply_user_id;'"));

										$author = '';
										if ($topic['user_id'] == $reply_data['user_id']) {
											$author = '<div class="author">
											Автор темы
										</div>';
										}

										echo '
										<div id="reply_' . $reply_id . '" alt="user_' . $reply_data['user_id'] . '" class="post">

										<div class="functions">
												<img class="add-reply-img" src="' . $link . '/assets/img/icons/message-plus.svg">
												<img class="add-reply-img" src="' . $link . '/assets/img/icons/heart.svg">
											</div>

											<div class="col-1">
											<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
												<div class="image">
													
													<img src="' . $local_user_data['photo'] . '" style="' . unserialize($local_user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($local_user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $local_user_data['photo'] . '">
													

												</div>
												</a>
											</div>
											<div class="col-2">
												<div class="name">
													<a href="' . $link . '/profile/?id=' . $reply_data['user_id'] . '">
														<b>' . $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] . '</b> 
													</a>
													' . $author . '

														<div class="bullet-point"></div><div class="date">' . calcTime($reply_data['date']) . '</div>
												</div>

												<div class="body">
													' . $reply_data['message'] . '
												</div>
											</div>
										</div>
										';

										}

										echo '</div>';
									}
								}
							}
						?>
					</div>

					<div class="add-reply">
						<div class="col-1 online">
							<a href="<?= $link ?>/profile/?id=<?= $user_id ?>">
								<div class="image">
									<img src="<?= $user_photo ?>" style="<?= $user_photo_style['ox_oy'] . ';transform: scale(' . $user_photo_style['scale'] . ');'?>">
								</div>
							</a>
						</div>
						<div class="col-2">
							<div alt="" class="row-1 reply-to">
								<div>Ответить на сообщение от пользователя <b></b> 
								</div>
								<img src="<?= $link ?>/assets/img/icons/x.svg">
							</div>

							<div class="row-2">
								<textarea onfocus="this.setSelectionRange(this.value.length,this.value.length);" placeholder="Напишите ответ..."></textarea>
							
								<div class="buttons">
									<button class="reply">
										<img class="reply-img" src="<?= $link ?>/assets/img/icons/brand-telegram.svg">
									</button>
								</div>
							</div>
							
						</div>
					</div>

					<div class="scroll-top ">
						<img src="<?= $link ?>/assets/img/icons/arrow-narrow-up.svg">
					</div>
				</div>
			</div>


		<? endif; ?>
	


	<script type="text/javascript" src="../assets/editPanel.js"></script>
	<script type="text/javascript">
		selectTab('forum');

		window.addEventListener('scroll', function () {
			let value_scrollY = window.scrollY;

			if (value_scrollY > 200) {
				$('.scroll-top').removeClass('scroll-top-hidden');
			} else {
				$('.scroll-top').addClass('scroll-top-hidden');
			}
		})

		$('.scroll-top').click(function () {
			window.scrollTo({top: 0, behavior: 'smooth'});
		})

		// Закрытие обращения
		$('.reply-to img').click(function () {
			$('.reply-to').removeClass('reply-to-show').attr('alt', '');
			$('.add-reply textarea').val('').focus()
		})

		// Определение сообщения, к которому происходит обращение
		$('.reply-to div').click(function () {
			post_id = $('.reply-to').attr('alt').replace(/\&.*/, '');
			console.log(post_id);
			$('#' + post_id).css({'background-color' : 'rgba(0, 0, 0, .07)'});
			setTimeout(function () {
				$('#' + post_id).css({'background-color' : 'unset'});
			}, 1000)
			$('.add-reply textarea').focus();
		})

		// Обращение к сообщению
		$('body').on('click', '.add-reply-img', function () {

			if ($(this).parent().parent().parent().attr('alt') == '') {
				id = $(this).parent().parent().parent().attr('alt').replace('reply_', '');
			} else {
				id = $(this).parent().parent().attr('id').replace('reply_', '');
			}

			// id = $(this).parent().parent().attr('id').replace('reply_', '');
			name = $(this).parent().parent().children('.col-2').children('.name').children('a').children('b').text();

			user_id = $(this).parent().parent().attr('alt').replace('user_', '');
			$('.reply-to').attr('alt', 'reply_' + id + '&&user_' + user_id);

			$('.add-reply textarea').val(name + ', ').focus();

			if ($('.reply-to').hasClass('reply-to-show')) {
				$('.reply-to').removeClass('reply-to-show');
				setTimeout(function () {
					$('.reply-to b').text(name);
					$('.reply-to').addClass('reply-to-show');
				}, 300)
			} else {
				$('.reply-to b').text(name);
				$('.reply-to').addClass('reply-to-show');
			}
		})

		function addReply (message) {
			if (message.replace(' ', '').replace(/<\/?[^>]+(>|$)/g, "").length <= 0) {
				return;
			}

			reply_to = $('.reply-to').attr('alt');
			reply_to_array = reply_to.split('&&');

			if (reply_to != '') {
				if ($('.reply[alt="' + reply_to_array[0] + '"]').length == 0) {
					reply_to = $('#' + reply_to_array[0]).parent().attr('alt') + '&&' +reply_to_array[1];

				}
			}
			// console.log(reply_to_array[0]);
			console.log('reply_to: ' + reply_to);

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					type: 'reply',
					message: message,
					reply_to: reply_to,
					topic_id: '<?= $topic_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_reply')?>'
				},
				success: function (result) {
					// console.log(result);
					$('.reply-to').removeClass('reply-to-show').attr('alt', '');
					window.scrollTo({top: $(window).height(), behavior: 'smooth'});
					$('.add-reply textarea').val('').focus()
				}

			})
		}

		$('body').on('textarea keydown', '.add-reply textarea', function (e) {
			// e.consume();
			// e.stopImmediatePropagation();
			// console.log(event.cancelable);

			id = $(this).attr('alt');
			message = $('.add-reply textarea').val();

			if (e.shiftKey && e.keyCode == 13) {
				// console.log('shift + enter');
			} else {
				if (e.keyCode == 13) {
					e.preventDefault();
					if (message.replace(' ', '').replace(/<\/?[^>]+(>|$)/g, "").length > 0) {
						addReply(message);
						// console.log(message)
						// getReplies();
					}
					
				}
			}
		})

		$('.reply-img').click(function() {
			addReply(message);
		})

		function getReplies () {
			replies_length = $('.replies .post').length;
			replies = [];

			for (let i = 0; i < replies_length; i++) {
				replies.push($('.replies .post:eq(' + i + ')').attr('id').replace('reply_', ''));
				// if (i + 1 != replies_length) {
				// 	replies += '_';
				// }
			}

			// console.log(replies);

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					type: 'get-replies',
					replies: JSON.stringify(replies),
					topic_id: '<?= $topic_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_getReplies')?>'
				},
				success: function (result) {
					if (result != '') {
						$('.replies .empty').remove();
						result = JSON.parse(result);
						// console.log(result);

						if (result[0] != '') {
							$('.replies').append(result[0]);
						}

						if (result[1].length != 0) {
							result_length = result[1].length;

							for (let i = 0; i < result_length; i++) {
								// console.log(result[1][i]['reply_id']);
								// console.log(result[1][i]['parent_reply_id']);
								// console.log(result[1][i]['body']);

								parent_reply_id = result[1][i]['parent_reply_id'];
								body = result[1][i]['body'];
								// console.log(body);

								if ($('#reply_' + result[1][i]['reply_id']).length == 0) {
									$('.reply[alt="reply_' + parent_reply_id + '"]').append(body);
								}
								
							}
							
						}
					}
				setTimeout(function() {
					$('.post-hidden').removeClass('post-hidden');
				}, 100)
				setTimeout(function() {
					getReplies()
				}, 1000)
				}
			})
		}

		getReplies();

		function getRepliesDate() {
			replies_length = $('.replies .post').length;
			replies_array = [];
			for (let i = 0; i < replies_length; i++) {
				if ($('.replies .post:eq(' + i + ') .col-2 .name .date').text().indexOf('назад') != -1 || $('.replies .post:eq(' + i + ')').children('.col-2').children('.name').children('.date').text().indexOf('Только') != -1) {
					// replies_array += $('.replies .post:eq(' + i + ')').attr('id').replace('reply_', '') + '_';
					replies_array.push($('.replies .post:eq(' + i + ')').attr('id').replace('reply_', ''));
				}
			}
			// console.log(replies_array);
			// replies_array = replies_array.substr(0, replies_array.length-1);
			// console.log(replies_array);

			if (replies_array.length != 0) {
				$.ajax({
					url: '<?= $link ?>/inc/forum.php',
					method: 'POST',
					cache: false,
					data: {
						topic_id: '<?= $topic_id ?>',
						type: 'get-replies-date',
						replies: JSON.stringify(replies_array),
						secret_id: '<?= md5('user_' . $user_token . '_getRepliesDate')?>'
					},
					success: function (result) {
						// console.log(result)

						if (result != '') {
							replies_date = JSON.parse(result);
							replies_date_lenght = replies_date.length;

							for (let i = 0; i < replies_date_lenght; i++) {
								$('#reply_' + replies_date[i]['reply_id'] + ' .name .date').text(replies_date[i]['date'])
							}

						}
						


						setTimeout(function () {
							getRepliesDate()
						}, 30000)
					}
				})
			} else {
				setTimeout(function () {
					getRepliesDate()
				}, 30000)
			}
		}
		getRepliesDate()


		function getUsersOnline() {
			replies_length = $('.replies .post').length;
			users_array = [];

			for (let i = 0; i < replies_length; i++) {
				if (users_array.indexOf($('.post:eq(' + i + ')').attr('alt').replace('user_', '')) == -1) {
					users_array.push($('.post:eq(' + i + ')').attr('alt').replace('user_', ''));
				}
			}

			if (users_array.length != 0) {
				$.ajax({
					url: '<?= $link ?>/inc/online.php',
					method: 'POST',
					cache: false,
					data: {
						type: 'get-online',
						users: JSON.stringify(users_array)
					},
					success: function (result) {
						// console.log(result)

						if (result != '') {
							users = JSON.parse(result);

							for (user_id in users) {

								if (users[user_id] == 'Онлайн') {
									$('.post[alt="user_'+ user_id + '"] .col-1').addClass('online');
								} else {
									$('.post[alt="user_'+ user_id + '"] .col-1').removeClass('online');
								}
							}

						}

						setTimeout(function () {
							getRepliesDate()
						}, 2000)
					}
				})
			} else {
				setTimeout(function () {
					getRepliesDate()
				}, 2000)
			}
		}
		getUsersOnline()
		
	</script>
	
	<?
		include_once '../../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('forum');
	</script>
</body>
</html>