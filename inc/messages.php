<?php

include_once 'info.php';
include_once 'db.php';
// include_once 'userData.php';
// include_once 'connectionInfo.php';

// print_r($connectionInfo);

function deleteZeroes ($text) {
	if ($text[0] == '0') {
		return $text[1];
	}
	return $text;
}

function addZeroes ($text) {
	if (strlen($text) == 1) {
		return '0' . $text;
	}
	return $text;
}

$timezone = $_SESSION['user_timezone'] - $_SESSION['server_timezone'];

if ($_SESSION['user_timezone'] == '') {
	$timezone = 0;
}
if ($_POST['user_timezone'] != '') {
	$timezone = $_POST['user_timezone'] - $_SESSION['server_timezone'];
}

function cutStr ($string, $max) {
	if (strlen($string) > $max) {
		return mb_substr($string, 0, $max) . '...';
	}
	return $string;
}

function gridMediaFiles ($media_files) {
	$count_gridable_files = 0;
	$output = '<div class="message_media_files">';

	if ($media_files == '' or count($media_files) == 0) {
		return;
	}

	foreach ($media_files as $key => $value) {
		if ($value == null) {
			unset($media_files[$key]);
		}
	}

	if ($media_files == '' or count($media_files) == 0) {
		return;
	}

	foreach ($media_files as $key => $value) {
		// return var_dump($value['name']);
		if ($value['file_type'] == 'img' or $value['file_type'] == 'video') {
			if ($value['file_type'] == 'video') {
				$media_files[$key]['file_type'] = 'unknown';
			}
			$count_gridable_files++;
		}
	}

	if ($count_gridable_files > 0) {
		$grid = '';
		$file_id = explode('_', $value['server_name'])[0];

		if ($count_gridable_files == 1) {
			$grid = '<div class="message_media_files_grid grid_row_1">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 2) {
			$grid = '<div class="message_media_files_grid grid_row_2">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 3) {
			$grid = '';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					<div class="message_media_files_grid grid_row_1_mini">
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					</div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);
				break;
			}

			$grid .= '<div class="message_media_files_grid grid_row_2">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 4) {
			$grid = '';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					<div class="message_media_files_grid grid_row_1_mini">
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					</div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);
				break;
			}

			$grid .= '<div class="message_media_files_grid grid_row_3">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 5) {
			$grid = '';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					<div class="message_media_files_grid grid_row_1_mini">
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					</div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);
				break;
			}

			$grid .= '<div class="message_media_files_grid grid_row_4">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 6) {
			// $grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';

			$files_count = 1;
			foreach ($media_files as $key => $value) {

				if (bcmod($files_count, 2) != 0) {
					$files_count++;
					if ($value['file_type'] == 'img') {
						$grid .= '
						<div class="message_media_files_grid grid_row_2_mini">
							<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
						';
					}

					if ( $value['file_type'] == 'video') {
						$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
					}
				} 
				else {
					$files_count++;
					if ($value['file_type'] == 'img') {
						$grid .= '
							<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
						</div>';
					}

					if ( $value['file_type'] == 'video') {
						$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
					}
				}
			}
		}

		if ($count_gridable_files == 7) {
			$grid = '<div class="message_media_files_grid grid_row_3_mini">';

			$files_count = 1;
			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 4) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_4_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 8) {
			$grid = '<div class="message_media_files_grid grid_row_2_mini">';

			$files_count = 1;
			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 3) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_2_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 5) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_4_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}


		if ($count_gridable_files == 9) {
			$grid = '<div class="message_media_files_grid grid_row_2_mini">';

			$files_count = 1;
			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 3) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_3_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 6) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_4_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if ($count_gridable_files == 10) {
			$grid = '<div class="message_media_files_grid grid_row_3_mini">';

			$files_count = 1;
			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 4) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_3_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '
					
						<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>
					';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				unset($media_files[$key]);

				$files_count++;
				if ($files_count == 7) {
					break;
				}
			}

			$grid .= '</div><div class="message_media_files_grid grid_row_4_mini">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}
		// $output .= $grid;
		
	}

	if ($count_gridable_files > 10) {
		while (count($media_files) >= 4) {
			$grid .= '<div class="message_media_files_grid grid_row_4">';
			$files_count = 1;

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
				$files_count++;
				unset($media_files[$key]);
				if ($files_count == 5) {
					break 1;
				}
			}

			$grid .= '</div>';
		} 

		if (count($media_files) == 3) {
			$grid .= '<div class="message_media_files_grid grid_row_3">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if (count($media_files) == 2) {
			$grid .= '<div class="message_media_files_grid grid_row_2">';

			foreach ($media_files as $key => $value) {
				if ($value['file_type'] == 'img') {
					$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
				}

				if ( $value['file_type'] == 'video') {
					$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
				}
			}

			$grid .= '</div>';
		}

		if (count($media_files) == 1) {
			$grid .= '<div class="message_media_files_grid grid_row_1_mini">';

			if ($value['file_type'] == 'img') {
				$grid .= '<div class="message_media_file"><img title="' . str_replace($file_id .'_', '', $value['name']) . '" src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></div>';
			}

			if ( $value['file_type'] == 'video') {
				$grid .= '<div class="message_media_file player-play-video"><video title="' . str_replace($file_id .'_', '', $value['name']) . '" ><source src="' . $link . '/uploads/user_files/' . $value['server_name'] . '"></video></div>';
			}

			$grid .= '</div>';
		}

		
	}


	foreach ($media_files as $key => $value) {
		if ($value['file_type'] == 'unknown') {
			$file_id = explode('_', $value['server_name'])[0];

			if (strlen($value['name']) > 65) {
				$server_name = mb_substr($value['name'], 0, 65) . '... (.' . $value['mime'] . ')';
			} else {
				$server_name = $value['name'];
			}
			if ($value['size'] < 1024*1024) {
				$value['size'] = round($value['size'] / 1024, 1) . ' КБ';
			} else {
				$value['size'] = round($value['size'] / 1024/1024, 1) . ' МБ';
			}

			

			$grid .= '<a class="link_to_file" download="' . $value['name'] . '" href="' . $link . '/uploads/user_files/' . $value['server_name'] . '">
				<div class="ungridable_media_file">
					<div class="image">
						<div class="mime">' . $value['mime'] . '</div>
						<img src="' . $link . '/assets/img/icons/download.svg" class="download_logo">
					</div>
					<div class="file_info">
						<p class="title">' . $server_name . '</p>
						<p class="size">' . $value['size'] . '</p>
					</div>
				</div>
				</a>';
		}
	}

	$output .= $grid . '</div>';

	return $output;
}


// Подсчёт последнего онлайна пользователя
function calcTime ($date, $func) {
	// Разница между часовыми поясами сервера и пользователя
	global $timezone;

	// Имеем client_last_online - время последнего посещения по часовому поясу клиента
	// global $client_last_online_year;
	// global $client_last_online_month;
	// global $client_last_online_day;
	// global $client_last_online_minutes;

	// Имеем client_current - время на данный момент по часовому поясу клиента
	global $client_current_year;
	global $client_current_month;
	global $client_current_day;
	global $client_current_minutes;

	// global $user_data;

	global $months_accusative;

	// Дата последнего посещения по часовому поясу сервера
	$server_last_online_year = (int) mb_substr($date, 0, 4);
	$server_last_online_month = (int) mb_substr($date, 5, 2);
	$server_last_online_day = (int) mb_substr($date, 8, 2);

	$server_last_online_hour = (int) mb_substr($date, 11, 2);
	$server_last_online_minute = (int) mb_substr($date, 14, 2);

	$server_minutes = $server_last_online_hour * 60 + $server_last_online_minute + $timezone;

	// Дата последнего посещения по часовому поясу клиента
	$client_last_online_year = $server_last_online_year;
	$client_last_online_month = $server_last_online_month;
	$client_last_online_day = $server_last_online_day;
	$client_last_online_minutes = $server_minutes;

	// Если в на сервере и у клиента разные дни, то высчитываем последнее время посещения от лица клиента
	if ($client_last_online_minutes >= 1440) {

		$client_last_online_day++;
		$client_last_online_minutes -= 1440;

		if (cal_days_in_month(CAL_GREGORIAN, $server_last_online_month, $server_last_online_year) < $client_last_online_day) {
			$client_last_online_month++;
			$client_last_online_day = 1;

			if ($client_last_online_month > 12) {
				$client_last_online_year++;
				$client_last_online_month = 1;
			}
		}
	}
	// return $client_current_minutes;
	if ($func == 'lastOnline') {
		if ($client_current_year != $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Был' . $ending . ' в сети ' . addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}

		if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
			return 'Онлайн';

		}

		if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
			return 'Был' . $ending . ' в сети ' . caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

		}
		if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Был' . $ending . ' в сети вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Был' . $ending . ' в сети сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Был' . $ending . ' в сети ' . $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		
	}


	if ($func == 'local_message_date') {
		$hour = intdiv($client_last_online_minutes, 60);
		$minute = $client_last_online_minutes - $hour * 60;
		return addZeroes($hour) . ':' . addZeroes($minute);
	}

	if ($func == 'chating_history_date') {
		if ($client_current_year != $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года';
		} 
		else if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Сегодня';
		}
		else if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Вчера';
		} else {
			return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]);
		}
	}

	if ($func == 'chat_last_message_time') {
		// return $client_last_online_year;
		// return $client_current_year .' - '. $client_last_online_year;
		if ($client_current_day == $client_last_online_day and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return addZeroes($hour) . ':' . addZeroes($minute);
		}
		else if ($client_current_day - $client_last_online_day == 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Вчера';
		} 
		else if ($client_current_year == $client_last_online_year) {
			return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]);
		}
		else if ($client_current_year != $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года';
		} 
		
	}

	if ($func == 'fullDate') {

		if ($client_current_year != $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return addZeroes($client_last_online_day) . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' ' . $client_last_online_year . ' года в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}

		if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes <= 1) {
			return 'Только что';

		}

		if ($client_current_day == $client_last_online_day and $client_current_year == $client_last_online_year and $client_current_minutes - $client_last_online_minutes < 60) {
			return caseOfMinutes($client_current_minutes - $client_last_online_minutes) . ' назад';

		}
		if ($client_current_day - $client_last_online_day == 1 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Вчера в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_day - $client_last_online_day == 0 and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return 'Сегодня в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_day - $client_last_online_day > 1 and $client_current_month == $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		if ($client_current_month != $client_last_online_month and $client_current_year == $client_last_online_year) {
			$hour = intdiv($client_last_online_minutes, 60);
			$minute = $client_last_online_minutes - $hour * 60;
			return $client_last_online_day . ' ' . mb_strtolower($months_accusative[$client_last_online_month]) . ' в ' . addZeroes($hour) . ':' . addZeroes($minute);
		}
		return 'Недавно';
	}
}










