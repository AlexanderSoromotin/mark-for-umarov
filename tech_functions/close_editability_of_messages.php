<?php

include_once "../inc/db.php";
include_once "../inc/info.php";

$messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE `editability` = 1");

if ($messages -> num_rows == 0) {
	echo 'Нет сообщений, которые возможно редактировать';
	exit();
} 
else {
	$count = 0;
	while ($m = mysqli_fetch_assoc($messages)) {
		$message_date_year = substr($m['date'], 0, 4);
		$message_date_mounth = substr($m['date'], 5, 2);
		$message_date_day = substr($m['date'], 8, 2);

		$server_date_year = date('Y');
		$server_date_mounth = date('m');
		$server_date_day = date('d');

		$message_id = $m['id'];

		if (($message_date_year != $server_date_year) or ($message_date_year == $server_date_year and $message_date_mounth != $server_date_mounth) or ($message_date_year == $server_date_year and $message_date_mounth == $server_date_mounth and $message_date_day != $server_date_day)) {

			$count++;
			mysqli_query($connection, "UPDATE `messages` SET `editability` = 0 WHERE `id` = '$message_id'");
		}
	}
	echo 'Обработано ' . $count . ' сообщений';
}