<?php

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
// redirect('unlogged', '/authorization');

$cache_ver = '?v=11';

$local_user_was_found = false;

if ($_GET['id'] != '') {
	$local_user_id = $_GET['id'];
	$local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'");
	if ($local_user_data -> num_rows != 0) {
		$local_user_data = mysqli_fetch_assoc($local_user_data);
		$local_user_was_found = true;
	}
} else {
	$local_user_id = $user_id;
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
		select_mobile_footer_tab('profile');	
	</script>
</body>
</html>

<? else: 

$local_user_photo_style = unserialize($local_user_data['photo_style']);
$local_user_group_id = $local_user_data['group_id'];

$group_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$local_user_group_id'"));

$specialization_id = $group_data['specialization_id'];
$specialization_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `specializations` WHERE `id` = '$specialization_id'"));

if ($specialization_data['title'] == '') {
	$specialization_data['title'] = 'Не выбрано';
}

$faculty_id = $specialization_data['faculty_id'];
$faculty_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `faculties` WHERE `id` = '$faculty_id'"));

if ($faculty_data['title'] == '') {
	$faculty_data['title'] = 'Не выбрано';
}

$education_id = $faculty_data['education_id'];
$education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));

if ($education_data['title'] == '') {
	$education_data['title'] = 'Не выбрано';
}

$local_user_status = 'Студент';
if ($group_data['deputy_head_student'] == $local_user_id) {
	$local_user_status = 'Заместитель старосты';
}
if ($group_data['head_student'] == $local_user_id) {
	$local_user_status = 'Староста';
}
if ($local_user_data['status'] == 'Admin') {
	$local_user_status = 'Администратор';
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
</head>
<body>
	<?
		include_once '../inc/head.php';
		include_once '../inc/header.php';
	?>


	<main>
		<div class="background_avatar">
			<img src="<?= $local_user_data['photo'] ?>">
		</div>

		<div class="profile_info">
			<div class="personal_info">
				<div class="avatar">
					<img draggable="false" style="<?= $local_user_photo_style['ox_oy']?>transform: scale(<?= $local_user_photo_style['scale'] ?>);" src="<?= $local_user_data['photo'] ?>">
				</div>
				<? if ($local_user_id == $user_id or $user_status == 'Admin'): ?>
					<a href="<?= $link ?>/edit-user-info?id=<?= $local_user_id ?>">
						<div class="edit_profile">
							<img src="<?= $link ?>/assets/img/icons/pencil.svg">
						</div>
					</a>
				<? endif; ?>
				<div class="name_status">
					<p class="user_name"><?= $local_user_data['last_name'] . ' ' . $local_user_data['first_name'] ?></p>
					<p class="user_status"><?= $local_user_status ?></p>
				</div>
			</div>

			<? if (($user_status == 'Admin' or $user_id == $local_user_id) or $local_user_data['closed_profile'] == 0): ?>

			<div class="local_info">
				<div class="registration_date">
					<p>Зарегистрирован</p>
					<p><?= $months[mb_substr($local_user_data['registration_date'], 5, 2)] . ' ' . mb_substr($local_user_data['registration_date'], 0, 4) ?></p>
				</div>

				<div class="days_of_study">
					<p>Дней на занятиях</p>
					<p>
						<?	
							$like_query = '%"' . $local_user_id . '":{"%';
							$archive = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) FROM `visits_archive` WHERE `group_id` = '$local_user_group_id' and `students` LIKE '$like_query'"))['COUNT(*)'];

							echo $archive;
							// echo '-';


						?>
					</p>
				</div>
			</div>
			<div class="info_about_education">
				<label>Учебное учреждение</label>
				<p><?= $education_data['title'] ?></p>
				<label>Факультет</label>
				<p><?= $faculty_data['title'] ?></p>
				<label>Специальность</label>
				<p><?= $specialization_data['title'] ?></p>
				<label>Группа</label>
				<p><?= $group_data['title'] ?><!-- Скрыто --></p>
			</div>

			<? else: ?>

			<div class="closed_profile">
				Это закрытый профиль
			</div>

			<? endif ?>

		</div>
	</main>

	<script type="text/javascript">
		$(".profile_info").scroll(function () {
			console.log(1)
		})
	</script>

	<?
		include_once '../inc/mobile_toolbar.php';
	?>

	<script>
		<? if ($_GET['from'] == 'groups'): ?>
			select_mobile_footer_tab('groups');
		<? elseif ($_GET['from'] == 'head_student_page'): ?>
			select_mobile_footer_tab('head_student');
		<? else : ?>
			select_mobile_footer_tab('profile');
		<? endif; ?>
	</script>
</body>
</html>

<? endif; ?>