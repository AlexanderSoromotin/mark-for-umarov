<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('Banned', '/banned');
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/authorization');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>Уведомления</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="mobile.css">
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
			<a href="<?$link?>/notifications">Уведомления</a>
		</div>
	</div>

	<div class="main">
		<div class="all-notifications">
			<h2>Уведомления</h2>
			<ul>
				<li class="empty"><h4>Тут ничего нет</h4></li>
			</ul>
		</div>
	</div>


		
	<script type="text/javascript">
		$.ajax({
    	url: '<?= $link ?>/inc/notifications.php',
    	cache: false,
    	type: "POST",
    	data: {
    		secret_id: '<?= md5('user_' . $user_token . '_getNotifications')?>',
    		type: 'get-all',
    		limit: 200
    	},
    	success: function (result) {
    		// console.log('ok');
    		if (result != '') {
    			$('.empty').remove();
    		}
    		// console.log(result);
    		checkUserNotifications();
    		$('.main .all-notifications ul').prepend(result);
    		// console.log('loaded all notifications');
    	}
    })
	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
	<script type="text/javascript">
		select_mobile_footer_tab('notifications');
	</script>
</body>
</html>