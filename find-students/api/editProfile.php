<?php
include_once "../inc/config.php";

if ($_POST['token'] != '') {
	$user_token = $_POST['token'];
	include_once "../inc/userData.php";
}

function rename_win($oldfile,$newfile) {
    if (!rename($oldfile,$newfile)) {
        if (copy ($oldfile,$newfile)) {
            unlink($oldfile);
            return TRUE;
        }
        return FALSE;
    }
    return TRUE;
}

if ($_POST['type'] == 'save-info') {
	$local_user_id = $_POST['user_id'];

	if ($_POST['user_id'] != '') {
		$local_user_id = $_POST['user_id'];
	} else {
		$local_user_id = $user_id;
	}

	if ($local_user_id == $user_id or $user_status == 'Admin') {
		$user_surname = $_POST['user_surname'];
		$user_first_name = $_POST['user_first_name'];
		$user_avatar_url = $_POST['user_avatar_url'];
		$user_profile_type = $_POST['user_profile_type'];
		$user_avatar_scale = $_POST['user_avatar_scale'];

		$user_photo_style = serialize(array(
			'ox_oy' => '',
			'scale' => $user_avatar_scale
		));

		if (mb_strtolower($user_profile_type) == 'открытый') {
			$user_profile_type = 0;
		} else {
			$user_profile_type = 1;
		}

		if ($user_avatar_url != '') {
			rename("../uploads/temp/" . $user_avatar_url, "../uploads/user_avatars/" . $user_avatar_url);

			$user_avatar_url = $link . '/uploads/user_avatars/' . $user_avatar_url . '?v=' . md5(date('YdmHis'));

			mysqli_query($connection, "UPDATE `users` SET `last_name` = '$user_surname', `first_name` = '$user_first_name', `photo` = '$user_avatar_url', `photo_style` = null, `closed_profile` = '$user_profile_type', `photo_style` = '$user_photo_style' WHERE `id` = '$local_user_id' ");
		} else {
			mysqli_query($connection, "UPDATE `users` SET `last_name` = '$user_surname', `first_name` = '$user_first_name', `closed_profile` = '$user_profile_type', `photo_style` = '$user_photo_style' WHERE `id` = '$local_user_id' ");
		}
		$output['success'] = true;
		$output['response'] = '';
		echoJSON($output);
	}
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
	echo json_encode(array("success" => true, "link" => $link . '/uploads/temp/' .  $needle_file_name . $mime), JSON_UNESCAPED_UNICODE);
}



if ($_POST['type'] == 'reset-token') {
	exitIfTokenIsNull($_POST['token']);

	$token = $_POST['token'];
	$new_token = md5($token . uniqid() . '_new_token_' . 'AIUfp97gHUBF@#7gPIWUgf73IUBfg&IGWFq9');

	$result = mysqli_query($connection, "UPDATE `users` SET `token` = '$new_token' WHERE `token` = '$token'");

	if ($result) {
		$output = array();
		$output['response'] = 'token changed';
		echoJSON($output);
	} else {
		$output = array();
		$output['response'] = 'error';
		echoJSON($output);
	}
}
