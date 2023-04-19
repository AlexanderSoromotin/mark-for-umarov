<?php
$cache_ver = '?v=7';

include_once '../inc/config.php';
// include_once '../inc/userData.php';
$report_text_id = $_GET['id'];

$report_id_invalid = false;

$report_data = mysqli_query($connection, "SELECT * FROM `visits_reports` WHERE `text_id` = '$report_text_id'");

if ($report_data -> num_rows != 0) {
	$report_data = mysqli_fetch_assoc($report_data);
	$group_id = $report_data['group_id'];
	$group_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `groups` WHERE `id` = '$group_id'"));

	$head_student_id = $group_data['head_student'];

	$specialization_id = $group_data['specialization_id'];
	$specialization_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `specializations` WHERE `id` = '$specialization_id'"));

	$faculty_id = $specialization_data['faculty_id'];
	$faculty_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `faculties` WHERE `id` = '$faculty_id'"));

	$education_id = $faculty_data['education_id'];
	$education_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `education` WHERE `id` = '$education_id'"));

	$report_archive = json_decode($report_data['archive'], 1);
	
} else {
	$report_id_invalid = true;
}

if ($report_id_invalid):
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Отчёт недействителен</title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta property="og:image" content="<?= $link ?>/assets/img/findstudents.jpg">
	<meta property="og:image:width" content="968">
</head>
<body>
	<?
		include_once '../inc/head.php';
		
		echo '<a class="back" href="' . $link . '"><img src="' . $link . '/assets/img/icons/arrow-left.svg">FINDSTUDENTS</a>';

	?>


	<main>
		<div class="group_invites">
			<div class="empty">
				Этот отчёт недействителен
			</div>
		</div>
	</main>

	<script type="text/javascript">
		
	</script>
</body>
</html>

<? else: ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Отчёт посещения <?= $group_data['title'] . ' на ' . date('d') . ' ' . $months_accusative[date('m')]  . ' ' . date('Y ') . 'г.' ?></title>
	<link rel="stylesheet" type="text/css" href="style.css<?= $cache_ver ?>">
	<link rel="shortcut icon" href="<?= $link ?>/assets/img/mark_icon.png" type="image/png">
	<meta property="og:image" content="<?= $link ?>/assets/img/findstudents.jpg">
