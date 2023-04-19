<?php
include_once "info.php";
include_once "db.php";

// echo $_POST['type'];
$image_quality = 50;

function translit ($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
  }

if ($_POST['type'] == 'upload-file') {
	$user_id = decodeSecretID($_POST['secret_id'], "uploadFile");

	if ($user_id) {
		$uploaddir = '../uploads/user_files';
		if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );

		$files = $_FILES;
		$done_files = array();

		$file_id = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `user_files` ORDER BY `id` DESC LIMIT 0, 1"))['id'] + 1;

		$output = array();

		// переместим файлы из временной директории в указанную
		foreach( $files as $file ) {
			if ($file['size'] / 1024 / 1024 > 50) {
				echo 'file size exceeds 50 mb';
				exit();
			}
			$stock_file_name = $_POST['stock_filename'];

			$dir = '../uploads';

			$getMime = explode('.', $stock_file_name);
			$mime = '.' . end($getMime);

			// $needle_file_name = $file_id . '_' . translit(str_replace(' ', '_', preg_replace('/[^.-=+0\1-9 a-zа-яё\d]/ui', '', $stock_file_name)));
			$needle_file_name = $file_id . $mime;
			
			if ($stock_file_name == '') {
				exit();
			}

			if( move_uploaded_file( $file['tmp_name'], "$uploaddir/$needle_file_name") ){
				$done_files[] = realpath( "$dir/$needle_file_name");
			}

			$file_types = array(
				"img" => "- png bmp ecw gif ico ilbm jpeg mrsid pcx tga tiff webp xbm xps rla rpf pnm jpg jfif",
				"video" => "- mp4 mov wmv avi avchd flv f4v swf mkv webm html5 mpeg-2 vob ogv qt rmvb viv asf amv mpg mp2 mpeg mpe mpv",
			);

			$file_type = 'unknown';

			$url_to_file = '../uploads/user_files/' . $needle_file_name;

			// Сжатие изображения
			if (mb_strpos("png bmp jpeg xbm jpg", mb_strtolower(str_replace('.', '', $mime))) !== false and $_POST['compress'] != 'false') {

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
					rename('../uploads/user_files/' . $needle_file_name, '../uploads/user_files/' . $file_id . '.jpeg');
					$mime = '.jpeg';
					$url_to_file = '../uploads/user_files/' . $file_id . $mime;
					$image = imagecreatefromjpeg($url_to_file);
				}

				// $image = imagecreatefrompng($url_to_file);
				$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
				imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
				imagealphablending($bg, TRUE);
				imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
				imagedestroy($image);
				$quality = 50;
				imagejpeg($bg, '../uploads/user_files/' . $file_id . ".jpg", $quality);
				imagedestroy($bg);

				unlink('../uploads/user_files/' . $file_id . $mime);
				$stock_file_name = str_replace($mime, '.jpg', $stock_file_name);
				// echo $stock_file_name;

				$mime = '.jpg';
				$needle_file_name = $file_id . $mime;
			}

			// echo $needle_file_name;

			foreach ($file_types as $type => $values) {
				if (mb_strpos($values, mb_strtolower(str_replace('.', '', $mime))) != '') {
					$file_type = $type;
				}
			}

			array_push($output, array("stock_name" => $stock_file_name,"name" => $needle_file_name, "url" => $link . '/uploads/user_files/' . $needle_file_name, "mime" => $mime, "file_type" => $file_type, "size" => $file['size'], "owner_id" => $user_id, "file_id" => $file['file_id']));

			mysqli_query($connection, "INSERT INTO `user_files` (`user_id`, `server_file_name`) VALUES ('$user_id', '$needle_file_name')");
		}

		echo json_encode($output, JSON_UNESCAPED_UNICODE);

	} else {
		echo $apErrorCodes['1.1'];
	}
}






else if ($_POST['type'] == 'delete-file') {
	$user_id = decodeSecretID($_POST['secret_id'], "deleteFile");

	if ($user_id) {
		$file_name = $_POST['file_name'];
		$file_name_array = explode('_', $file_name);
		$file_id = $file_name_array[0];

		$file_owner_id = mysqli_query($connection, "SELECT * FROM `user_files` WHERE `id` = '$file_id'");

		if ($file_owner_id -> num_rows == 0) {
			echo 'file not found';
			exit();
		}

		$file_owner_id = mysqli_fetch_assoc($file_owner_id)['user_id'];

		if ($file_owner_id == $user_id) {
			unlink('../uploads/user_files/' . $file_name);
			echo 'success';
		} else {
			echo 'access denied';
		}

	} else {
		echo $apErrorCodes['1.1'];
	}
}



else {
	// echo $_POST['my_file_upload'];
	if( isset( $_POST['my_file_upload'] ) ){  
		// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно
		if ($_POST['secret_id'] == '') {
			exit();
		}
		// echo $_POST['secret_id'];

		$uploaddir = '../uploads';
		$uploaddir_userPhoto = '../uploads/user_photo';
		$uploaddir_userBgPhoto = '../uploads/user_bg_photo';

		// cоздадим папку если её нет
		if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
		if( ! is_dir( $uploaddir_userPhoto ) ) mkdir( $uploaddir_userPhoto, 0777 );
		if( ! is_dir( $uploaddir_userBgPhoto ) ) mkdir( $uploaddir_userBgPhoto, 0777 );

		$files      = $_FILES; // полученные файлы
		$done_files = array();

		// переместим файлы из временной директории в указанную
		foreach( $files as $file ){
			$stock_file_name = $_POST['stock_filename'];
			$needle_file_name = $_POST['needle_filename'];
			if ($stock_file_name == '' or $needle_file_name == '') {
				exit();
			}
			// $file_name = $_POST['filename'];
			// echo var_dump($_POST);

			// if ($_POST['img_type'] == 'user_photo') {
			// 	$dir = '../uploads/user_photo';
			// }
			// else if ($_POST['img_type'] == 'user_bg_photo') {
			// 	$dir = '../uploads/user_photo';
			// } 
			// else {
			// 	$dir = '../uploads';
			// }
			$dir = '../uploads';

			$getMime = explode( '.', $stock_file_name );
			$mime = '.' . end( $getMime );

			if( move_uploaded_file( $file['tmp_name'], "$dir/$needle_file_name" . $mime) ){
				$done_files[] = realpath( "$dir/$needle_file_name" . $mime);
			}

			$url_to_file = '../uploads/' . $needle_file_name . $mime;
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
					rename('../uploads/' . $needle_file_name . $mime, '../uploads/' . $needle_file_name . '.jpeg');
					$mime = '.jpeg';
					$url_to_file = '../uploads/' . $needle_file_name . $mime;
					$image = imagecreatefromjpeg($url_to_file);
				}

				$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
				imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
				imagealphablending($bg, TRUE);
				imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
				imagedestroy($image);
				$quality = 50;
				imagejpeg($bg, '../uploads/' . $needle_file_name . ".jpg", $quality);
				imagedestroy($bg);

				unlink('../uploads/' . $needle_file_name . $mime);
				// $stock_file_name = str_replace($mime, '.jpg', $stock_file_name);
				// echo $stock_file_name;

				$mime = '.jpg';
			}
		}
	}
	echo $mime;
}
