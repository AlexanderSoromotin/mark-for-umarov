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
	<title>FINDCREEK :: Группы интересов</title>
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
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/interest-groups">Группы интересов</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
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


			<div class="subsections">

				<? 
				// if ($_GET['subsections'] != '') {
					// $subsections_array = explode('_', $_GET['subsections']);

					// foreach ($subsections_array as $subsection_id) {
					// 	$subsections_data = mysqli_query($connection, "SELECT * FROM `interests_subsections` WHERE `id` = '$subsection_id'");

					// 	if ($subsections_data -> num_rows != 0) {
					// 		$subsections_data = mysqli_fetch_assoc($subsections_data);
					// 		echo '
					// 		<div class="subsection" id="filter_subsection_' . $subsection_id . '">
					// 			' . $subsections_data[$lang . '_title'] . '
					// 			<img src="' . $link . '/assets/img/icons/x.svg">
					// 		</div>';
					// 	}
						
					// }
				// } 
				$subsections = mysqli_query($connection, "SELECT * FROM `interests_subsections` ");

				while ($s = mysqli_fetch_assoc($subsections)) {
					echo '
					 		<div class="subsection" id="filter_subsection_' . $s['id'] . '">
					 			' . $s[$lang . '_title'] . '
					 			<img src="' . $link . '/assets/img/icons/x.svg">
					 		</div>';
				}
				?>
			</div>

			<div class="recent-interests">
				<h3>Интересы пользователей</h3>
				<div class="list">
					<div class="empty">Тут пусто :с</div>

					<!-- <a href="#">
						<div class="interests-block">
							<div class="row-1">
								<div class="photo">
									<img src="http://frmjdg.com/uploads/user_photo/user_21.jpg?v=121640">
								</div>
							</div>
							<div class="row-2">
									<p>Ищу друга, шоб сыграть в каэсгэ</p>
									<div class="info">
										<div class="subinfo">
											<b>Соромотин Александр</b> 
											<div class="bullet-point"></div> 
											только что
										</div>
										<div class="group">
											<img src="<?= $link ?>/assets/img/icons/pacman.svg">
											Игры
										</div>
									</div>

							</div>
						</div>
					</a> -->

					

				</div>
			</div>
			<!-- <center>
				<button class="button-1 loadMore">
					Загрузить ещё
				</button>
			</center> -->
		</div>

		<div class="col-2">
			<div class="filters">
				<div class="title">
					Фильтры поиска
				</div>

				<ul>
					<li><input type="checkbox" name="city"> отображать результаты только из вашего города (Пермь)</li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
			</div>
			
			<? if ($userLogged) :?>
				<button class="button-3 add_interest">Создать запись</button>
				<a href="<?= $link ?>/interest-groups/user">
					<button class="button-1">Мои записи</button>
				</a>
			<? endif; ?>

			<div class="interests_sections">
				<?
					$interests_sections = mysqli_query($connection, "SELECT * FROM `interests_sections` ORDER BY `id`");

					while ($i = mysqli_fetch_assoc($interests_sections)) {
						$interest_id = $i['id'];
						$interests_subsections = mysqli_query($connection, "SELECT * FROM `interests_subsections` WHERE `section_id` = '$interest_id' ORDER BY `id` ");
						echo '
						<div class="interests_sections_block" id="interests_' . $i['id'] . '">
							<div class="title">
								<h3>' . $i['rus_title'] . '</h3>
								<!--<img class="arrow-down" src="' . $link . '/assets/img/icons/chevron-down.svg">-->
							</div>';

						if ($interests_subsections -> num_rows == 0) {
							echo '<div class="empty">Нет результатов</div>';
						} else {
							echo '<ul class="list">';
							while ($i_subs = mysqli_fetch_assoc($interests_subsections)) {
								echo '
									<li id="list_subsection_' . $i_subs['id'] . '"><img src="' . $link . $i_subs['icon'] .'"><span>' . $i_subs['rus_title'] . '</span></li>
								';
							}
							echo '</ul>';
						}
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>

	





	<div class="editPanel">
		<div class="editBlock addInterest">

			<div class="header">
				<h3>Добавление записи</h3>
				<img draggable="false" src="<?= $link ?>/assets/img/icons/x.svg">
			</div>

			<div class="content">
				
				<div class="row-1">
					<div class="col-1">
						<label>Выбор группы</label>
						<div class="relative">

							<span class="section_span">
								<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
								<?
									// $user_city_id = $local_user_data['city_id'];
									// $user_city = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `cities` WHERE `id` = '$user_city_id' "));

									// if ($user_city == 0 or $user_city == '') {
									// 	$user_city_title = '';
									// } else {
									// 	$user_country_id = $user_city['country_id'];
									// 	$user_country = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `countries` WHERE `id` = '$user_country_id' "));
									// 	$user_city_title = $user_city['rus_title'] . ' (' . $user_country['rus_title'] . ')';
									// }

									
								?>
								<input autocomplete="disabledFunction" autocomplete="disabled" autocomplete="off" type="" value="<?= $user_city_title ?>" name="" placeholder="Поиск">
								<ul class="subsections custom-scrollbar">
									<?
										$subsections = mysqli_query($connection, "SELECT * FROM `interests_subsections` ORDER BY `id`");
											$subsections_li = '';

											while ($s = mysqli_fetch_assoc($subsections)) {
												$subsections_li = $subsections_li .  "<li title='" . $s['rus_title'] . "'>" . $s['rus_title'] . "</li>";
											}
										
										echo $subsections_li;
										echo '<h5>Не нашли то, что искали? </h5>' . '<a href="' . $link . '/support"><div>Напишите нам об этом</div></a><br>';
									?>
									
								</ul>
							</span>
						</div>
					</div>

					<div class="col-2">
						<label>Заголовок</label>
						<input type="" name="" placeholder="Кратко опишите, что вас интересует">
					</div>
				</div>

				<div class="row-2">
					<label>Основная мысль</label>
					<textarea placeholder="Детально опишите: что или кого Вы хотите привлечь к вашим интересам"></textarea>
				</div>

				<ul class="settings">
					<li>
						<p>Локация</p>
						<select>
							<? if ($user_city_rus != '') {echo '<option>' . $user_city_rus . '</option>';}  ?>
							<option>Не важно</option>
						</select>

						<div class="shortInfo">
							<img draggable="false" class="" src="<?=$link?>/assets/img/icons/help.svg">
							<p>Выбрав определённый город, эта запись будет отображаться только пользователям, установившим соотвественные фильтры поиска. Выбрав "Не важно" эту запись увидят большее количество человек.</p>
						</div>

					</li>
					<li>
						<input type="checkbox" name="">
						<p>Рассказать друзьям</p>
					</li>
				</ul>
				<p class="error-message"></p>
				<a href="<?= $link ?>/interest-groups/user">
					<button class="button-3 publish">Опубликовать</button>
				</a>
				
			</div>
		</div>
	</div>












	<script type="text/javascript" src="../assets/editPanel.js"></script>
	<script type="text/javascript">
		selectTab('interest-groups');

		$('.main .search-tools .search-input input').keyup(() => searchInerests());
		$('.main .filters input').click(() => searchInerests());
		$('.main .search-tools select').change(function () {
			searchInerests()
		});

		$('.search-tools .search-input img').click(function () {
			$('.search-tools .search-input input').focus();
		})

		function addSubsection (id) {
			$('#list_subsection_' + id).addClass('selected');
			$('#filter_subsection_' + id).addClass('subsection-selected');
			console.log($('#list_subsection_' + id))
			searchInerests();
		}

		let user_location = '<?= $user_city_rus ?>';
		strGET = window.location.search.replace( '?', ''); 
		strGet_array = strGET.split('?');

		if (strGet_array[0] != '') {
			subsections_text = strGet_array[0].replace( 'subsections=', ''); 

			subsections_array = subsections_text.split('_');

			for (index = 0; index < subsections_array.length; index++) {
				addSubsection([subsections_array[index]]);
				// $('#list_subsection_' + [subsections_array[index]]).addClass('selected');
			}
		}

		function removeSubsection (id) {
			$('#filter_subsection_' + id).removeClass('subsection-selected');
			searchInerests()
		}

		$('body').on('click', '.subsections img', function () {
			id = $(this).parent().attr('id').replace('filter_subsection_', '');
			removeSubsection(id);
			$('#list_subsection_' + id).removeClass('selected');
		})


		$('.interests_sections ul li').click(function () {
			id = $(this).attr('id').replace('list_subsection_', '');

			if ($(this).hasClass('selected')) {
				$(this).removeClass('selected');
				removeSubsection(id);
			} else {
				// $(this).addClass('selected');
				addSubsection(id);
			}
		})

		function getLastInerests () {
			if ($('.main .col-2 .filters input[name="city"]:checked').length == 0) {
				loc = 0;
			} else {
				loc = 1;
			}
			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					loc: loc,
					html: true,
					type: 'get-last-interests',
					secret_id: '<?= md5('user_' . $user_token . '_getLastInterests')?>'
				},
				success: function (result) {
					$('.recent-interests .empty').remove();
					$('.recent-interests .list').append(result);
					// console.log(result);
				}
			})
		}
		// getLastInerests();

		// Блокирование получение запроса с сервера, если докрутили до конца и не получили уже отпарвленный запрос на сервер
		let scrollBlock = 0;

		window.addEventListener('scroll', function () {
			value_scrollY = window.scrollY;

			blocksLength = $('.interests-block').length - 1;

			// console.log($('.interests-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight))
			if ($('.interests-block:eq(' + blocksLength + ')').offset().top - (value_scrollY + window.innerHeight) <= 300 && scrollBlock == 0) {
				scrollBlock = 1;
				limitTo += 10;
				searchInerests('', 'scroll');
			}
		})

		let limitTo = 25;
		let responses_array = [];
		function searchInerests (type, method) {
			if (method != 'scroll') {
				$('.main .col-1 .recent-interests .list').css({'opacity' : '.5'})
			}
			
			if ($('.main .col-2 .filters input[name="city"]:checked').length == 0) {
				loc = 0;
			} else {
				loc = 1;
			}

			subsections = '';
			subsections_length = $('.main .col-1 .subsections .subsection-selected').length;
			if (subsections_length != 0) {
				for (let i = 0; i < subsections_length; i++) {
					subsections += $('.main .col-1 .subsections .subsection-selected:eq(' + i + ')').attr('id').replace('filter_subsection_', '');
					if (i + 1 != subsections_length) {
							subsections += ',';
					}
					// console.log(subsections);
				}
			}
			sort = $('.search-tools select').val();
			// console.log(sort)
			search_text = $('.search-tools .search-input input').val();
			// console.log(search_text)
			if (method == 'scroll') {
				limitFrom = $('.interests-block').length;
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
			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					subsections: subsections,
					search_text: search_text,
					sort: sort,
					loc: loc,
					html: true,
					limitFrom: limitFrom,
					limitTo: limitTo,
					type: 'search-interests',
					secret_id: '<?= md5('user_' . $user_token . '_searchInterests')?>'
				},
				success: function (result) {
					// console.log(result);
					timeout = 200;
					if (type == 'instantly') {
						timeout = 0;
					}
					setTimeout(function () {
						if (method == 'scroll') {
							$('.recent-interests .empty').remove();
							$('.recent-interests .list').append(result);
							// $('.main .col-1 .recent-interests .list').css({'opacity' : '1'})
							if (result.length < 50) {
								responses_array.push('empty');
							}
						} else {
							$('.recent-interests .interests-block').remove();
							$('.recent-interests .empty').remove();
							$('.recent-interests .list').append(result);
							$('.main .col-1 .recent-interests .list').css({'opacity' : '1'})
						}
						console.log('Всего блоков: ' + $('.interests-block').length)
						scrollBlock = 0;

						if ($('.interests-block').length != 0) {
							$('.empty').remove();
						}

						blocksLength = $('.interests-block').length;
						if (blocksLength != 0) {
							if ($('.interests-block:eq(' + (blocksLength - 1) + ')').offset().top - (value_scrollY + window.innerHeight) <= 200 && scrollBlock == 0) {
								scrollBlock = 1;
								limitTo += 10;
								searchInerests('', 'scroll');
							}
						}
						
					}, timeout)

					// console.log(result);
				}
			})
		}
		searchInerests('instantly');

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
		$('.addInterest .section_span img').click(function () {
			$('.addInterest .section_span input').focus();
		})

		// Показ списка УУ при нажатии на input
		$('.addInterest .section_span input').on('focus', function () {
			$('.addInterest .section_span').addClass('subsections-show');
		})

		// Поиск УУ
		$('.addInterest .section_span input').on('input keyup', function () {
			// console.log(1)
			$('.addInterest .section_span ul li').removeClass('hidden')
			// $('.addInterest .section_span ul h5').remove();
			// $('.addInterest .section_span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.addInterest .section_span ul li').length; li_eq++) {
					if ( $('.addInterest .section_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.addInterest .section_span ul li:eq(' + li_eq + ')').addClass('hidden');
					} else {
						result++;
					}
				}
				if (result == 0) {
					// изменено: эти надписи добавляются изначально
					// $('.addInterest .section_span ul').append('<h5>Не нашли своего? </h5>');
					// $('.addInterest .section_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.addInterest .section_span ul, .addInterest .section_span ul li').click(function () {
			$('.addInterest .section_span input').focus();
			// $('.col-2 .section_span').addClass('subsections-show');
		})

		$('.addInterest .section_span ul li').click(function () {
			$('.addInterest .section_span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.addInterest .section_span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.addInterest .section_span :focus').length === 0) {
	    			$('.addInterest .section_span').removeClass('subsections-show');
	    		}
		    }, 250)
		});

		function showErrorMessage (text) {
			$('.addInterest .error-message').text(text);

			setTimeout(function () {
				$('.addInterest .error-message').text('')
				// $('.addInterest .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.addInterest .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 1000);
		}

		$('.editPanel .addInterest .publish').click(function () {
			group = $('.addInterest .section_span input').val();
			title = $('.addInterest .row-1 .col-2 input').val();
			body = $('.addInterest .row-2 textarea').val();
			loc = $('.addInterest select').val();

			groups_lenght = $('.addInterest .section_span ul li').length;
			group_flag = 0;
			
			for (let i = 0; i < groups_lenght; i++) {
				if ($('.addInterest .section_span ul li:eq(' + i + ')').text() == group) {
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

			if ($('.addInterest input:checked').length != 0) {
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
					secret_id: '<?= md5('user_' . $user_token . '_addInterest')?>'
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
	
	<?
		include_once '../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('interests');
	</script>
</body>
</html>