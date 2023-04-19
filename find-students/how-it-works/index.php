<?php
$cache_ver = '?v=3';

include_once '../inc/config.php';
include_once '../inc/userData.php';
// include_once '../inc/redirect.php';

// redirect('banned', '/banned');
// redirect('pre-deleted', '/pre-deleted');
// redirect('unlogged', '/authorization');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Как пользоваться сервисом</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta property="og:image" content="<?= $link ?>/assets/img/findstudents.jpg">
	<meta property="og:image:width" content="968">
</head>
<body>
	<?
		include_once '../inc/head.php';
		if ($userLogged) {
			include_once '../inc/header.php';
		} else {
			echo '<a class="back" href="' . $link . '"><img src="' . $link . '/assets/img/icons/arrow-left.svg">FINDSTUDENTS</a>';
		}
	?>


	<main>
		<div class="content">
	        <div class="section">
	            <!-- <div class="sectionName1"><div class="title">Информация для студентов</div></div> -->
	            <!-- <div class="section1"> -->

                <div class="sectionName2">Как отметиться?</div>
                <div class="section2">
                    <div class="text">
                        1. Перейдите в раздел <b>«Отметка»</b>.
                    </div>
                    <div class="text">
                        2. Нажмите <b>«Я нахожусь на занятии!»</b>.
                    </div>

                    <div class="text">
                        (Отметка не спадёт, пока Вы сами того не пожелаете или пока её не уберёт староста.
                        До тех пор Вы будете присутствовать на всех следующих парах.)
                    </div>
                </div>

	            <!-- </div> -->

	            <div class="sectionName2">Что делать, если я попал не в ту группу?</div>
	            <div class="section2">
	                <div class="text">
	                    Перейдите в настройки → Учебное учреждение и группа → Покинуть группу → Подайте заявку в свою
	                    группу.
	                </div>
	            </div>
	        <!-- </div> -->

	        <!-- <div class="section"> -->
	            <div class="sectionName1"><div class="title">Информация для старост</div></div>

	            <!-- <div class="section1"> -->
	                <div class="sectionName2">Как добавить всех в группу?</div>
	                <div class="section2">
	                    <div class="text">
	                        Всё довольно просто. Как у старосты, у Вас должен появиться внизу новый раздел
	                        <b>«Староста»</b>.
	                        Там будет надпись: <b>«Пригласить людей»</b>.
	                        Вам нужно нажать на неё, скопировать ссылку и закинуть в беседу, откуда все смогут
	                        присоединиться!
	                    </div>
	                </div>

	                <div class="sectionName2">Как сформировать отчёт по присутствующим?</div>
	                <div class="section2">
	                    <div class="text">
	                        В том же самом разделе <b>«Староста»</b> нажмите <b>«Сформировать отчёт»</b>. Появится ссылка,
	                        которую можно перекинуть преподавателю в мессенджере или по почте.
	                        Перейдя по ней, он увидит сам отчёт, где будут отображены: дата, группа, присутствующие и
	                        отсутствующие студенты.
	                    </div>
	                </div>

	                <div class="sectionName2">Что делать, если человек не может отметиться?</div>
	                <div class="section2">
	                    <div class="text">
	                        Ты можешь сделать это самостоятельно. В разделе <b>«Староста»</b> около каждого студента есть
	                        кнопка, позволяющая отметить его.
	                        Напротив, если его нет, но он числится присутствующим, то нажав на минус рядом с его именем,
	                        можно
	                        убрать отметку.
	                    </div>
	                    <div class="text">
	                        Если что-то не понятно, Вы всегда можете спросить нас в беседе
	                        группы или через администраторов. Приятного пользования!
	                    </div>
	                </div>
	            <!-- </div> -->
	        </div>
	    </div>
	</main>

	<script type="text/javascript">
		
	</script>

	<?
		if ($userLogged) {
			include_once '../inc/mobile_toolbar.php';
		}
	?>

	<script>
		select_mobile_footer_tab('settings');
	</script>

</body>
</html>