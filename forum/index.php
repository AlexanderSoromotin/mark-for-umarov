<?
	$lang = 'rus';
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');
	redirect('Banned', '/banned');
	// redirect('unlogged', '/');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>Форум</title>
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
			<a href="<?= $link ?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?= $link ?>/forum">Форум</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			<? if ($userLogged) :?>
				<button class="button-3 add_topic">Создать тему</button>
				<a href="<?= $link ?>/forum/user">
					<button class="button-1">Мои темы</button>
				</a>
			<? endif; ?>

			<div class="filters">
				<div class="title">
					Фильтры поиска
				</div>

				<ul>
					<li style="display: block;">
						<h3>Город</h3>
						<select name="city">
							<option>Неважно</option>
							<option>
								<?
									echo $user_city_rus
								?>
							</option>
						</select>
					</li>

					<li style="display: block;">
						<h3>Учебное заведение</h3>
						<select name="education">
							<option>Неважно</option>
							<option>
								<?
									echo $user_education_short_title;
								?>
							</option>
						</select>
					</li>

					
					<li style="display: block;">
						<h3>Содержимое</h3>
						<div><input type="checkbox" checked name="topics">Темы форума</div>
						<div><input type="checkbox" checked name="article">Статьи</div>
					</li>

					<li style="display: block;">
						<h3>Дополнительное</h3>
						<div><input type="checkbox" name="onlyFromFriends">Только от друзей</div>
						<!-- <div><input type="checkbox" name="article">Статьи</div> -->
					</li>

					<!-- <li><input type="checkbox" name="city"> Отображать результаты только из вашего города (Пермь)</li> -->

					<!-- <li></li> -->
					<!-- <li></li> -->
				</ul>
			</div>
		</div>

		<div class="col-2">
			<div class="search-tools">
				<div class="search-input">
					<img src="<?= $link ?>/assets/img/icons/search.svg">
					<input type="" name="" placeholder="Поиск">
				</div>

				<div class="search-sort">
					<select>
						<option>Сначала последние</option>
						<option>По количеству ответов</option>
						<option>Сначала старые</option>
					</select>
				</div>
			</div>

			<div class="topics-list">
				<h3>Темы пользователей</h3>
				<div class="list">
					<!-- <div class="empty">Тут пусто :с</div> -->

					<!-- <a href="http://frmjdg.com/interest-groups/record/?id=167">
					<div class="topic-block">
						<div class="background">
							<img src="">
						</div>
						<div class="body">
							<div class="row-1">
							<div class="photo">
								<img style="top: 0%; left:0%;;transform: scale(1);" src="http://frmjdg.com/uploads/user_photo/user_21.jpg?v=916725">
							</div>
						</div>
						<div class="row-2">
								<p>Один гость показался нам нежелательным. Некий доктор Дор...</p>
								<div class="info">
									<div class="subinfo">
										<b>Соромотин Александр</b> 
										<div class="bullet-point"></div> Вчера в 10:43

										<div class="replies">
											<img src="http://frmjdg.com/assets/img/icons/message.svg">
											0
										</div>
									</div>
									

									<div class="group">
										<img src="http://frmjdg.com/assets/img/icons/news.svg">
										Статья
									</div>
								</div>

						</div>
						</div>
					</div>
				</a> -->

					

				</div>
			</div>
		</div>
	</div>

	





	<div class="editPanel">
		<div class="editBlock addTopic">

			<div class="header">
				<h3>Добавление темы</h3>
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
							<p>Выбрав определённый город, эта запись будет отображаться только пользователям, установившим соотвественные фильтры поиска. Выбрав "Не важно" эту запись увидят большее количество человек.</p>
						</div>

					</li>
					<!-- <li>
						<input type="checkbox" name="repost">
						<p>Рассказать друзьям</p>
					</li> -->
				</ul>
				<p class="error-message"></p>
				<!-- <a href="<?= $link ?>/interest-groups/user"> -->
					<button class="button-3 publish">Опубликовать</button>
				<!-- </a> -->
				
			</div>
		</div>
	</div>












	<script type="text/javascript" src="../assets/editPanel.js"></script>
	<script type="text/javascript">
		selectTab('forum');

		$('.main .search-tools .search-input input').keyup(() => searchTopics());

		$('.search-tools .search-input img').click(function () {
			$('.search-tools .search-input input').focus();
		})

		$('.main .filters input').click(() => searchTopics());
		$('.main .search-tools select').change(function () {
			searchTopics()
		});

		$('.main .filters select').change(function () {
			searchTopics()
		});

		let user_location = '<?= $user_city_rus ?>';
		strGET = window.location.search.replace( '?', ''); 
		strGet_array = strGET.split('?');

		// Блокирование получение запроса с сервера, если докрутили до конца и не получили уже отпарвленный запрос на сервер
		let scrollBlock = 0;

		window.addEventListener('scroll', function () {
			value_scrollY = window.scrollY;

			blocksLength = $('.topics-block').length - 1;

			// console.log($('.topic-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight))
			if ($('.topics-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight) <= 300 && scrollBlock == 0) {
				scrollBlock = 1;
				limitTo += 10;
				searchTopics('', 'scroll');
			}
		})

		let limitTo = 25;
		let responses_array = [];
		function searchTopics (type, method) {
			if (method != 'scroll') {
				$('.main .topics-list .list').css({'opacity' : '.5'})
			}
			
			if ($('.main .filters select[name="city"]').val() == 'Неважно') {
				loc = 0;
			} else {
				loc = 1;
			}

			if ($('.main .filters select[name="education"]').val() == 'Неважно') {
				education = 0;
			} else {
				education = 1;
			}

			if ($('.main .filters input[name="topics"]:checked').length == 0) {
				topics = 0;
			} else {
				topics = 1;
			}

			if ($('.main .filters input[name="article"]:checked').length == 0) {
				articles = 0;
			} else {
				articles = 1;
			}

			if ($('.main .filters input[name="onlyFromFriends"]:checked').length == 0) {
				onlyFromFriends = 0;
			} else {
				onlyFromFriends = 1;
			}

			sort = $('.search-tools select').val();
			// console.log(sort)
			search_text = $('.search-tools .search-input input').val();
			// console.log(search_text)
			if (method == 'scroll') {
				limitFrom = $('.topic-block').length;
				if (responses_array.length > 2) {
					return;
				}
			} else {
				responses_array = [];
				limitFrom = 0;
				limitTo = 25;
			}

			// console.log('limitFrom: ' + limitFrom);
			// console.log('limitTo: ' + limitTo);
			console.table(loc, education, topics, articles, onlyFromFriends)
			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					search_text: search_text,
					sort: sort,
					loc: loc,
					education: education,
					topics: topics,
					articles: articles,
					onlyFromFriends: onlyFromFriends,
					html: true,
					limitFrom: limitFrom,
					limitTo: limitTo,
					type: 'search-topics',
					secret_id: '<?= md5('user_' . $user_token . '_searchTopics')?>'
				},
				success: function (result) {
					// console.log(result);
					timeout = 200;
					if (type == 'instantly') {
						timeout = 0;
					}
					setTimeout(function () {
						if (method == 'scroll') {
							$('.topics-list .empty').remove();
							$('.topics-list .list').append(result);
							// $('.main .col-1 .topics-list .list').css({'opacity' : '1'})
							if (result.length < 50) {
								responses_array.push('empty');
							}
						} else {
							$('.topics-list .topic-block').remove();
							$('.topics-list .empty').remove();
							$('.topics-list .list').append(result);
							$('.main .topics-list .list').css({'opacity' : '1'})
						}
						console.log('Всего блоков: ' + $('.topic-block').length)
						scrollBlock = 0;

						if ($('.topic-block').length != 0) {
							$('.empty').remove();
						}

						blocksLength = $('.topic-block').length;
						if (blocksLength != 0) {
							if ($('.topic-block:eq(' + (blocksLength - 1) + ')').offset().top - (value_scrollY + window.innerHeight) <= 200 && scrollBlock == 0) {
								scrollBlock = 1;
								limitTo += 10;
								searchTopics('', 'scroll');
							}
						}
						
					}, timeout)

					// console.log(result);
				}
			})
		}
		searchTopics('instantly');

		// Открытие панели добавления записи
		$('.add_topic').click(function () {
			$('.editPanel').addClass('editPanel-opened');
			$('body').css({'overflow' : 'hidden'});
		})

		// $('.main .interests_sections_block').click(function () {
		// 	if ($(this).hasClass('interests_sections_block_opened')) {
		// 		$(this).removeClass('interests_sections_block_opened');
		// 	} else {
		// 		$(this).addClass('interests_sections_block_opened');
		// 	}
		// })

		// Фокусировка на input при нажатии на картинку
		$('.addTopic .section_span img').click(function () {
			$('.addTopic .section_span input').focus();
		})

		// Показ списка УУ при нажатии на input
		$('.addTopic .section_span input').on('focus', function () {
			$('.addTopic .section_span').addClass('subsections-show');
		})

		// Поиск УУ
		$('.addTopic .section_span input').on('input keyup', function () {
			// console.log(1)
			$('.addTopic .section_span ul li').removeClass('hidden')
			// $('.addTopic .section_span ul h5').remove();
			// $('.addTopic .section_span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.addTopic .section_span ul li').length; li_eq++) {
					if ( $('.addTopic .section_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.addTopic .section_span ul li:eq(' + li_eq + ')').addClass('hidden');
					} else {
						result++;
					}
				}
				if (result == 0) {
					// изменено: эти надписи добавляются изначально
					// $('.addTopic .section_span ul').append('<h5>Не нашли своего? </h5>');
					// $('.addTopic .section_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.addTopic .section_span ul, .addTopic .section_span ul li').click(function () {
			$('.addTopic .section_span input').focus();
			// $('.col-2 .section_span').addClass('subsections-show');
		})

		$('.addTopic .section_span ul li').click(function () {
			$('.addTopic .section_span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.addTopic .section_span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.addTopic .section_span :focus').length === 0) {
	    			$('.addTopic .section_span').removeClass('subsections-show');
	    		}
		    }, 250)
		});

		function showErrorMessage (text) {
			$('.addTopic .error-message').text(text);

			setTimeout(function () {
				$('.addTopic .error-message').text('')
				// $('.addTopic .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.addTopic .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 2500);
		}

		$('.editPanel .addTopic .publish').click(function () {
			if ($(this).hasClass('published')) {
				return;
			}

			title = $('.addTopic .row-1 .col-2 input').val();
			body = $('.addTopic .row-2 textarea').val();
			
			loc = $('.addTopic select:eq(0)').val();
			education = $('.addTopic select:eq(1)').val();

			if (title == '') {
				showErrorMessage('Необходимо указать заголовок темы');
				return;
			}
			if (body == 0) {
				showErrorMessage('Пожалуйста, в основной мысли распишите, что вас интересует');
				return;
			}
			if (title == '' || title.length < 2) {
				showErrorMessage('Заголовок должен быть длиннее');
				return;
			}
			if (body == '' || body.length < 3) {
				showErrorMessage('Слишиком короткая основная мысль');
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

			$('.publish').addClass('published').addClass('loading').removeClass('publish');

			$.ajax({
				url: '<?= $link ?>/inc/forum.php',
				method: 'POST',
				cache: false,
				data: {
					title: title,
					body: body,
					loc: loc,
					education: education,
					type: 'add-topic',
					secret_id: '<?= md5('user_' . $user_token . '_addTopic')?>'
				},
				success: function (result) {
					console.log(result);
					if (result == 'topic added') {
						location.href = "<?= $link ?>/forum/user";
					} else {
						$('.published').removeClass('published').removeClass('loading').addClass('publish');
					}
				}
			})
		})

	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('forum');
	</script>
</body>
</html>