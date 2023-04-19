<?php
include_once "config.php";
include_once "userData.php";

// echo $_POST['type'];
$image_quality = 70;

function translit ($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
  }


if ($_POST['type'] == 'upload-user-avatar') {  

	$uploaddir = '../uploads/temp';
	$uploaddir_userAvatar = '../uploads/user_avatars';

	if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
	if( ! is_dir( $uploaddir_userAvatar ) ) mkdir( $uploaddir_userAvatar, 0777 );

	$files = $_FILES;

	// переместим файлы из временной директории в указанную
	foreach( $files as $file ){
		$stock_file_name = $_POST['file_name'];
		if ($_POST['user_id'] != '') {
			$needle_file_name = md5($_POST['user_id'] . '_image_salt');
		} else {
			$needle_file_name = md5($user_id . '_image_salt');
		}
		

		if ($stock_file_name == '' or $needle_file_name == '') {
			exit();
		}

		$dir = '../uploads/temp';

		$getMime = explode( '.', $stock_file_name );
		$mime = '.' . end( $getMime );

		if ($mime == '.gif') {
			if ($user_status == 'Admin' or $user_gif_photo == 1) {

			} else {
				$output = array();
				$output['success'] = false;
				$output['response'] = "cant set gif image";
				echoJSON($output);
				exit();
			}

		} 

		if( move_uploaded_file( $file['tmp_name'], "$dir/$needle_file_name" . $mime) ){
			$done_files[] = realpath( "$dir/$needle_file_name" . $mime);
		}

		$url_to_file = '../uploads/temp/' . $needle_file_name . $mime;
		// echo 'mime - ' . $mime . ' - ';
		// Сжатие изображения
		if (mb_strpos("png bmp jpeg xbm jpg", mb_strtolower(str_replace('.', '', $mime))) !== false) {

			if ($mime == '.png') {
				$image = imagecreatefrompng($url_to_file);
			}
			if ($mime == '.bmp') {
				$image = imagecreatefrombmp($url_to_file);
			}
			if ($mime == '.jpeg') {
				$image = imagecreatefromjpeg($url_to_file);
			}
			if ($mime == '.gif') {
				$image = imagecreatefromgif($url_to_file);
			}
			if ($mime == '.webp') {
				$image = imagecreatefromwebp($url_to_file);
			}
			if ($mime == '.xbm') {
				$image = imagecreatefromxbm($url_to_file);
			}
			if ($mime == '.jpg') {
				rename('../uploads/temp/' . $needle_file_name . $mime, '../uploads/temp/' . $needle_file_name . '.jpeg');
				$mime = '.jpeg';
				$url_to_file = '../uploads/temp/' . $needle_file_name . $mime;
				$image = imagecreatefromjpeg($url_to_file);
			}

			$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
			imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
			imagealphablending($bg, TRUE);
			imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
			imagedestroy($image);
			$quality = 50;
			imagejpeg($bg, '../uploads/temp/' . $needle_file_name . ".jpg", $quality);
			imagedestroy($bg);

			unlink('../uploads/temp/' . $needle_file_name . $mime);
			// $stock_file_name = str_replace($mime, '.jpg', $stock_file_name);
			// echo $stock_file_name;

			$mime = '.jpg';
		}
	}
	echo json_encode(array("success" => true, "link" => $link . '/uploads/temp/' .  $needle_file_name . $mime, "file_name" => $needle_file_name . $mime), JSON_UNESCAPED_UNICODE);
}

