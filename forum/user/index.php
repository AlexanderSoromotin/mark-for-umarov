<?
	$lang = 'rus';
	include_once '../../inc/info.php';
	include_once '../../inc/db.php';
	include_once '../../inc/userData.php';

	include_once '../../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('Banned', '/banned');
	redirect('unlogged', '/');

	include_once '../../inc/head.php';

	$local_user_id = $_GET['id'];

	if ($local_user_id == '') {
		$local_user_id = $user_id;
	}

	$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");

	if ($local_user_data -> num_rows == 0) {
		$local_user_id = 0;
	} else {
		$local_user_data = mysqli_fetch_assoc($local_user_data);
	}


?>

<html>
<head>
	<meta charset="utf-8">
	<title>Темы пользователя <?= $local_user_data['first_name'] . ' ' . $local_user_data['last_name'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?
		include_once '../../inc/header.php'; // Шапка
		include_once '../../assets/online.php'; // Онлайн
	?>

	<? if ($local_user_id == $user_id) : ?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum">Форум</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum/user">Ваши темы</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			

			<div class="recent-topics">
				<h3>Ваши темы</h3>
				<div class="list">
					<div class="empty">Тут пусто :с</div>
				</div>
			</div>
		</div>
	</div>

	<? elseif ($local_user_id == 0) : ?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum">Форум</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum/user">Темы пользователя</a>
		</div>
	</div>

	<div class="main">
		<div class="user-not-found">
			<img draggable="false" src="http://frmjdg.com/assets/img/icons/zoom-cancel.svg">
			<h2>Ничего не найдено</h2>
		</div>
	</div>

	<? else : ?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum">Форум</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/forum/user/?id=<?= $local_user_data['id'] ?>">Темы пользователя <?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			

			<div class="recent-topics">
				<h3>Темы пользователя <a href="<?= $link ?>/profile/?id=<?= $local_user_id ?>"><?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></a></h3>
				<div class="list">
					<div class="empty">Тут пусто :с</div>
				</div>
			</div>
		</div>
	</div>

	<? endif; ?>





	<div class="editPanel">
		<div class="editBlock editTopic">

			<div class="header">
				<h3>Редактирование темы</h3>
				<img draggable="false" src="<?= $link ?>/assets/img/icons/x.svg">
			</div>

			<div class="content">
				
				<div class="row-1">
					<!-- <div class="col-1">
						<label>Выбор группы</label>
						<select>
							<option>Тема</option>
							<option>Статья</option>
						</select>
					</div> -->

					<div class="col-2">
						<label>Заголовок</label>
						<input type="" name="title" placeholder="Кратко опишите, что вас интересует">
					</div>
				</div>

				<div class="row-2">
					<label>Основная мысль</label>
					<textarea placeholder="Детально опишите, что вас интересует"></textarea>
				</div>

				<ul class="settings">
					<li>
						<div class="settings-1">
							<div>
								<p>Город</p>
								<select>
									<? if ($user_city_rus != '') {echo '<option>' . $user_city_rus . '</option>';}  ?>
									<option>Не важно</option>
								</select>
							</div>

							<div>
								<p>Учебное заведение</p>
								<select>
									<? if ($user_education_id != 0) {echo '<option>' . $user_education_short_title . '</option>';}  ?>
									<option>Не важно</option>
								</select>
							</div>
						</div>


						<div class="shortInfo">
							<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
							<p>Выбрав определённый город, эта запись будет отображаться только пользователям, установившим соотвественные фильтры поиска. Выбрав "Не важно" эту запись увидит большее количество человек.</p>
						</div>

					</li>
					<!-- <li>
						<input type="checkbox" name="repost">
						<p>Рассказать друзьям</p>
					</li> -->
				</ul>
				<p class="error-message"></p>
				<!-- <a href="<?= $link ?>/interest-groups/user"> -->
					<button class="button-3 save">Сохранить</button>
				<!-- </a> -->
				
			</div>
		</div>
	</div>












	<script type="text/javascript">
		selectTab('forum');

		let scrollToRecord = '<?
								if ($_GET['edit'] != '' and $scrollToEdit == 0) {
									echo '#topic_' . $_GET['edit'];
								}
							?>';

		function getUserTopics () {
			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					html: true,
					type: 'get-user-topics',
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_getUserTopics')?>'
				},
				success: function (result) {
					$('.recent-topics .empty').remove();
					$('.recent-topics .topic-block').remove();
					$('.recent-topics .list').append(result);
					// console.log(result);

					if (scrollToRecord != '') {
						window.scrollTo({top: $(scrollToRecord).offset().top - (window.innerHeight / 2) , behavior: "smooth"});
						$(scrollToRecord).css({'background-color' : 'rgba(0, 0, 0, .1)'});
						scrollToRecord = '';
					}
				}
			})
		}
		getUserTopics();

		let topic_id = 0;
		// Открытие панели добавления записи
		$(document).on('click', '.edit-topic', function () {
			topic_id = $(this).parent().parent().attr('id').replace('topic_', '')
			// console.log(topic_id)
			editTopic(topic_id);
		})

		function editTopic (topic_id) {
			$('.editPanel').addClass('editPanel-opened');
			$('body').css({'overflow' : 'hidden'});

			setTimeout(function () {
				$(scrollToRecord).css({'background-color' : 'unset'});
			}, 1000)

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					topic_id: topic_id,
					type: 'get-topic-data',
					secret_id: '<?= md5('user_' . $user_token . '_getTopicData')?>'
				},
				success: function (result) {
					console.table(result)
					
					if (result != '') {
						result = JSON.parse(result);
						console.table(result)
					}

					$('.editTopic input').val(result['group_title']);
					$('.editTopic .row-1 .col-2 input').val(result['title']);
					$('.editTopic .row-2 textarea').val(result['body']);

					if (result['location'] == 1) {
						$('.editTopic .settings select:eq(0)').val(result['city_title']);
					} else {
						$('.editTopic .settings select:eq(0)').val('Не важно');
					}

					if (result['education'] == 1) {
						$('.editTopic .settings select:eq(1)').val(result['education_title']);
					} else {
						$('.editTopic .settings select:eq(1)').val('Не важно');
					}
					
				}
			})
		}

		$(document).on('click', '.editTopic .save', function () {
			title = $('.editTopic .row-1 .col-2 input').val();
			body = $('.editTopic .row-2 textarea').val();
			loc = $('.editTopic select:eq(0)').val();
			education = $('.editTopic select:eq(1)').val();

			if (title == '' || title.length < 3) {
				showErrorMessage('Слшиком короткий заголовок');
				return;
			}
			if (body == '' || body.length < 3) {
				showErrorMessage('Слшиком короткая основная мысль');
				return;
			}
			if (body == '' || body.length < 3) {
				showErrorMessage('Слшиком короткая основная мысль');
				return;
			}
			if (loc == 'Не важно') {
				loc = 0;
			} else {
				loc = 1;
			}

			if (education == 'Не важно') {
				education = 0;
			} else {
				education = 1;
			}

			if (topic_id == '') {
				return;
			}

			$('.publish').removeClass('publish').addClass('published');
			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					title: title,
					body: body,
					loc: loc,
					education: education,
					topic_id: topic_id,
					type: 'edit-topic',
					secret_id: '<?= md5('user_' . $user_token . '_editTopic')?>'
				},
				success: function (result) {
					console.log(result);
					if (result == 'record edited') {
						$('body').css({'overflow' : 'unset'});
						$('.editPanel').removeClass('editPanel-opened');
						getUserTopics();
					}
				}
			})
		})

		function showErrorMessage (text) {
			$('.editTopic .error-message').text(text);

			setTimeout(function () {
				$('.editTopic .error-message').text('')
				// $('.editTopic .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.editTopic .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 1000);
		}


		

		$(document).on('click', '.hide-topic', function () {
			id = $(this).parent().parent().attr('id').replace('topic_', '');
			$(this).parent().parent().addClass('hidden')
			$('#topic_' + id + ' .hide-topic').removeClass('hide-topic').addClass('show-topic').children('img').attr('src', '<?= $link ?>/assets/img/icons/eye-off.svg')

			// console.log(id)
			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					html: true,
					type: 'hide-topic',
					topic_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_hideTopic')?>'
				},
				success: function (result) {
					console.log(result)
				}
			})
		})

		$(document).on('click', '.show-topic', function () {
			id = $(this).parent().parent().attr('id').replace('topic_', '');
			// console.log(111)
			$(this).parent().parent().removeClass('hidden')
					$('#topic_' + id + ' .show-topic').removeClass('show-topic').addClass('hide-topic').children('img').attr('src', '<?= $link ?>/assets/img/icons/eye.svg')

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					html: true,
					type: 'show-topic',
					topic_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_showTopic')?>'
				},
				success: function (result) {
					// console.log(result)
				}
			})
		})

		$(document).on('click', '.delete-topic', function () {
			id = $(this).parent().parent().attr('id').replace('topic_', '');
			// console.log(111)
			if (!confirm('Вы уверены, что хотите удалить эту тему? Это действие будет невозможно отменить.')) {
				return;
			}

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					html: true,
					type: 'delete-topic',
					topic_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_deleteTopic')?>'
				},
				success: function (result) {
					console.log(result)
					getUserTopics();
				}
			})
		})
		


		// Открытие панели добавления записи
		$('.add_interest').click(function () {
			$('.editPanel').addClass('editPanel-opened');
			$('body').css({'overflow' : 'hidden'});
		})

		$('.main .interests_sections_block').click(function () {
			if ($(this).hasClass('interests_sections_block_opened')) {
				$(this).removeClass('interests_sections_block_opened');
			} else {
				$(this).addClass('interests_sections_block_opened');
			}
		})

		// Фокусировка на input при нажатии на картинку
		$('.editTopic .section_span img').click(function () {
			$('.editTopic .section_span input').focus();
		})

		// Показ списка УУ при нажатии на input
		$('.editTopic .section_span input').on('focus', function () {
			$('.editTopic .section_span').addClass('subsections-show');
		})

		// Поиск УУ
		$('.editTopic .section_span input').on('input keyup', function () {
			// console.log(1)
			$('.editTopic .section_span ul li').removeClass('hidden')
			// $('.editTopic .section_span ul h5').remove();
			// $('.editTopic .section_span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.editTopic .section_span ul li').length; li_eq++) {
					if ( $('.editTopic .section_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.editTopic .section_span ul li:eq(' + li_eq + ')').addClass('hidden');
					} else {
						result++;
					}
				}
				if (result == 0) {
					// изменено: эти надписи добавляются изначально
					// $('.editTopic .section_span ul').append('<h5>Не нашли своего? </h5>');
					// $('.editTopic .section_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.editTopic .section_span ul, .editTopic .section_span ul li').click(function () {
			$('.editTopic .section_span input').focus();
			// $('.col-2 .section_span').addClass('subsections-show');
		})

		$('.editTopic .section_span ul li').click(function () {
			$('.editTopic .section_span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.editTopic .section_span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.editTopic .section_span :focus').length === 0) {
	    			$('.editTopic .section_span').removeClass('subsections-show');
	    		}
		    }, 250)
		});

		function showErrorMessage (text) {
			$('.editTopic .error-message').text(text);

			setTimeout(function () {
				$('.editTopic .error-message').text('')
				// $('.editTopic .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.editTopic .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 1000);
		}

		$('.editPanel .editTopic .publish').click(function () {
			group = $('.editTopic .section_span input').val();
			title = $('.editTopic .row-1 .col-2 input').val();
			body = $('.editTopic .row-2 textarea').val();
			loc = $('.editTopic select').val();

			groups_lenght = $('.editTopic .section_span ul li').length;
			group_flag = 0;
			
			for (let i = 0; i < groups_lenght; i++) {
				if ($('.editTopic .section_span ul li:eq(' + i + ')').text() == group) {
					group_flag = 1;
					break;
				}
			}

			if (group == '') {
				showErrorMessage('Необходимо указать группу интересов');
				return;
			}
			if (group_flag == 0) {
				showErrorMessage('Выберите одну из существующих групп интересов или обратитесь в поддержку');
				return;
			}
			if (title == '' || title.length < 3) {
				showErrorMessage('Слшиком короткий заголовок');
				return;
			}
			if (body == '' || body.length < 3) {
				showErrorMessage('Слшиком короткая основная мысль');
				return;
			}
			if (body == '' || body.length < 3) {
				showErrorMessage('Слшиком короткая основная мысль');
				return;
			}
			if (loc == 'Не важно') {
				loc = 0;
			} else {
				loc = 1;
			}

			if ($('.editTopic input:checked').length != 0) {
				repost = 1;
			} else {
				repost = 0;
			}
			$('.publish').removeClass('publish').addClass('published');
			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					group: group,
					title: title,
					body: body,
					loc: loc,
					repost: repost,
					type: 'add-interest',
					secret_id: '<?= md5('user_' . $user_token . '_editTopic')?>'
				},
				success: function (result) {
					console.log(result);
					if (result == 'record added') {
						// Делать перенаправление на только-что добавленную запись
					} else {
						$('.published').removeClass('published').addClass('publish');
					}
				}
			})
		})

	</script>
	<script type="text/javascript" src="<?= $link ?>/assets/editPanel.js"></script>
	
	<?
		include_once '../../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('forum');
	</script>
</body>
</html>