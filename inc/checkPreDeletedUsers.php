<?
include_once '../inc/info.php';
include_once '../inc/db.php';
	

$users = mysqli_query($connection, "SELECT * FROM `users` WHERE `status` = 'pre-deleted'");
$current_day = (int) date('d');
$current_month = (int) date('m');
$current_year = (int) date('Y');

$mimes = array('jpg', 'jpeg', 'jpeg 2000', 'icon', 'svg', 'gif', 'bmp', 'webm', 'webp', 'png');

while ($u = mysqli_fetch_assoc($users)) {
	$id = $u['id'];

	$deleted_day = (int) substr($u['delete_account_date'], 0, 2);
	$deleted_month = (int) substr($u['delete_account_date'], 3, 2);
	$deleted_year = (int) substr($u['delete_account_date'], 6, 4);
	
	$planned_day = $deleted_day;
	$planned_month = $deleted_month + 6;
	$planned_year = $deleted_year;
	

	if ($planned_month > 12) {
		$planned_month -= 12;
		$planned_year++;
	}
		echo '<br> delete: ' . $id . '<br>';
		echo 'day planned - current: ' . $planned_day . ' - ' . $current_day . '<br>';
		echo 'month planned - current: ' . $planned_month . ' - ' . $current_month . '<br>';
		echo 'year planned - current: ' . $planned_year . ' - ' . $current_year . '<br>';

	// Сегодня полное удаление
	$today = ($planned_day == $current_day and $planned_month == $current_month and $planned_year == $current_year);

	// Тот же год, тот же месяц, но день больше
	$next_days = ($current_year == $planned_year and $current_month == $planned_month and $current_day > $planned_day);

	// Тот же год, но месяц больше
	$next_month = ($current_year == $planned_year and $current_month > $planned_month);

	// Год больше
	$next_year = ($current_year > $planned_year);

	if ($today or $next_days or $next_month or $next_year) {
		echo '<br> DELETED: ' . $id . '<br>';
		mysqli_query($connection, "UPDATE `users` SET `status` = 'deleted', `photo` = 'http://frmjdg.com/assets/img/deleted-user.png', `photo_style` = DEFAULT,`bg_image_style` = DEFAULT, `bg_image` = DEFAULT WHERE `id` = '$id'");

		foreach ($mimes as $key) {
			// Удвление фотографий пользователя
			if (file_exists('../uploads/user_bg_photo/user_bg_' . $id . '.' . $key) == 1) { 
				echo '<br> EXISTS: ' . $link . '/uploads/user_bg_photo/user_bg_' . $id . '.' . $key; 
				unlink('../uploads/user_bg_photo/user_bg_' . $id . '.' . $key);
			};

			if (file_exists('../uploads/user_photo/user_' . $id . '.' . $key) == 1) { 
				echo '<br> EXISTS: ' . $link . '/uploads/user_photo/user_' . $id . '.' . $key; 
				unlink('../uploads/user_photo/user_' . $id . '.' . $key);
			};

			if (file_exists('../uploads/user_' . $id . '.' . $key) == 1) { 
				echo '<br> EXISTS: ' . $link . '/uploads/user_' . $id . '.' . $key; 
				unlink('../uploads/user_' . $id . '.' . $key);
			};
			// unlink('user_bg_photo/user_' . $id . '.' . $key);
			// unlink('user_photo/user_' . $id . '.' . $key);
			// unlink('user_' . $id . '.' . $key);
		}
		
	}
}