</head>
<body>
	<?	
		include_once '../inc/head.php';

		$report_date = $report_data['date'];
		$report_day = mb_substr($report_date, 0, 2);
		$report_month = mb_substr($report_date, 3, 2);
		$report_year = mb_substr($report_date, 6, 4);

		$report_time = mb_substr($report_date, 10);

		// $students_list = array_merge($report_archive['present_students'], $report_archive['missing_students']);

		$present_students = array();
		$missing_students = array();

		// echo json_encode($report_archive['present_students'], JSON_UNESCAPED_UNICODE);
		// echo '<br><br>';
		// echo json_encode($report_archive['missing_students'], JSON_UNESCAPED_UNICODE);

		// echo 1;

		foreach ($report_archive['present_students'] as $key => $value) {
			// echo gettype($value);
			if (gettype($value) != 'array') {
				$student_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT CONCAT(`last_name`, '-=delemiter=-', `first_name`) FROM `users` WHERE `id` = '$value' "))["CONCAT(`last_name`, '-=delemiter=-', `first_name`)"];

				$student_data = array(
					"student_id" => $value,
					"student_name" => explode('-=delemiter=-', $student_data)[1],
					"student_surname" => explode('-=delemiter=-', $student_data)[0]
				);
			} else {
				$student_data = array(
					"student_id" => $value['student_id'],
					"student_name" => $value['student_name'],
					"student_surname" => $value['student_surname']
				);
			}

			array_push($present_students, $student_data);
		}

		$tmp_present_student_sort_by_surname = array();
		foreach ($present_students as $key => $value) {
			array_push($tmp_present_student_sort_by_surname, $value['student_surname']);
		}
		array_multisort($tmp_present_student_sort_by_surname, SORT_ASC, $present_students);

		foreach ($report_archive['missing_students'] as $key => $value) {
			if (gettype($value) != 'array') {
				$student_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT CONCAT(`last_name`, '-=delemiter=-', `first_name`) FROM `users` WHERE `id` = '$value' "))["CONCAT(`last_name`, '-=delemiter=-', `first_name`)"];

				$student_data = array(
					"student_id" => $value,
					"student_name" => explode('-=delemiter=-', $student_data)[1],
					"student_surname" => explode('-=delemiter=-', $student_data)[0]
				);
			} else {
				$student_data = array(
					"student_id" => $value['student_id'],
					"student_name" => $value['student_name'],
					"student_surname" => $value['student_surname']
				);
			}

			array_push($missing_students, $student_data);
		}

		$tmp_missing_student_sort_by_surname = array();
		foreach ($missing_students as $key => $value) {
			array_push($tmp_missing_student_sort_by_surname, $value['student_surname']);
		}
		array_multisort($tmp_missing_student_sort_by_surname, SORT_ASC, $missing_students);

		// echo json_encode($present_students, JSON_UNESCAPED_UNICODE);
		// echo '<br><br>';
		// echo json_encode($missing_students, JSON_UNESCAPED_UNICODE);
		// exit();

		// $students_list_text = '(' . implode(',', $students_list) . ')';
		// $students_list_array = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` IN " . $students_list_text . " ORDER BY `last_name`");

		// $sorted_present_student = array();
		// $sorted_missing_student = array();

		$head_student_name = mysqli_fetch_assoc(mysqli_query($connection, "SELECT CONCAT(`last_name`, ' ', `first_name`) FROM `users` WHERE `id` = '$head_student_id' "))["CONCAT(`last_name`, ' ', `first_name`)"];


		// while ($s = mysqli_fetch_assoc($students_list_array)) {
		// 	if (!in_array($s['id'], $report_archive['present_students'])) {
		// 		$sorted_missing_student[$s['id']] = array(
		// 			"name" => $s['last_name'] . ' ' . $s['first_name']
		// 		);
		// 		if ($s['id'] == $group_data['head_student']) {
		// 			$head_student_name = $s['last_name'] . ' ' . $s['first_name'];
		// 		}
		// 	} else {
		// 		$sorted_present_student[$s['id']] = array(
		// 			"name" => $s['last_name'] . ' ' . $s['first_name']
		// 		);
		// 		if ($s['id'] == $group_data['head_student']) {
		// 			$head_student_name = $s['last_name'] . ' ' . $s['first_name'];
		// 		}
		// 	}
		// }
	?>


	<main>
		<div class="back_to_mark_container">
			<a class="back" href="<?= $link ?>">
				<img src="<?= $link ?>/assets/img/icons/arrow-left.svg">MARK
			</a>
		</div>

		<h3>Отчёт посещения <?= $group_data['title'] . ' на ' . date('d') . ' ' . $months_accusative[date('m')]  . ' ' . date('Y ') . 'г.' ?></h3>

		<p class="report_date local_title">Точная дата: <?= $report_time . ' (МСК) ' . $report_day . ' ' . $months_accusative[$report_month] . ' ' . $report_year . ' года' ?></p>

		<p class="local_title">Присутствующие студенты (<?= count($report_archive['present_students']) ?>)</p>
		<ol>
			<?
				foreach ($present_students as $student_id => $student_info) {
					echo '<li>' . $student_info['student_surname'] . ' ' . $student_info['student_name'] . '</li>';
				}
			?>
		</ol>
		<br>
		<p class="local_title">Отсутствующие студенты (<?= count($report_archive['missing_students']) ?>)</p>
		<ol>
			<?
				foreach ($missing_students as $student_id => $student_info) {
					echo '<li>' . $student_info['student_surname'] . ' ' . $student_info['student_name'] . '</li>';
				}
			?>
		</ol>
		<br>
		<!-- <hr> -->
		<div class="details details_opened">
			<div class="title">
				Детальная информацию о группе
				<img src="<?= $link ?>/assets/img/icons/chevron-down.svg">
			</div>
			<div class="scroll_block">
				<p class="local_title">Учебное учреждение</p>
				<p><?= $education_data['title'] ?></p>

				<p class="local_title">Факультет</p>
				<p><?= $faculty_data['title'] ?></p>

				<p class="local_title">Специальность</p>
				<p><?= $specialization_data['title'] ?></p>

				<p class="local_title">Группа</p>
				<p><?= $group_data['title'] ?></p>
				<p><?= count(json_decode($group_data['students'])) . ' ' . caseOfWords(count(json_decode($group_data['students'])), 'студентов') ?></p>
				<p>Староста - <?= $head_student_name ?></p>
			</div>
		</div>
		
		<!-- <p><?= $group_data['title'] ?></p> -->
	</main>

	<script type="text/javascript">
		$('.details .title').click(function () {
			if ($('.details').hasClass('opened_details')) {
				$('.details').removeClass('opened_details')
			} else {
				$('.details').addClass('opened_details')
			}
		})
	</script>
</body>
</html>

<? endif; ?>

