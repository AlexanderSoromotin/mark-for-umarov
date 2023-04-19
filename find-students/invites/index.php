<?php
$cache_ver = '?v=2';

include_once '../inc/config.php';
include_once '../inc/userData.php';
include_once '../inc/redirect.php';

redirect('banned', '/banned');
redirect('pre-deleted', '/pre-deleted');
// redirect('unlogged', '/authorization');

$local_user_was_found = false;
$invitation_is_invalid = false;

if ($_GET['fs_invite'] != '') {
	$text_id = $_GET['fs_invite'];
	$invite_data = mysqli_query($connection, "SELECT * FROM `invites` WHERE `text_id` = '$text_id'");

	if ($invite_data -> num_rows != 0) {
		$invite_data = mysqli_fetch_assoc($invite_data);

		if ($invite_data['expires'] == 0) {
			$group_id = $invite_data['group_id'];
			$group_data = mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'");

			if ($group_data -> num_rows != 0) {
				$group_data = mysqli_fetch_assoc($group_data);
			} else {
				$invitation_is_invalid = true;
			}
		} else {
			// Проверка действительности приглашения
		}
	} else {
		$invitation_is_invalid = true;
	}
} else {
	$invitation_is_invalid = true;
}

if ($invitation_is_invalid):
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Приглашение недействительно</title>
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
		<div class="group_invites">
			<div class="empty">
				Это приглашение больше не действует
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
		select_mobile_footer_tab('group');
		function getRandomInt (max) {
			return Math.floor(Math.random() * max);
		}

	</script>

</body>
</html>

<? else: ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Приглашение в группу <?= $group_data['title'] ?></title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta property="og:image" content="<?= $link ?>/assets/img/findstudents.jpg">
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
		<video looped autoplay muted >
			<source src="<?= $link ?>/assets/video/bg_640_360.mp4" type="video/mp4">
		</video>
		<div class="group_invites">
			<div class="group" id="group_<?= $group_id ?>">
				<div class="row-1">
					<div class="avatar">
						<img src="https://findcreek.com/assets/img/findstudents.jpg" style="transform: scale(1.2);">
					</div>
					<div class="info">
						<div class="group_name"><?= $group_data['title'] ?></div>
						<div class="students_info">
							<div class="bullet_point"></div>
							<?= count(json_decode($group_data['students'])) . ' ' . caseOfWords(count(json_decode($group_data['students'])), 'студентов') ?>
						</div>
						<div class="head_of_group_info">
							<img src="https://findcreek.com/assets/img/icons/crown.svg">
							<?
								$head_student_id = $group_data['head_student'];
								$head_student_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$head_student_id'"));
								echo $head_student_data['last_name'] . ' ' . mb_substr($head_student_data['first_name'], 0, 1) . '.';
							?>
						</div>
					</div>
				</div>

				<?
					if ($userLogged and $user_group_id == $group_id) {
						echo '<button class="button-5">В группе</button>';
					} else {
						echo '<button class="button-2">Вступить</button>';
					}
				?>
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
		<? if ($userLogged): ?>
			$('.button-2').click(function () {
				$.ajax({
					url: '<?= $link ?>/api/getEducationData.php',
					type: "POST",
					data: {
						type: 'accept-invite',
						token: '<?= $user_token ?>',
						invite_id: '<?= $text_id ?>'
					},
					success: function (result) {
						console.log('принятие приглашения', result)
						result = JSON.parse(result)
						console.log('принятие приглашения json:', result);

						if (result['success']) {
							location.href = "<?= $link ?>";
						}
					}
				})
			})
		<? else: ?>
			$('.button-2').click(function () {
				localStorage.setItem('accept-invite', '<?= $text_id ?>');
				location.href = "<?= $link ?>";
			})
		<? endif; ?>
		select_mobile_footer_tab('group');
		function getRandomInt (max) {
			return Math.floor(Math.random() * max);
		}

	</script>

</body>
</html>

<? endif; ?>