if ($_POST['type'] == 'get-users') {
	// пока отключено
	$limit = $_POST['limit'];

	$server_current_year = (int) date('Y');
	$server_current_month = (int) date('m');
	$server_current_day = (int) date('d');

	$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

	// Текущая дата по часовому поясу клиента
	$client_current_year = $server_current_year;
	$client_current_month = $server_current_month;
	$client_current_day = $server_current_day;

		$client_current_minutes = $server_current_minutes;

	$local_user_id = decodeSecretID($_POST['secret_id'], 'getUsers');

	if ($local_user_id) {
		$output = array();
		if ($_POST['html']) {
			$output = '';
		}
		// 1 стадия. Выводим список пользователей в чате с которыми есть непрочитанные сообщения
		$unread_messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE `incoming_id` = '$local_user_id' and `status` != 2 ORDER BY `id` DESC");

		// список пользователей, которые прислали нам сообщения
		$incoming_notViewed_messages_id = array();
		if ($unread_messages -> num_rows != 0) {
			while ($m = mysqli_fetch_assoc($unread_messages)) {
				// Пользователя нет в массиве
				if (!in_array($m['outgoing_id'], $incoming_notViewed_messages_id)) {
					array_push($incoming_notViewed_messages_id, $m['outgoing_id']);
				}
			}

			foreach ($incoming_notViewed_messages_id as $key => $value) {
				$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$value'"));
				$count_messages = mysqli_query($connection, "SELECT `id` FROM `messages` WHERE `incoming_id` = '$local_user_id' and `outgoing_id` = '$value' and `status` != 2") -> num_rows;

				if ($count_messages > 999 && $count_messages < 1000000) {
					$count_messages = round($count_messages / 1000, 1) . 'к';
				}
				if ($count_messages > 999999) {
					$count_messages = round($count_messages / 1000000, 1) . 'млн';
				}

				$last_message = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `messages` WHERE (`incoming_id` = '$local_user_id' and `outgoing_id` = '$value') or (`incoming_id` = '$value' and `outgoing_id` = '$local_user_id') ORDER BY `id` DESC LIMIT 0, 1 "))['text'];

				if (strlen($last_message) > 75) {
					$last_message = mb_substr($last_message, 0, 75) . '...';
				}

				if (strlen($last_message) == 0) {
					$last_message = '<b class="media">Вложение</b>';
				}

				if (explode(' ', $last_message['date'])[0] == date('Y-m-d')) {
					$last_message_time = calcTime($last_message['date'], 'chat_last_message_time');
				} else {
					$last_message_time = calcTime($last_message['date'], 'chat_last_message_time');
				}


				if ($_POST['html']) {
					// html запрос
					$output .= '
					<li class="unread" id="user_' . $user_data['id'] . '">
						<div class="col-1 online">
							<div class="photo">
								<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
							</div>
						</div>
						<div class="col-2">
							<p>' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</p>
							<p>' . $last_message . '</p>
						</div>
						<div class="notification">' . $count_messages . '</div>
						<div class="last_message_time">' . $last_message_time . '</div>
					</li>';
				} else {
					// Обычный запрос
					$local_data_array = array(
						"user_id" => $user_data['id'],
						"first_name" => $user_data['first_name'],
						"last_name" => $user_data['last_name'],
						"patronymic" => $user_data['patronymic'],
						"user_photo" => $user_data['photo'],
						"user_photo_style" => unserialize($user_data['photo_style']),
						"last_message" => $last_message,
						"last_message_type" => "incoming"
					);
					$output[$user_data['id']] = $local_data_array;
				}
				
			}
		}

		// Чаты, в которых сообщения прочитаны
		$users = mysqli_query($connection, "SELECT * FROM `chats` WHERE `first_user_id` = '$local_user_id' or `second_user_id` = '$local_user_id'");

		while ($u = mysqli_fetch_assoc($users)) {
			if ($u['first_user_id'] == $local_user_id) {
				$second_user_id = $u['second_user_id'];
			} else {
				$second_user_id = $u['first_user_id'];
			}

			if (!in_array($second_user_id, $incoming_notViewed_messages_id)) {
				array_push($incoming_notViewed_messages_id, $second_user_id);

				$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$second_user_id'"));

				$last_message = mysqli_query($connection, "SELECT * FROM `messages` WHERE (`incoming_id` = '$local_user_id' and `outgoing_id` = '$second_user_id') or (`incoming_id` = '$second_user_id' and `outgoing_id` = '$local_user_id') ORDER BY `id` DESC LIMIT 0, 1 ");

				if ($last_message -> num_rows != 0) {
					$last_message = mysqli_fetch_assoc($last_message);

					if (explode(' ', $last_message['date'])[0] == date('Y-m-d')) {
						$last_message_time = calcTime($last_message['date'], 'chat_last_message_time');
					} else {
						$last_message_time = calcTime($last_message['date'], 'chat_last_message_time');
					}

					$message_classname = '';

					if ($last_message['status'] != 2) {
						$message_classname = 'unread';
					}

					if (strlen($last_message['text']) == 0) {
						$last_message['text'] = '<b class="media">Вложение</b>';
					}

					if (strlen($last_message['text']) > 75) {
						$last_message['text'] = mb_substr($last_message['text'], 0, 75) . '...';
					}

					if ($last_message['incoming_id'] != $local_user_id) {
						$last_message = 'Вы: ' . $last_message['text'];
						$last_message_type = 'outgoing';
					} 
					else {
						$last_message = $last_message['text'];
						$last_message_type = 'incoming';
					}

				} else {
					$last_message_type = 'none';
					$last_message = 'Начните чат!';
				}

				if ($_POST['html']) {

					$output .= '
					<li id="user_' . $user_data['id'] . '">
						<div class="col-1 online">
							<div class="photo">
								<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
							</div>
						</div>
						<div class="col-2">
							<p>' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</p>
							<p class="' . $message_classname . '">' . $last_message . '</p>
						</div>
						<div class="last_message_time">' . $last_message_time . '</div>
					</li>';
				} else {
					// Обычный запрос
					$local_data_array = array(
						"user_id" => $user_data['id'],
						"first_name" => $user_data['first_name'],
						"last_name" => $user_data['last_name'],
						"patronymic" => $user_data['patronymic'],
						"user_photo" => $user_data['photo'],
						"user_photo_style" => unserialize($user_data['photo_style']),
						"last_message" => $last_message,
						"last_message_type" => $last_message_type
					);
					$output[$user_data['id']] = $local_data_array;
				}
			}
		}

		$user_friends = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$local_user_id'"))['friends'];
		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		foreach ($user_friends as $key => $value) {
			if (!in_array($value, $incoming_notViewed_messages_id)) {
				$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$value'"));

				if ($_POST['html']) {
					$output .= '
					<li id="user_' . $user_data['id'] . '">
						<div class="col-1 online">
							<div class="photo">
								<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
							</div>
						</div>
						<div class="col-2">
							<p>' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</p>
							<p>Начните чат!</p>
						</div>
					</li>';
				} else {
					// Обычный запрос
					$local_data_array = array(
						"user_id" => $user_data['id'],
						"first_name" => $user_data['first_name'],
						"last_name" => $user_data['last_name'],
						"patronymic" => $user_data['patronymic'],
						"user_photo" => $user_data['photo'],
						"user_photo_style" => unserialize($user_data['photo_style']),
						"last_message" => 'Начните чат!',
						"last_message_type" => 'none'
					);
					$output[$user_data['id']] = $local_data_array;
				}
			}
		}

		// Отмечаем все сообщения со статусом как присланные (путём изменения статуса на 1)
		// Это сделано для того, чтобы эти сообщения не приходили повторно при проверке на новые сообщения
		mysqli_query($connection, "UPDATE `messages` SET `status` = 1 WHERE `incoming_id` = '$local_user_id' and `status` = 0");
		if ($_POST['html']) {
			echo $output;
		} else {
			if ($count($output) == 0) {
				echo 'null';
			} else {
				echo json_encode($output);
			}	
		}
	}
	else {
		echo $apiErrorCodes["1.1"];
	}
}









