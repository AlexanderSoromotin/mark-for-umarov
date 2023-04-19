<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
redirect('unlogged', '/authorization');

$cache_ver = '?v=4';

$local_user_was_found = false;

if ($_GET['id'] != '') {
	$local_user_id = $_GET['id'];
	$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");
	if ($local_user_data -> num_rows != 0) {
		$local_user_data = mysqli_fetch_assoc($local_user_data);
		$local_user_was_found = true;
	}
} else {
	$local_user_data = $result;
	$local_user_was_found = true;
}

if (!$local_user_was_found) : 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Пользователь не найден</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="empty">
			Профиль не найден
		</div>
	</main>

	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		select_mobile_footer_tab('settings');	
	</script>
</body>
</html>

<? else: 

$local_user_photo_style = unserialize($local_user_data['photo_style']);
$local_user_group_id = $local_user_data['group_id'];

$group_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$local_user_group_id'"));

$specialization_id = $group_data['specialization_id'];
$specialization_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `specializations` WHERE `id` = '$specialization_id'"));

$faculty_id = $specialization_data['faculty_id'];
$faculty_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `faculties` WHERE `id` = '$faculty_id'"));

$education_id = $faculty_data['education_id'];
$education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));

$local_user_status = 'Студент';
if ($local_user_data['status'] == 'Admin') {
	$local_user_status = 'Администратор';
}
if ($group_data['deputy_head_student'] == $local_user_id) {
	$local_user_status = 'Заместитель старосты';
}
if ($group_data['head_student'] == $local_user_id) {
	$local_user_status = 'Староста';
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Редактрование профиля (<?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?>)</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/fs_icon_64_br_20.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="change_avatar">
			<div class="avatar">
				<img draggable="false" style="<?= $local_user_photo_style['ox_oy']?>transform: scale(<?= $local_user_photo_style['scale'] ?>);" src="<?= $local_user_data['photo'] ?>">
			</div>
			<div class="control">
				<label style="display: block;text-align: center;margin-bottom: 10px;" for="user-avatar" class="button-3 upload_avatar">
					Загрузить изображение
				</label>

				<input id="user-avatar" class="" type="file" name="avatar" accept=".jpg,.jpeg,.png,.gif,.tiff,.bmp,.raw,.xbm,.webm,.webp">

				<!-- <button class="edit_avatar_size button-5">
					Изменить миниатюру
				</button> -->

				<label>Изменение миниатюры</label>
				<input type="range" name="avatar_scale" min="0.2" max="3" step="0.01" value="<?= $local_user_photo_style['scale'] ?>">

				<!-- <button class="button-1">
					Удалить изображение
				</button> -->
			</div>
		</div>

		<div class="change_username">
			<div>
				<label>Фамилия</label>
				<input type="" name="surname" value="<?= $local_user_data['last_name'] ?>">
			</div>

			<div>
				<label>Имя</label>
				<input type="" name="first_name" value="<?= $local_user_data['first_name'] ?>">
			</div>

			<div>
				<label>Эл. почта</label>
				<input readonly class="uneditable" type="" name="first_name" value="<?= $local_user_data['email'] ?>" >
			</div>
		</div>

		<div class="profile_type">
			<p class="title">Доступность профиля</p>
			<? if ($local_user_data['closed_profile'] == 0): ?>
				<button class="open_profile selected">Открытый</button>
				<button class="private_profile">Закрытый</button>
			<? else: ?>
				<button class="open_profile">Открытый</button>
				<button class="private_profile selected">Закрытый</button>
			<? endif; ?>
		</div>

		<button class="save_changes button-1">Сохранить изменения</button>

		<div class="popup_menu">
			<div class="popup_container edit_avatar_size_popup">
				<div class="popup_header">
					<h1>Изменение миниатюры</h1>
					<img class="close_popup_menu" src="<?= $link ?>/assets/img/icons/x.svg">
				</div>	
				<div class="popup_content">
					<div class="avatar_block">
						<img draggable="false" style="" src="<?= $local_user_data['photo'] ?>">
						<!-- <div class="avatar_border"></div> -->
						
					</div>

					
					<button class="save_avatar_size button-1">Сохранить</button>
					<!-- <p class="description">Все предыдущие ссылки перестанут работать</p> -->
				</div>
			</div>
		</div>
	</main>

	<!-- <div class="edit_thumbnail">
	</div> -->	

	<script type="text/javascript">
		
	</script>

	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		// setHeaderTitle('Редактирование профиля')
		function getRandomInt (max) {
			return Math.floor(Math.random() * max);
		}

		$('.upload_avatar').click(() => {
			$('input[name="avatar"]').click();
		})
		select_mobile_footer_tab('settings');

		function showSaveNotification () {
			$('.save_changes').removeClass('button-1').addClass('button-3');
		}

		$('input').on('keydown', () => showSaveNotification());

		$('.profile_type button').click((e) => {
			$('.profile_type button').removeClass('selected');
			$(e.target).addClass('selected');
			showSaveNotification();
		});

		$('input[name="avatar_scale"]').on('input keydown', function () {
			$('.avatar img').css({"transform" : "scale(" + $(this).val() + ")"});
			console.log(12);
			showSaveNotification();
		})

		$('input[name="avatar"]').on("change", function () {
			file = $(this).prop('files')[0]; 
			tmp_lenght = file['name'].split('.').length
			mime = file['name'].split('.')[tmp_lenght - 1];

			console.log($(this), file, mime);

			if (!mime.indexOf('png bmp jpeg xbm gif jpg')) {
				alert('Выберите изображение формата png, bmp, или jpg');
				return;
			}
			if (mime == 'gif' && '<?= $user_status ?>' != 'Admin' && '<?= $user_gif_photo ?>' != '1' ) {
				alert('Выберите изображение формата png, bmp, или jpg');
				return;
			}

			var formData = new FormData();
			formData.append(0, file);
			formData.append('type', 'upload-user-avatar');
			formData.append('file_name', file['name']);
			<? if ($local_user_id != $user_id): ?>
				formData.append('user_id', '<?= $local_user_id ?>');
			<? endif; ?>
			// console.log(file['name'])

			$('.avatar').append("<div class='progress_bar_1'><div class='progress_bar_line'><div class='progress_bar_line_background'></div></div></div>");
			setTimeout(() => {
				$('.avatar .progress_bar_1').css({"opacity": "1"});
			}, 20)

			$.ajax({
				url: "<?= $link ?>/inc/uploadFiles.php",
				type: "POST",
				cache: false,
				data: formData,
				processData : false,
				contentType : false,
				xhr: function () {
			        let xhr = $.ajaxSettings.xhr();
			        xhr.upload.addEventListener('progress', function (e) {
			        	if (e.lengthComputable) {
			            	let percentComplete = Math.ceil(e.loaded / e.total * 100);
			            	// $('.opened_chat .input-form label').text('Загружено ' + percentComplete + '%');
			            	$('.avatar .progress_bar_line_background').css({"width" : percentComplete + '%'});
			        	}
			        }, false);
			        return xhr;
			    },
				success: function (result) {
					console.log('загрузка файлов', result)
					if (result != '') {
						result = JSON.parse(result)
						console.log('загрузка файлов json:', result);

						if (result['success']) {
							$('.avatar img').attr('style', '').attr('src', result['link'] + '?v=' + getRandomInt(10000000)).attr('file_name', result['file_name']);
							showSaveNotification();
							$('.avatar .progress_bar_1').css({"opacity": "1"});
							setTimeout(() => {
								$('.avatar .progress_bar_1').remove();
							}, 210)
							$('input[name="avatar_scale"]').val(1);
							$('.change_avatar .avatar img').css({'transform': 'scale(1)'});
						}
					}
				}
			})
		});

		$('.save_changes').click(function () {
			if ($(this).hasClass('button-3')) {
				user_surname = $('input[name="surname"]').val();
				user_first_name = $('input[name="first_name"]').val();
				user_avatar_url = $('.avatar img').attr('file_name');
				user_profile_type = $('.profile_type .selected').text();
				user_avatar_scale = $('input[name="avatar_scale"]').val();

				$.ajax({
					url: "<?= $link ?>/api/editProfile.php",
					type: "POST",
					cache: false,
					data: {
						type: 'save-info',
						token: '<?= $user_token ?>',
						user_id: '<?= $local_user_id?>',
						user_surname: user_surname,
						user_first_name: user_first_name,
						user_avatar_url: user_avatar_url,
						user_profile_type: user_profile_type,
						user_avatar_scale: user_avatar_scale
					},
					success: function (result) {
						console.log('сохранение личной информации', result)
						result = JSON.parse(result);
						console.log('сохранение личной информации json:', result)
						if (result['success']) {
							$('.save_changes').removeClass('button-3').addClass('button-1');
						}
					}
				})
			}
		})

		$('.edit_avatar_size').click(() => {
			$('.popup_container').removeClass('opened_popup_container');
			// $('.popup_menu').addClass('opened_popup_menu');
			// $('.edit_avatar_size_popup').addClass('opened_popup_container');
		})

		$('.close_popup_menu').click(() => {
			$('.popup_container').removeClass('opened_popup_menu');
			$('.popup_menu').removeClass('opened_popup_menu');
		})

	</script>

	<? if ($local_user_id == 96 or $local_user_id == 96): ?>
		<style type="text/css">
			.hidden_input {
				display: block !important;
			}
		</style>
	<? endif; ?>
</body>
</html>

<? endif; ?>