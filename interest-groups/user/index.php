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
	<title>FINDCREEK :: Группы интересов</title>
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
			<a href="<?$link?>/interest-groups">Группы интересов</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/interest-groups/user">Ваши интересы</a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			

			<div class="recent-interests">
				<h3>Ваши интересы</h3>
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
			<a href="<?$link?>/interest-groups">Группы интересов</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/interest-groups/user">Интересы пользователя</a>
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
			<a href="<?$link?>/interest-groups">Группы интересов</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/interest-groups/user/?id=<?= $local_user_data['id'] ?>">Интересы пользователя <?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></a>
		</div>
	</div>

	<div class="main">
		<div class="col-1">
			

			<div class="recent-interests">
				<h3>Интересы пользователя <a href="<?= $link ?>/profile/?id=<?= $local_user_id ?>"><?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></a></h3>
				<div class="list">
					<div class="empty">Тут пусто :с</div>
				</div>
			</div>
		</div>
	</div>

	<? endif; ?>





	<div class="editPanel">
		<div class="editBlock editInterest">

			<div class="header">
				<h3>Редактирование записи</h3>
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
					<!-- <li>
						<input type="checkbox" name="">
						<p>Рассказать друзьям</p>
					</li> -->
				</ul>
				<!-- <button class="delete-this-record button-1">Удалить запись</button> -->
				<p class="error-message"></p>
				<button class="button-3 save">Сохранить изменения</button>
			</div>
		</div>
	</div>












	<script type="text/javascript">
		selectTab('interest-groups');

		let scrollToRecord = '<?
								if ($_GET['edit'] != '' and $scrollToEdit == 0) {
									echo '#interest_' . $_GET['edit'];
								}
							?>';

		function getUserInerests () {
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
					type: 'get-user-interests',
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_getUserInterests')?>'
				},
				success: function (result) {
					$('.recent-interests .empty').remove();
					$('.recent-interests .interests-block').remove();
					$('.recent-interests .list').append(result);
					// console.log(result);

					if (scrollToRecord != '') {
						window.scrollTo({top: $(scrollToRecord).offset().top - (window.innerHeight / 2) , behavior: "smooth"});
						$(scrollToRecord).css({'background-color' : 'rgba(0, 0, 0, .1)'});
						scrollToRecord = '';
					}

					
				}
			})
		}
		getUserInerests();

		let record_id = 0;
		// Открытие панели добавления записи
		$(document).on('click', '.edit-interest', function () {
			record_id = $(this).parent().parent().attr('id').replace('interest_', '')
			editRecord(record_id);
		})

		function editRecord (record_id) {
			$('.editPanel').addClass('editPanel-opened');
			$('body').css({'overflow' : 'hidden'});

			setTimeout(function () {
				$(scrollToRecord).css({'background-color' : 'unset'});
			}, 1000)

			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					record_id: record_id,
					type: 'get-record-data',
					secret_id: '<?= md5('user_' . $user_token . '_getRecordData')?>'
				},
				success: function (result) {
					if (result != '') {
						result = JSON.parse(result);
					}

					$('.editInterest input').val(result['group_title']);
					$('.editInterest .row-1 .col-2 input').val(result['title']);
					$('.editInterest .row-2 textarea').val(result['body']);

					if (result['location'] == 1) {
						$('.editInterest .settings select').val(result['city_title']);
					} else {
						$('.editInterest .settings select').val('Не важно');
					}
					
				}
			})
		}

		$(document).on('click', '.editInterest .save', function () {
			group = $('.editInterest .section_span input').val();
			title = $('.editInterest .row-1 .col-2 input').val();
			body = $('.editInterest .row-2 textarea').val();
			loc = $('.editInterest select').val();

			groups_lenght = $('.editInterest .section_span ul li').length;
			group_flag = 0;
			
			for (let i = 0; i < groups_lenght; i++) {
				if ($('.editInterest .section_span ul li:eq(' + i + ')').text() == group) {
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

			if ($('.editInterest input:checked').length != 0) {
				repost = 1;
			} else {
				repost = 0;
			}

			if (record_id == '') {
				return;
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
					record_id: record_id,
					type: 'edit-interest',
					secret_id: '<?= md5('user_' . $user_token . '_editInterest')?>'
				},
				success: function (result) {
					console.log(result);
					if (result == 'record edited') {
						$('body').css({'overflow' : 'unset'});
						$('.editPanel').removeClass('editPanel-opened');
						getUserInerests();
					}
				}
			})
		})


		// Фокусировка на input при нажатии на картинку
		$('.editInterest .section_span img').click(function () {
			$('.editInterest .section_span input').focus();
		})

		// Показ списка УУ при нажатии на input
		$('.editInterest .section_span input').on('focus', function () {
			$('.editInterest .section_span').addClass('subsections-show');
		})

		// Поиск УУ
		$('.editInterest .section_span input').on('input keyup', function () {
			// console.log(1)
			$('.editInterest .section_span ul li').removeClass('hidden')
			// $('.editInterest .section_span ul h5').remove();
			// $('.editInterest .section_span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.editInterest .section_span ul li').length; li_eq++) {
					if ( $('.editInterest .section_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.editInterest .section_span ul li:eq(' + li_eq + ')').addClass('hidden');
					} else {
						result++;
					}
				}
				if (result == 0) {
					// изменено: эти надписи добавляются изначально
					// $('.editInterest .section_span ul').append('<h5>Не нашли своего? </h5>');
					// $('.editInterest .section_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.editInterest .section_span ul, .editInterest .section_span ul li').click(function () {
			$('.editInterest .section_span input').focus();
			// $('.col-2 .section_span').addClass('subsections-show');
		})

		$('.editInterest .section_span ul li').click(function () {
			$('.editInterest .section_span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.editInterest .section_span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.editInterest .section_span :focus').length === 0) {
	    			$('.editInterest .section_span').removeClass('subsections-show');
	    		}
		    }, 250)
		});

		function showErrorMessage (text) {
			$('.editInterest .error-message').text(text);

			setTimeout(function () {
				$('.editInterest .error-message').text('')
				// $('.editInterest .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.editInterest .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 1000);
		}


		

		$(document).on('click', '.hide-interest', function () {
			id = $(this).parent().parent().attr('id').replace('interest_', '');
			$(this).parent().parent().addClass('hidden')
			$('#interest_' + id + ' .hide-interest').removeClass('hide-interest').addClass('show-interest').children('img').attr('src', '<?= $link ?>/assets/img/icons/eye-off.svg')

			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					loc: loc,
					html: true,
					type: 'hide-interest',
					interest_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_hideInterest')?>'
				},
				success: function (result) {
					// console.log(result)
				}
			})
		})

		$(document).on('click', '.show-interest', function () {
			id = $(this).parent().parent().attr('id').replace('interest_', '');
			// console.log(111)
			$(this).parent().parent().removeClass('hidden')
					$('#interest_' + id + ' .show-interest').removeClass('show-interest').addClass('hide-interest').children('img').attr('src', '<?= $link ?>/assets/img/icons/eye.svg')

			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					loc: loc,
					html: true,
					type: 'show-interest',
					interest_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_showInterest')?>'
				},
				success: function (result) {
					// console.log(result)
				}
			})
		})

		$(document).on('click', '.delete-interest', function () {
			id = $(this).parent().parent().attr('id').replace('interest_', '');
			// console.log(111)
			if (!confirm('Вы уверены, что хотите удалить эту запись? Это действие будет невозможно отменить.')) {
				return;
			}

			$.ajax({
				url: '<?= $link ?>/inc/interests-groups.php',
				method: 'POST',
				cache: false,
				data: {
					loc: loc,
					html: true,
					type: 'delete-interest',
					interest_id: id,
					user_id: '<?= $local_user_id ?>',
					secret_id: '<?= md5('user_' . $user_token . '_deleteInterest')?>'
				},
				success: function (result) {
					console.log(result)
					getUserInerests();
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
		$('.editInterest .section_span img').click(function () {
			$('.editInterest .section_span input').focus();
		})

		// Показ списка УУ при нажатии на input
		$('.editInterest .section_span input').on('focus', function () {
			$('.editInterest .section_span').addClass('subsections-show');
		})

		// Поиск УУ
		$('.editInterest .section_span input').on('input keyup', function () {
			// console.log(1)
			$('.editInterest .section_span ul li').removeClass('hidden')
			// $('.editInterest .section_span ul h5').remove();
			// $('.editInterest .section_span ul a').remove();

			if ($(this).val() != '') {
				result = 0;
				for (li_eq = 0; li_eq <= $('.editInterest .section_span ul li').length; li_eq++) {
					if ( $('.editInterest .section_span ul li:eq(' + li_eq + ')').text().toLowerCase().indexOf($(this).val().toLowerCase()) == -1) {
						$('.editInterest .section_span ul li:eq(' + li_eq + ')').addClass('hidden');
					} else {
						result++;
					}
				}
				if (result == 0) {
					// изменено: эти надписи добавляются изначально
					// $('.editInterest .section_span ul').append('<h5>Не нашли своего? </h5>');
					// $('.editInterest .section_span ul').append('<a href="<?= $link ?>/support?v=add_new_uni"><div>Добавить моё учебное заведение</div></a>')
				}
			} 
			
		})

		// Фокус на input после нажатия на УУ
		$('.editInterest .section_span ul, .editInterest .section_span ul li').click(function () {
			$('.editInterest .section_span input').focus();
			// $('.col-2 .section_span').addClass('subsections-show');
		})

		$('.editInterest .section_span ul li').click(function () {
			$('.editInterest .section_span input').val($(this).attr('title'));
			// console.log($(this).text());
		})

		$('.editInterest .section_span :input').blur(function() {
		    setTimeout(function () {
	    		if ($('.editInterest .section_span :focus').length === 0) {
	    			$('.editInterest .section_span').removeClass('subsections-show');
	    		}
		    }, 250)
		});

		function showErrorMessage (text) {
			$('.editInterest .error-message').text(text);

			setTimeout(function () {
				$('.editInterest .error-message').text('')
				// $('.editInterest .error-message').animate({opacity: 0}, 200);
				// setTimeout(function () {
				// 	$('.editInterest .error-message').text('').css({'opacity' : '1'});
				// }, 200)
			}, 1000);
		}

		$('.editPanel .editInterest .publish').click(function () {
			group = $('.editInterest .section_span input').val();
			title = $('.editInterest .row-1 .col-2 input').val();
			body = $('.editInterest .row-2 textarea').val();
			loc = $('.editInterest select').val();

			groups_lenght = $('.editInterest .section_span ul li').length;
			group_flag = 0;
			
			for (let i = 0; i < groups_lenght; i++) {
				if ($('.editInterest .section_span ul li:eq(' + i + ')').text() == group) {
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

			if ($('.editInterest input:checked').length != 0) {
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
					secret_id: '<?= md5('user_' . $user_token . '_editInterest')?>'
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
		select_mobile_footer_tab('interests');
	</script>
</body>
</html>