if ($_POST['type'] == 'get-chats') {
	// пока отключено
	$limit = $_POST['limit'];
	$local_user_id = decodeSecretID($_POST['secret_id'], 'getChats');

	if ($local_user_id) {
		$users = mysqli_query($connection, "SELECT * FROM `chats` WHERE `first_user_id` = '$local_user_id' or `second_user_id` = '$local_user_id'");

		$user_friends = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `friends` FROM `users` WHERE `id` = '$local_user_id'"))['friends'];
		$user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$local_user_id'"))['blacklist'];

		if ($user_friends == '') {
			$user_friends = array();
		} else {
			$user_friends = unserialize($user_friends);
		}

		if ($user_blacklist == '') {
			$user_blacklist = array();
		} else {
			$user_blacklist = unserialize($user_blacklist);
		}

		if ($users -> num_rows == 0 and count($user_friends) == 0) {
			echo 'null';
			exit();
		}
		$chats = array();

		$output = array();	
		if ($_POST['html']) {
			$output = '';	
		}

		// Текущая дата по часовому поясу сервера
		$server_current_year = (int) date('Y');
		$server_current_month = (int) date('m');
		$server_current_day = (int) date('d');

		$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

		// Текущая дата по часовому поясу клиента
		$client_current_year = $server_current_year;
		$client_current_month = $server_current_month;
		$client_current_day = $server_current_day;

		$client_current_minutes = $server_current_minutes;

		// Если в один момент на сервере и у клиенка разные дни, то высчитываем настоящее время у клиента
		if ($client_current_minutes >= 1440) {

			$client_current_day++;
			$client_current_minutes -= 1440;

			if (cal_days_in_month(CAL_GREGORIAN, $server_current_month, $server_current_year) < $client_current_day) {
				$client_current_month++;
				$client_current_day = 1;

				if ($client_current_month > 12) {
					$client_current_year++;
					$client_current_month = 1;
				}
			}
		}


		while ($u = mysqli_fetch_assoc($users)) {

			if ($u['first_user_id'] == $local_user_id) {
				$second_user_id = $u['second_user_id'];
			} else {
				$second_user_id = $u['first_user_id'];
			}

			array_push($chats, $second_user_id);

			$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$second_user_id'"));

			$user_data_blacklist = $user_data['blacklist'];
			if ($user_data_blacklist == '') {
				$user_data_blacklist = array();
			} else {
				$user_data_blacklist = unserialize($user_data_blacklist);
			}

			$ending;
			if ($user_data['sex'] == 'Женский') {
				$ending = 'а';
			}	

			$messaging_history = array();
			if ($_POST['html']) {
				$messaging_history = '';
			}
			$messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE (`incoming_id` = '$local_user_id' and `outgoing_id` = '$second_user_id') or (`incoming_id` = '$second_user_id' and `outgoing_id` = '$local_user_id') ORDER BY `id` DESC");
			// DESC из-за того, что css переворачивает позиционирование, а именно flex-direction: column-reverse;
			// Всё из-за того, что overflow scroll и jusify-content flex-end несовместимы
			if ($messages -> num_rows != 0) {

				$lastDate = '';
				$lastDateCount = 0;
				$notViewedIncomingMessagesCount = 0;
				if ($_POST['html']) {
					while ($m = mysqli_fetch_assoc($messages)) {
						if ($m['text'] != '') {
							$m['text'] = '<div class="message_text">' . searchUrl($m['text']) . '</div>';
						}
						
						$media_files = json_decode($m['media'], 1);

						
						if ($lastDateCount == 0) {
							$lastDateCount++;
							$lastDate = $m['date'];
						} else {
							if (mb_substr($lastDate, 0, 10) != mb_substr($m['date'], 0, 10) and $m['date'] != '' and $lastDate != '') {
								$messaging_history .= '<div class="chating_date">' . calcTime($lastDate, 'chating_history_date') . '</div>';
								$lastDate = $m['date'];
							}
						}

						$message_status = '';
						if ($m['status'] != 2) {
							$message_status = 'unread';
							if ($m['incoming_id'] == $local_user_id) {
								$notViewedIncomingMessagesCount++;
							}
						} else {
							if ($notViewedIncomingMessagesCount > 0) {
								$messaging_history .= '<div class="unread_messages">Непрочитанные сообщения</div>';
							}
							$notViewedIncomingMessagesCount = -1;
						}

						$message_edited_class = '';
						$message_edited_attr = 'edited_version="0"';
						if ($m['edited_version'] != 0) {
							$message_edited_class = 'message_edited';
							$message_edited_attr = 'edited_version="' . $m['edited_version'] . '"';
						}

						$message_edit_button = '<div class="edit_message"><img src="' . $link . '/assets/img/icons/pencil.svg"></div>';
						$message_delete_button = '<div class="delete_message"><img src="' . $link . '/assets/img/icons/trash.svg"></div>';
						$message_delete_button = '';
						$message_editability = 'true';
						if ($m['editability'] == 0) {
							$message_editability = 'false';	
							$message_edit_button = '';
							$message_delete_button = '';
						}


						if ($m['text'] == '' and count($media_files) == 0) {
							$m['text'] = '<div class="empty_message">Пустое сообщение</div>';
						}


						// $pattern = '/^(?:http:\/\/)?[-0-9a-z._]*.\w{2,4}[:0-9]*$/';
						// preg_match($pattern, $m['text'], $matches, PREG_OFFSET_CAPTURE, 3);
						// print_r($matches);
											
						if ($m['incoming_id'] == $local_user_id) {
							$messaging_history .= '
							<div id="message_' . $m['id'] . '" class="message_block incoming_msg ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								<div class="message">' . str_replace(array("\r\n", "\r", "\n"), '<br>', $m['text']) . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles($media_files) . '</div>
									
								
							</div>
							';
						} else {
							$messaging_history .= '
							<div id="message_' . $m['id'] . '" class="message_block outgoing_msg ' . $message_status . ' ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								' . $message_delete_button . $message_edit_button . '
								<div class="message">' . str_replace(array("\r\n", "\r", "\n"), '<br>',  $m['text']) . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles($media_files) . '</div>
								
								
							</div>
							';
						}
					}
					$messaging_history .= '<div class="chating_date">' . calcTime($lastDate, 'chating_history_date') . '</div>';
					
				} else {
					while($m = mysqli_fetch_assoc($messages)) {

					$message_status = 'viewed';
					if ($m['status'] != 2) {
						$message_status = 'unread';
					}
										
					if ($m['incoming_id'] == $local_user_id) {
						$local_array = array(
							"message_type" => 'incoming',
							"message_status" => $message_status,
							"text" => $m['text']
						);
						array_push($messaging_history, $local_array);
					} else {
						$local_array = array(
							"message_type" => 'outgoing',
							"message_status" => $message_status,
							"text" => $m['text']
						);
						array_push($messaging_history, $local_array);
					}
				}	
				}
			}

			$user_is_blacklisted = 0;
				$second_user_is_blacklisted = 0;

			if (in_array($second_user_id, $user_blacklist)) {
				$input_form = '<p class=	"privacy_error">Уберите пользователя из чёрного списка, чтобы начать переписку</p>';
				$second_user_is_blacklisted = 1;
			} else if (in_array($local_user_id, $user_data_blacklist)) {
				$input_form = '<p class="privacy_error"> Пользователь запретил Вам присылать ему сообщения</p>';
				$user_is_blacklisted = 1;
			} else {
				$input_form = '
						<div class="editing_message_info">
							<div></div>
							<div><img src="' . $link . '/assets/img/icons/x.svg"></div>
						</div>
						<div class="rows">
							<div class="add-media-file">
								<img src="' . $link . '/assets/img/icons/paperclip.svg">
								<div class="add_media_file_without_compress">
									<img src="' . $link . '/assets/img/icons/file.svg">Файл без сжатия
								</div>
							</div>
							<div class="input-form">
								<textarea alt="' . $user_data['id'] . '" placeholder="Напишите сообщение..."></textarea>

								<div class="upload-files">
									<input multiple type="file" name="upload-file" id="message-media">
									<label class="button" for="message-media">Выберите <b>файл</b> или перетащите его сюда</label>
									<img src="' . $link . '/assets/img/icons/x.svg">
								</div>
							</div>
							<button class="send_message">
								<img src="' . $link . '/assets/img/icons/brand-telegram.svg">

								<!-- https://icon-library.com/images/send-message-icon/send-message-icon-26.jpg -->
							</button>
						</div>

						<div class="uploaded-files"></div>';
			}
					
			if ($_POST['html']) {
				$output .= '
					<div id="chat_' . $user_data['id'] . '" class="chats-block">
					<div class="info">
						<img class="back_to_users_list" src="' . $link . '/assets/img/icons/arrow-left.svg">
						<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
							<div class="col-1 online">
								<div class="photo">
									<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
								</div>
							</div>
						</a>
						<div class="col-2">
							<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</a>
							<p class="online_time">' . calcTime($user_data['last_online'], 'lastOnline') . '</p>
							<div class="status_chatting">Печатает</div>

							<div class="status_sending_files">Отправляет файлы
								<div class="status_sf_i">
									<div class=" status_sf_i_1"></div>
									<div class=" status_sf_i_2"></div>
									<div class=" status_sf_i_3"></div>
								</div>
							</div>
						</div>
						<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
							<button class="button-1">Перейти в профиль</button>
						</a>
					</div>

					<div class="chat">
						' . $messaging_history . '
					</div>
					
					<div class="input">
						' . $input_form . '
					</div>
				</div>';
			} else {
				$output[$user_data['id']] = array(
					"user_id" => $user_data['id'],
					"first_name" => $user_data['first_name'],
					"patronymic" => $user_data['patronymic'],
					"last_name" => $user_data['last_name'],
					"online" => $user_data['last_online'],
					"user_photo" => $user_data['photo'],
					"user_photo_style" => unserialize($user_data['photo_style']),
					"chating_history" => $messaging_history,
					"user_is_blacklisted" => $user_is_blacklisted,
					"second_user_is_blacklisted" => $second_user_is_blacklisted
				);
			}
		}
		
		foreach ($user_friends as $key => $value) {
			if (!in_array($value, $chats)) {
				$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$value'"));

				$user_data_blacklist = $user_data['blacklist'];
				if ($user_data_blacklist == '') {
					$user_data_blacklist = array();
				} else {
					$user_data_blacklist = unserialize($user_data_blacklist);
				}

				$user_is_blacklisted = 0;
				$second_user_is_blacklisted = 0;
				if (in_array($value, $user_blacklist)) {
					$input_form = '<p class="privacy_error">Уберите пользователя из чёрного списка, чтобы начать переписку</p>';
					
					$second_user_is_blacklisted = 1;

				} else if (in_array($local_user_id, $user_data_blacklist)) {
					$input_form = '<p class="privacy_error"> Пользователь запретил Вам присылать ему сообщения</p>';
					$user_is_blacklisted = 1;
				} else {
					$input_form = '
						<div class="rows">
							<div class="add-media-file">
								<img src="' . $link . '/assets/img/icons/paperclip.svg">
								<div class="add_media_file_without_compress">
									<img src="' . $link . '/assets/img/icons/file.svg">Файл без сжатия
								</div>
							</div>
							<div class="input-form">
								<textarea alt="' . $user_data['id'] . '" placeholder="Напишите сообщение..."></textarea>

								<div class="upload-files">
									<input multiple type="file" name="upload-file" id="message-media">
									<label class="button" for="message-media">Выберите <b>файл</b> или перетащите его сюда</label>
									<img src="' . $link . '/assets/img/icons/x.svg">
								</div>
							</div>
							<button class="send_message">
								<img src="' . $link . '/assets/img/icons/brand-telegram.svg">
							</button>
						</div>

						<div class="uploaded-files"></div>';
				}

				if ($_POST['html']) {
					$output .= '
					<div id="chat_' . $user_data['id'] . '" class="chats-block">
					<div class="info">
						<img class="back_to_users_list" src="' . $link . '/assets/img/icons/arrow-left.svg">
						<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
							<div class="col-1 online">
								<div class="photo">
									<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
								</div>
							</div>
						</a>
						<div class="col-2">
							<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</a>
							<p>' . calcTime($user_data['last_online'], 'lastOnline') . '</p>
						</div>
						<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
							<button class="button-1">Перейти в профиль</button>
						</a>
					</div>

					<div class="chat">
						
					</div>

					<div class="input">
						' . $input_form . '
					</div>
				</div>';
				} else {
					$output[$user_data['id']] = array(
						"user_id" => $user_data['id'],
						"first_name" => $user_data['first_name'],
						"last_name" => $user_data['last_name'],
						"patronymic" => $user_data['patronymic'],
						"online" => calcTime($user_data['last_online'], 'lastOnline'),
						"user_photo" => $user_data['photo'],
						"user_photo_style" => unserialize($user_data['photo_style']),
						"chating_history" => 'null',
						"user_is_blacklisted" => $user_is_blacklisted,
						"second_user_is_blacklisted" => $second_user_is_blacklisted
					);
				}
			}
		}

		if ($_POST['html']) {
			echo $output;
		} else {
			echo json_encode($output);
			// var_dump($output);
		}
	} else {
		echo $apiErrorCodes['1.1'];
	}
}









