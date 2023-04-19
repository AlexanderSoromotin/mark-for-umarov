<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=2';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Поддержка</title>
	
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">


	<main>
		<!-- <h3>Как мы можем Вам помочь?</h3> -->
		<!-- <div class="page_title">
			Служба поддержки
		</div> -->
		<div class="tabs">
			<ul>
				<li class="tab" id="tab_knowledge_base">
					База знаний
				</li>
				<li class="tab" id="tab_tickets">
					Тикеты
				</li>
			</ul>
		</div>

		<div class="screen" id="screen_tickets">
			<center>
				<div class="title">
					Служба поддержки пока не работает
				</div>
			</center>

			<center>
				<button class="button-5">Создать обращение</button>	
			</center>

		</div>
		<div class="screen" id="screen_knowledge_base">
			<center>
				<div class="title">
					Ответы на популярные вопросы
				</div>
			</center>

			<div class="popular_questions">
				<div class="question">
					<div class="question_text">Почему моего города нет в списке городов?</div>
					<div class="answer_text">Скорее всего дело в том, что ни одно учебное заведение из Вашего города не подключено к системе FINDSTUDENTS</div>
				</div>

				<div class="question">
					<div class="question_text">Почему моего учебного заведения нет в списке?</div>
					<div class="answer_text">Ваше учебное заведение не подключено к нашей системе. Напишите нам об этом и мы попробуем провести переговоры
					<br>
					<button class="button-3">Написать</button></div>
				</div>
			</div>

		</div>

		

		

	</main>



	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		function selectTab (tab_name) {
			$('.screen, .tab').removeClass('opened');
			$('#screen_' + tab_name).addClass('opened');
			$('#tab_' + tab_name).addClass('opened');
		}
		selectTab('knowledge_base');
		select_mobile_footer_tab('settings');

		$('.tab').click(function () {
			selectTab($(this).attr('id').replace('tab_', ''));
		})

		$('.question').click(function() {
			if ($(this).hasClass('opened_question')) {
				$(this).removeClass('opened_question')
			} else {
				$(this).addClass('opened_question')
			}
		})
	</script>
</body>
</html>