if ($_POST['type'] == 'get-chat') {
	$secret_id = $_POST['secret_id'];
	$id = $_POST['id'];

	$local_user_id = decodeSecretID($_POST['secret_id'], 'getChat');

	if ($local_user_id) {

		$output = array();
		if ($_POST['html']) {
			$output = '';
		}		

		// Текущая дата по часовому поясу сервера
		$server_current_year = (int) date('Y');
		$server_current_month = (int) date('m');
		$server_current_day = (int) date('d');

		$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

		// Текущая дата по часовому поясу клиента
		$client_current_year = $server_current_year;
		$client_current_month = $server_current_month;
		$client_current_day = $server_current_day;

		$client_current_minutes = $server_current_minutes;

		// Если в один момент на сервере и у клиенка разные дни, то высчитываем настоящее время у клиента
		if ($client_current_minutes >= 1440) {

			$client_current_day++;
			$client_current_minutes -= 1440;

			if (cal_days_in_month(CAL_GREGORIAN, $server_current_month, $server_current_year) < $client_current_day) {
				$client_current_month++;
				$client_current_day = 1;

				if ($client_current_month > 12) {
					$client_current_year++;
					$client_current_month = 1;
				}
			}
		}

		$user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$id'"));
		$chat = mysqli_query($connection, "SELECT `id` FROM `chats` WHERE (`first_user_id` = '$local_user_id' and `second_user_id` = '$id') or (`first_user_id` = '$id' and `second_user_id` = '$local_user_id')");


		$user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$local_user_id'"))['blacklist'];
		if ($user_blacklist == '') {
			$user_blacklist = array();
		} else {
			$user_blacklist = unserialize($user_blacklist);
		}

		$ending;
		if ($user_data['sex'] == 'Женский') {
			$ending = 'а';
		}

		$user_data['friends'] = unserialize($user_data['friends']);
		// Список друзей пользователя
		if ($user_data['friends'] == '') {
			$user_data['friends'] = array();
		}

		$user_data_blacklist = $user_data['blacklist'];
		if ($user_data_blacklist == '') {
			$user_data_blacklist = array();
		} else {
			$user_data_blacklist = unserialize($user_data_blacklist);
		}

		$input_form = '
						<div class="rows">
							<div class="add-media-file">
								<img src="' . $link . '/assets/img/icons/paperclip.svg">
								<div class="add_media_file_without_compress">
									<img src="' . $link . '/assets/img/icons/file.svg">Файл без сжатия
								</div>
							</div>
							<div class="input-form">
								<textarea alt="' . $user_data['id'] . '" placeholder="Напишите сообщение..."></textarea>

								<div class="upload-files">
									<input multiple type="file" name="upload-file" id="message-media">
									<label class="button" for="message-media">Выберите <b>файл</b> или перетащите его сюда</label>
									<img src="' . $link . '/assets/img/icons/x.svg">
								</div>
							</div>
							<button class="send_message">
								<img src="' . $link . '/assets/img/icons/brand-telegram.svg">
							</button>
						</div>

						<div class="uploaded-files"></div>';

		if ($user_data['privacy_messages'] == 0 and $chat -> num_rows == 0) {
			$input_form = '<p class="privacy_error">Пользователь запретил присылать ему сообщения</p>';
		}
		if ($user_data['privacy_messages'] == 1 and !in_array($local_user_id, $user_data['friends']) and $chat -> num_rows == 0) {
			$input_form = '<p class="privacy_error">Чтобы присылать сообщения пользователю необходимо состоять с ним в друзьях</p>';
		}

		$second_user_is_blacklisted = 0;
		$user_is_blacklisted = 0;
		if (in_array($id, $user_blacklist)) {
				$input_form = '<p class="privacy_error">Уберите пользователя из чёрного списка, чтобы начать переписку</p>';
				$second_user_is_blacklisted = 1;
		}
		if (in_array($local_user_id, $user_data_blacklist)) {
				$input_form = '<p class="privacy_error">Пользователь запретил Вам присылать ему сообщения</p>';
				$user_is_blacklisted = 1;
		} 


		$messaging_history = array();
		if ($_POST['html']) {
			$messaging_history = '';
		}

		$messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE (`incoming_id` = '$local_user_id' and `outgoing_id` = '$id') or (`incoming_id` = '$id' and `outgoing_id` = '$local_user_id') ORDER BY `id` DESC");
			// DESC из-за того, что css переворачивает позиционирование, а именно flex-direction: column-reverse;

		if ($messages -> num_rows != 0) {

			if ($_POST['html']) {
				while($m = mysqli_fetch_assoc($messages)) {

					$media_files = json_decode($m['media'], 1);

					$message_status = '';
					if ($m['status'] != 2) {
						$message_status = 'unread';
					}

					$message_edited_class = '';
					$message_edited_attr = 'edited_version="0"';
					if ($m['edited_version'] != 0) {
						$message_edited_class = 'message_edited';
						$message_edited_attr = 'edited_version="' . $m['edited_version'] . '"';
					}

					$message_editability = 'true';
					if ($m['editability'] == 0) {
						$message_editability = 'false';
						
					}

					if ($m['text'] == '' and count($media_files) == 0) {
						$m['text'] = '<div class="empty_message">Пустое сообщение</div>';
					} else {
						if ($_POST['html'] == true) {
							$m['text'] = searchUrl($m['text']);
						}
						
					}

										
					if ($m['incoming_id'] == $local_user_id) {
						$messaging_history .= '
							<div id="message_' . $m['id'] . '" class="message_block incoming_msg ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								<div class="message">' . str_replace(array("\r\n", "\r", "\n"), '<br>', $m['text']) . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles($media_files) . '</div>
									
								
							</div>
							';
					} else {
						$messaging_history .= '
							<div id="message_' . $m['id'] . '" class="message_block incoming_msg ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								<div class="message">' . str_replace(array("\r\n", "\r", "\n"), '<br>', $m['text']) . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles($media_files) . '</div>
									
								
							</div>
							';
					}
				}
			}
			else {
				$lastDate = '';
				$lastDateCount = 0;
				while($m = mysqli_fetch_assoc($messages)) {

					if ($lastDateCount == 0) {
						$lastDateCount++;
						$lastDate = $m['date'];
					} else {
						if (mb_substr($lastDate, 0, 10) != mb_substr($m['date'], 0, 10) and $m['date'] != '' and $lastDate != '') {
							
							$messaging_history .= '<div class="chating_date">' . calcTime($lastDate, 'chating_history_date') . '</div>';
							$lastDate = $m['date'];
						}
					}

					$message_status = '';
					if ($m['status'] != 2) {
						$message_status = 'unread';
					}

					if ($m['incoming_id'] == $local_user_id) {
						$local_array = array(
							"message_type" => 'incoming',
							"message_status" => $message_status,
							"text" => $m['text']
						);
						array_push($messaging_history, $local_array);
					} else {
						$local_array = array(
							"message_type" => 'outgoing',
							"message_status" => $message_status,
							"text" => $m['text']
						);
						array_push($messaging_history, $local_array);
					}
				}
				$messaging_history .= '<div class="chating_date">' . calcTime($lastDate, 'chating_history_date') . '</div>';
			}
		}
					
		if ($_POST['html']) {
			$output .= '
				<div id="chat_' . $user_data['id'] . '" class="chats-block">
				<div class="info">
					<img class="back_to_users_list" src="' . $link . '/assets/img/icons/arrow-left.svg">
					<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
						<div class="col-1">
							<div class="photo">
								<img style="' . unserialize($user_data['photo_style'])['ox_oy'] . ';transform: scale(' . unserialize($user_data['photo_style'])['scale'] . ');" draggable="false" src="' . $user_data['photo'] . '">
							</div>
						</div>
					</a>
					<div class="col-2">
						<a href="">' . $user_data['last_name'] . ' ' . $user_data['first_name'] . '</a>
						<p class="online_time">' . calcTime($user_data['last_online'], 'lastOnline') . '</p>
						<div class="status_chatting">Печатает</div>

						<div class="status_sending_files">Отправляет файлы
							<div class="status_sf_i">
								<div class=" status_sf_i_1"></div>
								<div class=" status_sf_i_2"></div>
								<div class=" status_sf_i_3"></div>
							</div>
						</div>
					</div>
					<a href="' . $link . '/profile/?id=' . $user_data['id'] . '">
						<button class="button-1">Перейти в профиль</button>
					</a>
				</div>

				<div class="chat">
					' . $messaging_history . '
				</div>

				<div class="input">
					' . $input_form . '
				</div>
			</div>';

			echo $output;
		} else {
			$output[$user_data['id']] = array(
				"user_id" => $user_data['id'],
				"first_name" => $user_data['first_name'],
				"last_name" => $user_data['last_name'],
				"patronymic" => $user_data['patronymic'],
				"online" => calcTime($user_data['last_online'], 'lastOnline'),
				"user_photo" => $user_data['photo'],
				"user_photo_style" => unserialize($user_data['photo_style']),
				"chating_history" => $messaging_history,
				"user_is_blacklisted" => $user_is_blacklisted,
				"second_user_is_blacklisted" => $second_user_is_blacklisted
			);

			echo json_encode($output);
		}

		
	} else {
		echo $apiErrorCodes['1.1'];
	}
}












if ($_POST['type'] == 'send-message') {
	$second_user_id = $_POST['id'];
	$message = strip_tags(addslashes($_POST['message']));
	// echo $message;

	$message_type = $_POST['message_type'];
	$second_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$second_user_id'");

	// не найден пользователь, которому мы отправляем сообщение
	if ($second_user_data -> num_rows == 0) {
		echo 'user not found';
		exit();
	}
	$second_user_data = mysqli_fetch_assoc($second_user_data);

	$local_user_id = decodeSecretID($_POST['secret_id'], 'sendMessage');

	if ($local_user_id) {
		$local_user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$local_user_id'"))['blacklist'];

		$media_files = json_decode($_POST['media_files'], 1);

		if ($_POST['media_files'] == '') {
			$output_media_files = '';

		} else {
			$file_types = array(
				"img" => "- png bmp ecw gif ico ilbm jpeg mrsid pcx tga tiff webp xbm xps rla rpf pnm jpg jfif",
				"video" => "- mp4 mov wmv avi avchd flv f4v swf mkv webm html5 mpeg-2 vob ogv qt rmvb viv asf amv mpg mp2 mpeg mpe mpv",
			);

			foreach ($media_files as $key => $value) {
				if ($value != '' and gettype($value) != "NULL") {
					$file_name_array = explode('_', $value['server_name']);
					$mime = end(explode('.', $value['server_name']));

					$file_type = 'unknown';

					foreach ($file_types as $type => $values) {
						// echo $type . ' - ' . $values;
						if (mb_strpos($values, mb_strtolower(str_replace('.', '', $mime))) != '') {
							$file_type = $type;
						}
					}

					$output_media_files[$key] = array(
						"name" => $value['name'],
						"server_name" => $value['server_name'],
						"size" => $value['size'],
						"owner_id" => $value['owner_id'],
						"mime" => $mime,
						"last_modified_date" => $value['lastModifiedDate'],
						"file_type" => $file_type
					);
				}
				
			}
			
		}

		// exit();

		if ((is_null(str_replace(PHP_EOL, '', $message)) and strlen($message) >= 10000) and count($output_media_files) == 0) {
			// Сообщение не подходит по фильтрам
			exit();
		}

		if ($local_user_blacklist == '') {
			$local_user_blacklist = array();
		} else {
			$local_user_blacklist = unserialize($local_user_blacklist);
		}

		$second_user_blacklist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `blacklist` FROM `users` WHERE `id` = '$second_user_id'"))['blacklist'];

		if ($second_user_blacklist == '') {
			$second_user_blacklist = array();
		} else {
			$second_user_blacklist = unserialize($second_user_blacklist);
		}

		// Проверим, можем ли мы отправить пользователю сообщение
		// 1. проверка приватности сообщений (кто может присылать сообщения пользователю)
		// 2. если могут присылать только друзья, то проверяем, состоим ли мы в друзьях
		// 3. проверяем, существует ли чат

		// Второй пользователь находится в черном списке
		if (in_array($second_user_id, $local_user_blacklist)) {
			echo '3 failed';
			exit();
		}
		// Основной пользователь находится в черном списке
		if (in_array($local_user_id, $second_user_blacklist)) {
			echo '4 failed';
			exit();
		}

		// Приватность сообщений: никто
		// Если уже есть чат, то пользователь может писать без проблем
		if ($second_user_data['privacy_messages'] == 0) {
			if (mysqli_query($connection, "SELECT `id` FROM `chats` WHERE (`first_user_id` = '$local_user_id' and `second_user_id` = '$second_user_id') or (`first_user_id` = '$second_user_id' and `second_user_id` = '$local_user_id') ") -> num_rows == 0) {
				echo '0 failed';
				exit();
			} else {

				// Формируем и создаём сообщение
				if ($output_media_files != '') {
					$output_media_files = json_encode($output_media_files, JSON_UNESCAPED_UNICODE);
				}
				mysqli_query($connection, "INSERT INTO `messages` (`outgoing_id`, `incoming_id`, `type`, `text`, `media`) VALUES ('$local_user_id', '$second_user_id', '$message_type', '$message', '$output_media_files')");
				echo '0 success';
				// echo $message;
			}
		}

		// Приватность сообщений: только друзья
		// Если уже есть чат, то пользователь может писать без проблем
		else if ($second_user_data['privacy_messages'] == 1) {
			$second_user_data['friends'] = unserialize($second_user_data['friends']);
			if ($second_user_data['friends'] == '') {
				$second_user_data['friends'] = array();
			}

			if (in_array($local_user_id, $second_user_data['friends']) or mysqli_query($connection, "SELECT `id` FROM `chats` WHERE (`first_user_id` = '$local_user_id' and `second_user_id` = '$second_user_id') or (`first_user_id` = '$second_user_id' and `second_user_id` = '$local_user_id') ") -> num_rows != 0) {
				// Проверяем, существует ли чат
				$chat = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$local_user_id' and `second_user_id` = '$second_user_id') or (`first_user_id` = '$second_user_id' and `second_user_id` = '$local_user_id') ");

				if ($chat -> num_rows == 0) {
					// Чата нет => создаём чат
					mysqli_query($connection, "INSERT INTO `chats` (`first_user_id`, `second_user_id`) VALUES ('$local_user_id', '$second_user_id')");
				}

				// Формируем и создаём сообщение
				if ($output_media_files != '') {
					$output_media_files = json_encode($output_media_files, JSON_UNESCAPED_UNICODE);
				}
				mysqli_query($connection, "INSERT INTO `messages` (`outgoing_id`, `incoming_id`, `type`, `text`, `media`) VALUES ('$local_user_id', '$second_user_id', '$message_type', '$message', '$output_media_files')");
				echo '1 success';
				// echo $message;

			} else {
				// Мы не состоим в друзьях у пользователя
				echo "1 failed";
				exit();
			}
		}

		// Приватность сообщений: все
		else if ($second_user_data['privacy_messages'] == 2) {
			// Проверяем, существует ли чат
			$chat = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$local_user_id' and `second_user_id` = '$second_user_id') or (`first_user_id` = '$second_user_id' and `second_user_id` = '$local_user_id') ");

			if ($chat -> num_rows == 0) {
				// Чата нет => создаём чат
				mysqli_query($connection, "INSERT INTO `chats` (`first_user_id`, `second_user_id`) VALUES ('$local_user_id', '$second_user_id')");
			}

			// Формируем и создаём сообщение
			if ($output_media_files != '') {
				$output_media_files = json_encode($output_media_files, JSON_UNESCAPED_UNICODE);
			}
			mysqli_query($connection, "INSERT INTO `messages` (`outgoing_id`, `incoming_id`, `type`, `text`, `media`) VALUES ('$local_user_id', '$second_user_id', '$message_type', '$message', '$output_media_files')");
			echo '2 success';
			// echo $body;
		}
	} else {
		echo $apiErrorCodes['1.1'];
	}
}








if ($_POST['type'] == 'get-messages') {
	$secret_id = $_POST['secret_id'];

	$local_user_id = decodeSecretID($_POST['secret_id'], 'getMessages');

	if ($local_user_id) {
		$chats = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$local_user_id') or (`second_user_id` = '$local_user_id')");

		$json_messages = json_decode($_POST['messages'], 1);
		$output = array();

		if ($chats -> num_rows == 0) {
			echo 'chats not found';
			exit();
		}

		$chats_array = array();

		while ($c = mysqli_fetch_assoc($chats)) {
			if ($c['first_user_id'] == $local_user_id) {
				array_push($chats_array, $c['second_user_id']);
			} else {
				array_push($chats_array, $c['first_user_id']);
			}
		}

		foreach ($chats_array as $chat_id) {
			$output[$chat_id] = array();

			if ($json_messages[$chat_id] != '') {
				// У пользователя чат отображён
				// echo '/Chat ' . $chat_id . ' displayed';

				$last_outgoing_message = mysqli_query($connection, "SELECT * FROM `messages` WHERE `outgoing_id` = '$local_user_id' and `incoming_id` = '$chat_id' ORDER BY `id` DESC LIMIT 0, 1");

				$last_incoming_message = mysqli_query($connection, "SELECT * FROM `messages` WHERE `outgoing_id` = '$chat_id' and `incoming_id` = '$local_user_id' ORDER BY `id` DESC LIMIT 0, 1");

				// В чате есть отправленные сообщения
				if ($last_outgoing_message -> num_rows != 0) {
					$last_outgoing_message = mysqli_fetch_assoc($last_outgoing_message);
					// echo '/ chat ' . $chat_id . ' has outgoing messages ';

					// У пользователя отображено не последнее отправленное сообщение
					if ($last_outgoing_message['id'] != $json_messages[$chat_id]['og']) {
						$last_outgoing_message_id = $json_messages[$chat_id]['og'];
						$last_outgoing_messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE (`outgoing_id` = '$local_user_id' and `incoming_id` = '$chat_id') and `id` > '$last_outgoing_message_id'");

						// echo '/ chat ' . $chat_id . ' outgoing_id > ' . $last_outgoing_message_id . ' /';

						$output[$chat_id]['og'] = array();

						while ($m = mysqli_fetch_assoc($last_outgoing_messages)) {
							$message_status = '';

							if ($m['status'] != 2) {
								$message_status = 'unread';
							}

							$message_edited_class = '';
							$message_edited_attr = 'edited_version="0"';
							if ($m['edited_version'] != 0) {
								$message_edited_class = 'message_edited';
								$message_edited_attr = 'edited_version="' . $m['edited_version'] . '"';
							}

							$message_edit_button = '<div class="edit_message"><img src="' . $link . '/assets/img/icons/pencil.svg"></div>';
							$message_delete_button = '<div class="delete_message"><img src="' . $link . '/assets/img/icons/trash.svg"></div>';
							$message_delete_button = '';
							$message_editability = 'true';
							if ($m['editability'] == 0) {
								$message_editability = 'false';
								$message_edit_button = '';
								$message_delete_button = '';
							}

							// var_dump($media_files);
							if ($m['text'] == '' and count(json_decode($m['media'], 1)) == 0) {
								$m['text'] = '<div class="empty_message">Пустое сообщение</div>';

							} else {
								$message_text = '';
								if (str_replace(array("\r\n", "\r", "\n"), '<br>',  $m['text']) != '') {
									$message_text = '<div class="message_text">' . str_replace(array("\r\n", "\r", "\n"), '<br>',  searchUrl($m['text'])) . '</div>';
								}
							}

							
												
							$message_html = '<div id="message_' . $m['id'] . '" class="outgoing_hidden_msg message_block outgoing_msg ' . $message_status . ' ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								' . $message_delete_button . $message_edit_button . '
									<div class="message">' . $message_text . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles(json_decode($m['media'], 1)) . '</div>	
								</div>
								';

							// $message_html = $m['id'];

							$output[$chat_id]['og'][$m['id']] = $message_html;
						}

					}
				}

				// В чате есть принятые сообщения
				if ($last_incoming_message -> num_rows != 0) {
					// echo '/ chat ' . $chat_id . ' has incoming messages ';
					$last_incoming_message = mysqli_fetch_assoc($last_incoming_message);

					// У пользователя отображено не последнее принятое сообщение
					if ($last_incoming_message['id'] != $json_messages[$chat_id]['ic']) {
						$last_incoming_message_id = $json_messages[$chat_id]['ic'];

						// echo '/ chat ' . $chat_id . ' incoming_id > ' . $last_incoming_message_id . ' /';

						$last_incoming_messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE (`outgoing_id` = '$chat_id' and `incoming_id` = '$local_user_id') and `id` > '$last_incoming_message_id'");

						$output[$chat_id]['ic'] = array();

						while ($m = mysqli_fetch_assoc($last_incoming_messages)) {
							$message_status = '';

							if ($m['status'] != 2) {
								$message_status = 'unread';
							}

							$message_edited_class = '';
							$message_edited_attr = 'edited_version="0"';
							if ($m['edited_version'] != 0) {
								$message_edited_class = 'message_edited';
								$message_edited_attr = 'edited_version="' . $m['edited_version'] . '"';
							}

							$message_editability = 'true';
							if ($m['editability'] == 0) {
								$message_editability = 'false';
								
							}

							if ($m['text'] == '' and count($media_files) == 0) {
								$m['text'] = '<div class="empty_message">Пустое сообщение</div>';
							} else {
								$message_text = '';
								if (str_replace(array("\r\n", "\r", "\n"), '<br>',  $m['text']) != '') {
									$message_text = '<div class="message_text">' . str_replace(array("\r\n", "\r", "\n"), '<br>',  searchUrl($m['text'])) . '</div>';
								}
							}

							
												
							$message_html = '<div id="message_' . $m['id'] . '" class="incoming_hidden_msg message_block incoming_msg ' . $message_edited_class . '" ' . $message_edited_attr . ' editability="' . $message_editability . '">
								<div class="message">' . $message_text . '<b>' . calcTime($m['date'], 'local_message_date') . '</b>' . gridMediaFiles(json_decode($m['media'], 1)) . '</div>
							</div>
							';
							// $message_html = $m['id'];

							$output[$chat_id]['og'][$m['id']] = $message_html;
						}

					}
				}

				// В чате есть отредактированные сообщения
				// if ($json_messages[$chat_id]['ed'] != '') {
					// $output[$chat_id]['ed'] = array();
				$edited_messages_ids_from_db = mysqli_query($connection, "SELECT `id` FROM `messages` WHERE `editability` = 1 and `edited_version` != 0 and (`outgoing_id` = '$local_user_id' or `incoming_id` = '$local_user_id')");
				

				while ($m = mysqli_fetch_assoc($edited_messages_ids_from_db)) {
					$message_id = $m['id'];
					$edited_message_version_from_db = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `edited_version` FROM `messages` WHERE `id` = '$message_id'"))['edited_version'];
					// echo 1;
					// echo $edited_message_version_from_db . ' ==  ' . $json_messages['ed'][$message_id] . ' | ';

					if ($json_messages['ed'][$message_id] == '' or $json_messages['ed'][$message_id] != $edited_message_version_from_db) {
						// echo $edited_message_version_from_db;
						$message_db_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `messages` WHERE `id` = '$message_id'"));
						$message_edit_button = '<div class="edit_message"><img src="' . $link . '/assets/img/icons/pencil.svg"></div>';
						$message_delete_button = '<div class="delete_message"><img src="' . $link . '/assets/img/icons/trash.svg"></div>';
						$message_delete_button = '';
						$message_editability = 'true';

						if ($message_db_data['editability'] == 0) {
							$message_editability = 'false';
							$message_edit_button = '';
							$message_delete_button = '';
						}

						// $message_db_data['text'] = '';
						if (str_replace(array("\r\n", "\r", "\n"), '<br>',  $message_db_data['text']) != '') {
							$message_db_data['text'] = '<div class="message_text">' . str_replace(array("\r\n", "\r", "\n"), '<br>',  searchUrl($message_db_data['text'])) . '</div>';
						} else {
							// $message_db_data['text'] = '';
						}

						if ($message_db_data['text'] == '' and count(json_decode($message_db_data['media'], 1)) == 0) {
							$message_db_data['text'] = '<div class="empty_message">Пустое сообщение</div>';
						}
											
						$message_html = $message_delete_button . $message_edit_button . '
								<div class="message">' . $message_db_data['text'] . '<b>' . calcTime($message_db_data['date'], 'local_message_date') . '</b>' . gridMediaFiles(json_decode($message_db_data['media'], 1)) . '</div>	
							';

						$output['ed'][$message_db_data['id']]['html'] = $message_html;
						$output['ed'][$message_db_data['id']]['ed_ver'] = $message_db_data['edited_version'];
						$output['ed'][$message_db_data['id']]['editability'] = $message_db_data['editability'];
					}
				}


				// foreach ($json_messages[$chat_id]['ed'] as $message_id => $edited_version) {
				// 	$message_data = mysqli_query($connection, "SELECT * FROM `messages` WHERE `id` = '$message_id' and (`outgoing_id` = '$local_user_id' or `incoming_id` = '$user_id')");
				// 	// echo $local_user_id . ' - ';
				// 	if ($message_data -> num_rows != 0) {
				// 		$message_data = mysqli_fetch_assoc($message_data);
				// 		// echo 'vard - ' . var_dump($message_data) . ' --';
						
				// 		if ($message_data['edited_version'] != $edited_version) {
							
				// 		}
				// 		else if ($message_data['editability'] == 0) {
				// 			$output[$chat_id]['ed'][$message_id]['editability'] = $message_data['editability'];
				// 		}
				// 	}

						
				// 	// }
				// }

				// foreach ($json_messages[$chat_id]['ed'] as $message_id => $edited_version) {
				// 	$message_data = mysqli_query($connection, "SELECT * FROM `messages` WHERE `id` = '$message_id' and (`outgoing_id` = '$local_user_id' or `incoming_id` = '$user_id')");
				// 	// echo $local_user_id . ' - ';
				// 	if ($message_data -> num_rows != 0) {
				// 		$message_data = mysqli_fetch_assoc($message_data);
				// 		// echo 'vard - ' . var_dump($message_data) . ' --';
						
				// 		if ($message_data['edited_version'] != $edited_version) {
				// 			$message_edit_button = '<div class="edit_message"><img src="' . $link . '/assets/img/icons/pencil.svg"></div>';
				// 			$message_delete_button = '<div class="delete_message"><img src="' . $link . '/assets/img/icons/trash.svg"></div>';
				// 			$message_delete_button = '';
				// 			$message_editability = 'true';
				// 			if ($message_data['editability'] == 0) {
				// 				$message_editability = 'false';
				// 				$message_edit_button = '';
				// 				$message_delete_button = '';
				// 			}

				// 			$message_text = '';
				// 			if (str_replace(array("\r\n", "\r", "\n"), '<br>',  $message_data['text']) != '') {
				// 				$message_text = '<div class="message_text">' . str_replace(array("\r\n", "\r", "\n"), '<br>',  $message_data['text']) . '</div>';
				// 			}
												
				// 			$message_html = $message_delete_button . $message_edit_button . '
				// 					<div class="message">' . $message_text . '<b>' . calcTime($message_data['date'], 'local_message_date') . '</b>' . gridMediaFiles(json_decode($message_data['media'], 1)) . '</div>	
				// 				';

				// 			$output[$chat_id]['ed'][$message_id]['html'] = $message_html;
				// 			$output[$chat_id]['ed'][$message_id]['ed_ver'] = $message_data['edited_version'];
				// 			$output[$chat_id]['ed'][$message_id]['editability'] = $message_data['editability'];
				// 		}
				// 		else if ($message_data['editability'] == 0) {
				// 			$output[$chat_id]['ed'][$message_id]['editability'] = $message_data['editability'];
				// 		}
				// 	}

						
				// 	// }
				// }

				if ($json_messages[$chat_id]['ur'] != '') {
					$last_unread_message = (int) $json_messages[$chat_id]['ur'];

					if (mysqli_query($connection, "SELECT `id` FROM `messages` WHERE `id` = '$last_unread_message' and `status` != 2") -> num_rows == 0) {
						// возвращает пустое, если сообщение до сих пор не прочитано
						// иначе возвращает "readed"
						$output[$chat_id]['ur'] = 'readed';
					}
				}
			}
		}

		// mysqli_query($connection, "UPDATE `messages` SET `status` = 1 WHERE `incoming_id` ='$local_user_id' and `status` = 0");
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
	} else {
		echo $apiErrorCodes['1.1'];
	}
}






if ($_POST['type'] == 'check-new-messages') {
	$secret_id = $_POST['secret_id'];

	$local_user_id = decodeSecretID($_POST['secret_id'], 'checkNewMessages');

	if ($local_user_id) {
		$messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE `incoming_id` = '$local_user_id' and `status` = 0");

		if ($messages -> num_rows != 0) {
			// echo $messages -> num_rows;
			echo 'new';
			mysqli_query($connection, "UPDATE `messages` SET `status` = 1 WHERE `incoming_id` ='$local_user_id' and `status` = 0");
		}
		else {
			$messages = mysqli_query($connection, "SELECT * FROM `messages` WHERE `incoming_id` = '$local_user_id' and `status` = 1");

			if ($messages -> num_rows != 0) {
				echo 'exist';
			} else {
				echo 'null';
			}
		}
		
	} else {
		echo $apiErrorCodes['1.1'];
	}
}






if ($_POST['type'] == 'view-messages') {
	$secret_id = $_POST['secret_id'];
	$id = $_POST['user_id'];

	$local_user_id = decodeSecretID($_POST['secret_id'], 'viewMessages');

	if ($local_user_id) {
		mysqli_query($connection, "UPDATE `messages` SET `status` = 2 WHERE `incoming_id` = '$local_user_id' and `outgoing_id` = '$id' and `status` != 2 ");
	} else {
		echo $apiErrorCodes['1.1'];
	}


}







if ($_POST['type'] == 'get-media-files') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getMediaFiles');

	if ($user_id) {
		// Текущая дата по часовому поясу сервера
		$server_current_year = (int) date('Y');
		$server_current_month = (int) date('m');
		$server_current_day = (int) date('d');

		$server_current_minutes = (int) date('H') * 60 + (int) date('i') + $timezone;

		// Текущая дата по часовому поясу клиента
		$client_current_year = $server_current_year;
		$client_current_month = $server_current_month;
		$client_current_day = $server_current_day;

		$client_current_minutes = $server_current_minutes;

		// Если в один момент на сервере и у клиенка разные дни, то высчитываем настоящее время у клиента
		if ($client_current_minutes >= 1440) {

			$client_current_day++;
			$client_current_minutes -= 1440;

			if (cal_days_in_month(CAL_GREGORIAN, $server_current_month, $server_current_year) < $client_current_day) {
				$client_current_month++;
				$client_current_day = 1;

				if ($client_current_month > 12) {
					$client_current_year++;
					$client_current_month = 1;
				}
			}
		}

		$message_ids = $_POST['message_ids'];

		if ($message_ids != '') {
			$message_ids = json_decode($message_ids);
			$output = array();

			foreach ($message_ids as $message_id) {
				$message_data = mysqli_query($connection, "SELECT * FROM `messages` WHERE `id` = '$message_id' ");

				if ($message_data -> num_rows != 0) {
					$message_data = mysqli_fetch_assoc($message_data);

					// Доступ к данным сообщения имею только: тот, кто отправил и тот, кому отправили
					if ($message_data['outgoing_id'] == $user_id or $message_data['incoming_id'] == $user_id) {
						$local_user_id = $message_data['outgoing_id'];

						$local_user_data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$local_user_id'"));

						$sex = '';
						if ($local_user_data['sex'] == 'Женский') {
							$sex = 'a';
						}
						$user_data_array = array("user_id" => $local_user_id, "first_name" => $local_user_data['first_name'], "last_name" => $local_user_data['last_name'], "photo" => $local_user_data['photo'], "photo_style" => unserialize($local_user_data['photo_style']), "sex" => $sex);

						$date = calcTime($message_data['date'], 'fullDate');

						$media_files = json_decode($message_data['media']);

						foreach ($media_files as $key => $value) {
							if ($value == '' or $value == null) {
								unset($media_files[$key]);
							}
						}

						$output[$message_id] = array("media" => $media_files, "date" => $date, "user_data" => $user_data_array);
					}
					
				}
			}

			echo json_encode($output, JSON_UNESCAPED_UNICODE);
		} else {
			echo 'message_ids is empty';
		}

	} else {
		echo $apiErrorCodes['1.1'];
	}


}





if ($_POST['type'] == 'get-message-data') {
	$user_id = decodeSecretID($_POST['secret_id'], 'getMessageData');

	if ($user_id) {
		mysqli_query($connection, "UPDATE `messages` SET `status` = 2 WHERE `incoming_id` = '$local_user_id' and `outgoing_id` = '$id' and `status` != 2 ");

		$message_id = $_POST['message_id'];

		$message_data = mysqli_query($connection, "SELECT * FROM `messages` WHERE `id` = '$message_id' and (`outgoing_id` = '$user_id' or `incoming_id` = '$user_id')");

		if ($message_data -> num_rows != 0) {
			$message_data = mysqli_fetch_assoc($message_data);

			$message_data_media = json_decode($message_data['media']);

			if ($message_data_media != '') {
				if ($message_data_media != '') {
					foreach ($message_data_media as $file => $value) {
						if ($file == null or $value == null) {
							unset($message_data_media[$file]);
							// unset($file);
						}
					}
				}
				
				$message_data_media = json_encode($message_data_media, JSON_UNESCAPED_UNICODE);
			} else {
				$message_data_media = '';
			}

			$local_array = array("id" => $message_id, "outgoing_id" => $message_data['outgoing_id'], "incoming_id" => $message_data['incoming_id'], "status" => $message_data['status'], "text" => $message_data['text'], "media" => $message_data_media, "date" => $message_data["date"], "editability" => $message_data['editability'], "edited_version" => $message_data['edited_version']);

			echo json_encode($local_array, JSON_UNESCAPED_UNICODE);
		} else {
			echo 'access denied';
		}
	} else {
		echo $apiErrorCodes['1.1'];
	}
}






if ($_POST['type'] == 'save-message') {
	$user_id = decodeSecretID($_POST['secret_id'], 'saveMessage');

	if ($user_id) {
		$message_text = $_POST['message'];
		$media_files = json_decode($_POST['media_files']);
		$chat_id = $_POST['chat_id'];
		$message_id = $_POST['message_id'];

		// echo $message_id;

		$message_data = mysqli_query($connection, "SELECT COUNT(*) FROM `messages` WHERE `id` = '$message_id' and `outgoing_id` = '$user_id' and `editability` = 1");

		if ($message_data -> num_rows == 0) {
			echo 'access denied';
			exit();
		}

		if (str_replace(' ', '', $message_text) == '' and count($media_files) == 0) {
			// echo 'error. empty message';
			// exit();
		}

		$media_files = json_encode($media_files, JSON_UNESCAPED_UNICODE);
		mysqli_query($connection, "UPDATE `messages` SET `text` = '$message_text', `media` = '$media_files', `edited_version` = `edited_version` + 1 WHERE `id` = '$message_id'");
		echo 'success';


	} else {
		echo $apiErrorCodes['1.1'];
	}
}





if ($_POST['type'] == 'user-activity') {

	function calcTimeDifference ($time) {
		$array_time = explode('.', $time);
		$server_day = $array_time[0];
		$server_month = $array_time[1];
		$server_year = $array_time[2];

		$server_hour = $array_time[3];
		$server_minutes = $array_time[4];
		$server_seconds = $array_time[5];

		$current_day = date('d');
		$current_month = date('m');
		$current_year = date('Y');

		$current_hour = date('h');
		$current_minutes = date('i');
		$current_seconds = date('s');

		if ($current_year == $server_year and $current_month == $server_month and $current_day == $server_day and $current_hour == $server_hour and $current_minutes == $server_minutes) {
			if ($current_seconds - $server_seconds < 2) {
				return true;
			}
		}
		return false;

		// return $time . ' - ' . date('d.m.Y.h.i.s');
	}

	$user_id = decodeSecretID($_POST['secret_id'], 'userActivity');

	if ($user_id) {
		$user_activity = json_decode($_POST['user_activity'], 1);

		foreach ($user_activity as $chat_id => $new_chat_activity) {
			$chat_data = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$user_id' and `second_user_id` = '$chat_id') or (`first_user_id` = '$chat_id' and `second_user_id` = '$user_id')");

			if ($chat_data -> num_rows != 0) {
				$chat_data = mysqli_fetch_assoc($chat_data);
				$chat_activity = $chat_data['activity'];
				$chat_activity_db_id = $chat_data['id'];

				if ($chat_activity == '') {
					$chat_activity = array($user_id => array("chatting" => '', "sending_files" => ''), $chat_id => array("chatting" => '', "sending_files" => ''));
				} else {
					$chat_activity = json_decode($chat_activity, 1);
				}

				if ($new_chat_activity['chatting'] == 1) {
					$chat_activity[$user_id]['chatting'] = date('d.m.Y.h.i.s');
				}
				if ($new_chat_activity['sending_files'] == 1) {
					$chat_activity[$user_id]['sending_files'] = date('d.m.Y.h.i.s');
				}

				$chat_activity = json_encode($chat_activity);
				mysqli_query($connection, "UPDATE `chats` SET `activity` = '$chat_activity' WHERE `id` = '$chat_activity_db_id'");
			}
		}

		$chats = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$user_id' or `second_user_id` = '$user_id')");
		$output = array();
		while ($c = mysqli_fetch_assoc($chats)) {
			$second_user_id = $c['first_user_id'];

			if ($c['first_user_id'] == $user_id) {
				$second_user_id = $c['second_user_id'];
			}

			$chat_activity = $c['activity'];
			if ($chat_activity != '') {
				$chat_activity = json_decode($chat_activity, 1);
				$second_user_activity = $chat_activity[$second_user_id];
				// echo $second_user_activity['chatting'];

				// $chat_activity[$second_user_id] = array();
				// У статуса "Отправляет файл" выше приоритет

				if ($second_user_activity['sending_files'] != '') {
					$output[$second_user_id]['sending_files'] = calcTimeDifference($second_user_activity['sending_files']);
				}
				if ($second_user_activity['chatting'] != '') {
					$output[$second_user_id]['chatting'] = calcTimeDifference($second_user_activity['chatting']);
				}

				// echo calcTimeDifference($second_user_activity['chatting']);
				if (!$output[$second_user_id]['chatting']) {
					unset($output[$second_user_id]['chatting']);
				}

				if (!$output[$second_user_id]['sending_files']) {
					unset($output[$second_user_id]['sending_files']);
				}

				if (!isset($output[$second_user_id]['sending_files']) and !isset($output[$second_user_id]['chatting'])) {
					unset($output[$second_user_id]);
				}
			}
		}
		if ($output != array()) {
			echo json_encode($output);
		}
	} else {
		echo $apiErrorCodes['1.1'];
	}
}


