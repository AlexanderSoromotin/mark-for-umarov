<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('Banned', '/banned');
	redirect('pre-deleted', '/pre-deleted');
	redirect('unlogged', '/authorization');

	include_once '../inc/head.php';

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Мессенджер</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

	<?
		include_once '../inc/header.php'; // Шапка
		include_once '../assets/online.php'; // Онлайн
	?>

	<!-- Хронология -->
	<div class="history">
		<div class="block">
			<a href="<?$link?>/">Главная</a>
			<img draggable="false" src="<? $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?$link?>/messenger">Мессенджер</a>
		</div>
	</div>

	<div class="main">
		<div class="count_textarea_lines"></div>
		<div class="users">
			<!-- <h2>Список пользователей</h2> -->
			<div class="search">
				<img draggable="false" src="<?=$link?>/assets/img/icons/search.svg">
				<input type="" name="" placeholder="Поиск">
			</div>

			<center>
				<a class="start_chat" href="<?= $link ?>/profile/friends/?act=search-friends">
					<button class="button-3">Начать чат с новым пользователем</button>
				</a>
			</center>

			<ul>
			</ul>
		</div>

		<div class="chats">
			<div id="chat_start" class="chats-block">
				Выберите чат для начала переписки
			</div>
		</div>
	</div>


		
	<script type="text/javascript">
		<?
			if ($_GET['id'] != '') {
				$get_id = $_GET['id'];
				$get_local_user_data = mysqli_query($connection, "SELECT * FROM `users` WHERE `id` = '$get_id'");
				$thisChat = mysqli_query($connection, "SELECT * FROM `chats` WHERE (`first_user_id` = '$user_id' and `second_user_id` = '$get_id') or (`first_user_id` = '$get_id' and `second_user_id` = '$user_id')");
			}
		?>

		selectTab('messenger');
		
		$(document).bind("drop dragover", function(e){
		    if(e.target.type != "file"){
		        e.preventDefault();
		    }
		});

		var files_list = [];
		var fileId = 1;

		function addFile (chat_id, files, compress) {
			// Загрузка файла на сервер и последующее добавление файла в список файлов
			// console.log('compress', compress);
			// Временной промежуток между добавлением файлов
			interval = 150;
			// if (method == 'fast') {
			// 	interval = 10;
			// }

			if (files.length > 0) {
				let i = 0;
				var addFileInterval = setInterval(function () {
					if (i < files.length && files[i]['size'] > 0) {

						// Ограничение максимального объёма файла
						if (files[i].size / 1024 / 1024 > 25) {
							alert('Размер файла "' + files[i].name + '" превышает 25мб');
							i++;
							return;
						}

						if (files_list[chat_id] == undefined) {
							files_list[chat_id] = [];
						}

						if( typeof files[i] == 'undefined' ) return;

						// Создание прогрессбара
						$('#chat_' + chat_id + ' .input-form label').text('').append('<div class="progress_bar"><div class="progress_line"></div></div><p class="file_name">' + files[i].name.substr(0, 35) + '</p>')
						
						// Создание счётчика количества файлов
						if ($('#chat_' + chat_id + ' .input .upload-files-info').length == 0) {
							$('#chat_' + chat_id + ' .input').append('<div class="upload-files-info">Файлов: <b>1</b> <button class="button-3 delete_all_media_files">Удалить все</button></div>')
						}

						// Анимация появления счётчика файлов
						$('#chat_' + chat_id + ' .upload-files-info').addClass('upload-files-info-display');

						// Подготовка данных для отправки файла на сервер
						var data = new FormData();
						data.append( 0, files[i] );
						data.append('type', 'upload-file');
						data.append('stock_filename', files[i].name);
						data.append('file_type', 'message_media');
						data.append('file_id', fileId);
						if (compress == false) {
							data.append('compress', false);
							// console.log('addFile compress', false);
						}
						
						data.append('secret_id', '<?= md5('user_' . $user_token . '_uploadFile')?>');

						var files_i = files[i];
						files_sending[chat_id] = 1;

						// Отправка файла на сервер
						$.ajax({
							url : '<?=$link?>/inc/uploadFiles.php',
							type : 'POST',
							data : data,
							cache : false,
							processData : false,
							contentType : false,
							xhr: function(){
						        let xhr = $.ajaxSettings.xhr();
						        xhr.upload.addEventListener('progress', function (e) {
						        	if (e.lengthComputable) {
						            	let percentComplete = Math.ceil(e.loaded / e.total * 100);
						            	// $('.opened_chat .input-form label').text('Загружено ' + percentComplete + '%');
						            	$('#chat_' + chat_id + ' .input-form label .progress_line').css({"width" : percentComplete + '%'})
						        	}
						        }, false);
						        return xhr;
						    }, 
							success : function(result) {
								// console.log(result)
								if (result != '') {
									result = JSON.parse(result)[0];

									files_list[chat_id].push(files_i);

									// Добавление к файлам, находящимся в списке файлов дополнительных данных
									search_file: for (chat_id in files_list) {
										for (file_index in files_list[chat_id]) {
											object = files_list[chat_id][file_index];
											localObject = new Object();

											if (object['file_id'] == result['file_id']) {
												localObject = object;
												files_list[chat_id][file_index] = new Object();

												// console.log(object['name'])
												// console.log(result['stock_name'])

												files_list[chat_id][file_index]['server_name'] = result['name'];
												files_list[chat_id][file_index]['name'] = result['stock_name'];
												files_list[chat_id][file_index]['file_id'] = fileId;
												files_list[chat_id][file_index]['file_type'] = result['file_type'];
												files_list[chat_id][file_index]['mime'] = result['mime'];
												files_list[chat_id][file_index]['size'] = localObject['size'];
											}
										}
									}

									// Изменение данных в локальном хранилище
									if (localStorage.getItem('saved_chat_data_' + chat_id) == '' || localStorage.getItem('saved_chat_data_' + chat_id) == null) {
										localStorage.setItem('saved_chat_data_' + chat_id, '{"message":"", "media": []}');
									}

									saved_chat_data = JSON.parse(localStorage.getItem('saved_chat_data_' + chat_id));

									saved_chat_data['media'] = [];
									for (file_index in files_list[chat_id]) {
										file = files_list[chat_id][file_index];

										saved_chat_data['media'][file['file_id']] = new Object();
										saved_chat_data['media'][file['file_id']]['name'] = file['name'];
										saved_chat_data['media'][file['file_id']]['server_name'] = file['server_name'];
										saved_chat_data['media'][file['file_id']]['file_id'] = file['file_id'];
										saved_chat_data['media'][file['file_id']]['file_type'] = file['file_type'];
										saved_chat_data['media'][file['file_id']]['mime'] = file['mime'];
										saved_chat_data['media'][file['file_id']]['size'] = file['size'];

									}

									localStorage.setItem('saved_chat_data_' + chat_id, JSON.stringify(saved_chat_data));

									// Добавление файла в файлбар под полем ввода сообщения
									if (result['file_type'] == 'img') {
										$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + result['stock_name'] + "' class='media_file hidden_media_file'><img src='" + result['url'] + "'><div class='file_name'>" + result['stock_name'] + "</div><img class='delete_media_file' alt='file_" + fileId + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
										setTimeout(function () {
											$('.media_file').removeClass('hidden_media_file')
											$('#chat_' + chat_id + ' .input .upload-files-info b').text($('.uploaded-files .media_file').length);
										}, 300)
									} else {
										$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + result['stock_name'] + "' class='media_file hidden_media_file'><div class='file_background'>" + result['mime'] + "</div><div class='file_name'>" + result['stock_name'] + "</div><img class='delete_media_file' alt='file_" + fileId + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
										setTimeout(function () {
											// Анимация появления файлов в файлбаре
											$('.media_file').removeClass('hidden_media_file')

											// Обновление количества загруженных файлов
											$('#chat_' + chat_id + ' .input .upload-files-info b').text($('.uploaded-files .media_file').length);
										}, 300)
									}
									fileId++;
									if (i == (files.length)) {
										// Если загруженный файл является последний, то прогрессбар скрывается
										$('#chat_' + chat_id + ' .input-form label .progress_bar').remove();
										$('#chat_' + chat_id + ' .input-form label').text('');
										$('#chat_' + chat_id + ' .input-form label').append('Выберите <b>файл</b> или перетащите его сюда');

										// Если загруженный файл является последний, то поле сбрасывания файлов скрывается
										$('#chat_' + chat_id + ' .input textarea').css({"display" : "flex"})
										$('#chat_' + chat_id + ' .input .upload-files').css({"display" : "none"});
										$('#chat_' + chat_id + ' .input .add-media-file').css({"display" : "flex"})	
									}

									// Фокус на поле ввода
									textarea_val = $('.main .chats #chat_' + chat_id + ' .input textarea').val();
									$('.main .chats #chat_' + chat_id + ' .input textarea').val('').focus().val(textarea_val);

								}
							},
							error : function (result) {
								// console.log("error upload file: " + result)
							}
						});
						i++;
					} else {
						// Все файлы загружены на сервер
						clearInterval(addFileInterval);
						files_sending[chat_id] = 0;
						$('#chat_' + chat_id + ' .input input').val('');
						// console.log('Все файлы загружены на сервер')
					}
				}, interval)
			}
		}

		function deleteFile (file_id) {
			// Удаление файла из списка загруженных файлов
			for (var chat_id in files_list) {
				for (index in files_list[chat_id]) {
					object = files_list[chat_id][index];

					if (object['file_id'] == file_id) {
						// Отправка запроса на удаление с сервера загруженного файла
						$.ajax({
							url: "<?= $link ?>/inc/uploadFiles.php",
							type: "POST",
							cache: false,
							data: {
								type: "delete-file",
								file_name: object['server_name'],
								secret_id: "<?= md5('user_' . $user_token . '_deleteFile')?>"
							},
							success: function (result) {
								// Файл успешно удалён
								// console.log('Файл удалён')
								if (result == 'success') {

									// Анимация скрытия удалённого файла из файлбара
									$('.opened_chat .delete_media_file[alt="file_' + file_id + '"]').parent().addClass('hidden_media_file');

									setTimeout(function () {
										// Удаление файла из файлбара
										$('.opened_chat .delete_media_file[alt="file_' + file_id + '"]').parent().remove();

										// Обновление счётчика файлов
										if ($('#chat_' + chat_id + ' .uploaded-files .media_file').length <= 0) {
											$('#chat_' + chat_id + ' .input .upload-files-info').removeClass('upload-files-info-display');
										} else {
											$('#chat_' + chat_id + ' .input .upload-files-info b').text($('#chat_' + chat_id + ' .uploaded-files .media_file').length);
										}
										
									}, 350);
								}
							}
						})
						delete(files_list[chat_id][index]);
						return;
					}
				}
			}
		}

		$('body').on('click', '.delete_all_media_files', function () {
			// Удаление всех загруженных файлов
			chat_id = $(this).parent().parent().parent().attr('id').replace('chat_', '');
			delete(files_list[chat_id]);

			saved_chat_data = JSON.parse(localStorage.getItem('saved_chat_data_' + chat_id));
			if (saved_chat_data != null && saved_chat_data != undefined) {
				saved_chat_data['media'] = [];
				localStorage.setItem('saved_chat_data_' + chat_id,  JSON.stringify(saved_chat_data));
			}
			
			// Удаление файлов из файлбара
			$('#chat_' + chat_id + ' .uploaded-files .media_file').addClass('hidden_media_file');
			$('#chat_' + chat_id + ' .upload-files-info').removeClass('upload-files-info-display');

			setTimeout(function () {
				$('#chat_' + chat_id + ' .uploaded-files .media_file').remove();
			}, 300)
		})

		// Закрытие поля загрузки файла
		$(document).on('click', '.upload-files img', function () {
			$('.opened_chat .input textarea').css({"display" : "flex"})
			$('.opened_chat .input .upload-files').css({"display" : "none"})
			$('.opened_chat .input .add-media-file').css({"display" : "flex"})
		})

		// Взаимодействие с полем загрузки файлов
		$(document).on('change', '.input input', function () {
			if (compress_files == false) {
				// console.log('chatnge falsee', this.files)
				addFile($(this).parents('.chats-block').attr('id').replace('chat_', ''),this.files, false);
				console.log('addFile>chat_id', $(this).parents('.chats-block').attr('id').replace('chat_', ''));
				compress_files = true;
				
			} else {
				addFile($(this).parents('.chats-block').attr('id').replace('chat_', ''),this.files);
				console.log('addFile>chat_id', $(this).parents('.chats-block').attr('id').replace('chat_', ''));
				// console.log('chatnge true')
			}
			
			// console.log('chatnge')
		})

		// Нажатие на "скрепку"
		var compress_files = true;
		$(document).on('click', '.add-media-file img:eq(0)', function () {
			// $('.opened_chat .input textarea').css({"display" : "none"})
			// $('.opened_chat .input .add-media-file').css({"display" : "none"})
			// $('.opened_chat .input .upload-files').css({"display" : "flex"})

			$(".opened_chat .input input").click();
		})

		$(document).on('click', '.add_media_file_without_compress', function () {
			// $('.opened_chat .input textarea').css({"display" : "none"})
			// $('.opened_chat .input .add-media-file').css({"display" : "none"})
			// $('.opened_chat .input .upload-files').css({"display" : "flex"})
			compress_files = false;
			$(".opened_chat .input input").click();
		})

		$(document).on('click', '.delete_media_file', function () {
			// Удаление конкретного файла из файлбара
			let file_id = $(this).attr('alt').replace('file_', '');
			let chat_id = $(this).parents('.chats-block').attr('id').replace('chat_', '');
			deleteFile(file_id);
			
			// Изменение информации в лкальном хранилище
			saved_chat_data = JSON.parse(localStorage.getItem('saved_chat_data_' + chat_id));

			if (saved_chat_data != null && saved_chat_data != undefined) {

				// Избавление от пустых элементов в массиве файлов
				for (file_index in saved_chat_data['media']) {
					file = saved_chat_data['media'][file_index];				
					if (file == null) {
						delete (saved_chat_data['media'][file_index]);
					} else {
						if (file['file_id'] == file_id) {
							delete (saved_chat_data['media'][file_index]);
						}
					}	
				}

				// Запись изменённой информации в локальное хранилище
				localStorage.setItem('saved_chat_data_' + chat_id,  JSON.stringify(saved_chat_data));
			}
		})

		// Дроп файлов в поле загрузки файлов
		$(document).on("drop", ".chats-block .input-form label", function(e) {
	        // $('.chats-block .input textarea').css({"display" : "flex"})
			// $('.chats-block .input .upload-files').css({"display" : "none"})

			chat_id = $(this).parents('.chats-block').attr('id').replace('chat_', '');
			console.log($(this))
			addFile(chat_id, e.originalEvent.dataTransfer.files)
		});

		var dragAnim = 1;
		// Перетаскивание файла по странице
		$(document).on('dragenter', function (e) { 
			if (dragAnim == 1) {
				$('.opened_chat .input textarea').css({"display" : "none"})
				$('.opened_chat .input .upload-files').css({"display" : "flex"})
				$('.opened_chat .input .add-media-file').css({"display" : "none"})
				dragAnim = 0;
			}
		});

		// Начало перетаскивания файла по полю для дропа файлов
		$(document).on('dragenter', '.chats-block .input-form', function (e) { 
			// $('.opened_chat .input .upload-files').css({"background-color" : "rgba(0, 0, 0, .07)"});
			$('.opened_chat .input .add-media-file').css({"display" : "none"})
			$('.opened_chat .input textarea').css({"display" : "none"})
			$('.opened_chat .input .upload-files').css({"display" : "flex"})
		});

		// Получение информации об онлайне пользователей
		function getLastOnline () {
			users_length = $('.main .users ul li').length;
			users = [];

			// Формирование списка пользователей
			for (let i = 0; i < users_length; i++) {
				users.push( Number($('.main .users ul li:eq(' + i + ')').attr('id').replace('user_', '')) );
			}
			users = JSON.stringify(users);

			// Отправка запроса на сервер
			$.ajax({
				url: '<?= $link ?>/inc/online.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'get-online',
					users: users
				},
				success: function (result) {
					if (result == '') {
						return;
					}

					users = JSON.parse(result);
					users_length = Object.keys(users).length;

					// Обновление значений в полях онлайна
					for (user_id in users) {
						if (users[user_id] == 'Онлайн') {
							$('#chat_' + user_id + ' .col-1').addClass('online_active');
							$('#user_' + user_id + ' .col-1').addClass('online_active');
						} else {
							$('#chat_' + user_id + ' .col-1').removeClass('online_active');
							$('#user_' + user_id + ' .col-1').removeClass('online_active');
						}
						if ($('#chat_' + user_id + ' .info .col-2 p').text() != users[user_id]) {
							$('#chat_' + user_id + ' .info .col-2 p').text(users[user_id]);
						}
					}
				}
			})
		}
		setInterval(() => getLastOnline(), 5000)

		// Ввод текста в поля ввода сообщений
		$('body').on('textarea keyup', '.chats .input textarea', function (e) {
			var text = $(this).val();   
			var lines = text.split(/\r|\r\n|\n/);
			var count = lines.length;

			// Вычисление необходимой высоты для textarea 
			$('.count_textarea_lines').text('')
			$('.count_textarea_lines_br').remove();
			for (var i = 0; i < count; i++) {
				if (lines[i] != '') {
					$('.count_textarea_lines').append('<p>' + lines[i] + '</p>');
				} else {
					$('.count_textarea_lines').append('<p class="count_textarea_lines_br"></p>')
				}
			}
			height = $('.count_textarea_lines').css('height');
			$(this).css({'height' : height});

			chat_id = $(this).attr('alt');

			// console.log(e.key)
			if (e.key.length == 1 || e.key == 'Backspace') {
				if (characters_entered[chat_id] == undefined) {
					characters_entered[chat_id] = 1;
				} else {
					characters_entered[chat_id]++;
				}
			}
			// console.log(characters_entered);
		})
		
		// Экранирование текста
		function addslashes( str ) {
			return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
		}

		// Открытие чата
		function openChat (user_id) {
			$('.main .chats .chats-block').css({'display' : 'none'});
			$('.main .chats #chat_' + user_id).css({'display' : 'flex'});
			$('.main .chats .opened_chat').removeClass("opened_chat");
			$('.main .chats #chat_' + user_id).addClass("opened_chat");

			textarea_val = $('.main .chats #chat_' + user_id + ' .input textarea').val();
			$('.main .chats #chat_' + user_id + ' .input textarea').val('').focus().val(textarea_val);
		}

		// Клик на пользователя
		$('body').on('click', '.main .users ul li', function () {
			id = $(this).attr("id").replace('user_', '');
			openChat(id);
			if ($(this).hasClass('unread')) {
				$(this).removeClass('unread');
				$(this).children('.notification').remove();
			}
			viewMessages(id);
		})

		// Отметка сообщений как "прочитано"
		function viewMessages (id) {
			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				type: 'POST',
				cache: false,
				data: {
					type: 'view-messages',
					user_id: id,
					secret_id: '<?= md5('user_' . $user_token . '_viewMessages')?>'
				},
				success: function (result) {
					// console.log(result)
				}
			})
		}

		let limit = 1000;
		// Формирование списка пользователей
		function getUsers () {
			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				cache: false,
				type: 'POST',
				data: {
					type: 'get-users',
					secret_id: '<?= md5('user_' . $user_token . '_getUsers')?>',
					limit: '0, ' + limit,
					html: true
				},
				success: function (result) {
					// console.log('r: ' + result)
					$('.main .users ul li').remove();
					$('.main .users ul').append(result);
					getLastOnline();
				}
			})
		}
		getUsers();

		// Получение всех чатов с историей переписки
		$.ajax({
			url: '<?= $link ?>/inc/messages.php',
			cache: false,
			type: 'POST',
			data: {
				type: 'get-chats',
				secret_id: '<?= md5('user_' . $user_token . '_getChats')?>',
				limit: '0, ' + limit,
				html: true
			},
			success: function (result) {
				if (result != 'null') {
					$('.main .chats').prepend(result);
					getMessages()
				}

				chats_length = $('.chats-block').length;
				for (let eq = 0; eq < chats_length; eq++) {
					let chat_id = $('.chats-block:eq(' + eq + ')').attr('id');
					if (chat_id != '' && chat_id != 'chat_start') {

						chat_id = chat_id.replace('chat_', '');
						saved_chat_data = localStorage.getItem('saved_chat_data_' + chat_id.replace('chat_', ''));
						temporary_storage = localStorage.getItem('temporary_storage_' + chat_id.replace('chat_', ''));
						let files = [];

						if (temporary_storage != '' && temporary_storage != null) {
							temporary_storage = JSON.parse(temporary_storage);
							files = temporary_storage['media'];

							$('#chat_' + chat_id + ' .input textarea').text(temporary_storage['message']);

							var lines = temporary_storage['message'].split(/\r|\r\n|\n/);
							var count = lines.length;

							$('.count_textarea_lines').text('')
							$('.count_textarea_lines_br').remove();
							for (var i = 0; i < count; i++) {
								if (lines[i] != '') {
									$('.count_textarea_lines').append('<p>' + lines[i] + '</p>');
								} else {
									$('.count_textarea_lines').append('<p class="count_textarea_lines_br"></p>')
								}
							}

							height = $('.count_textarea_lines').css('height');

							localStorage.setItem('saved_chat_data_' + chat_id.replace('chat_', ''), localStorage.getItem('temporary_storage_' + chat_id.replace('chat_', '')));
							localStorage.removeItem('temporary_storage_' + chat_id.replace('chat_', ''));

							$('#chat_' + chat_id + ' .input textarea').css({'height' : height});
						} else {
							if (saved_chat_data != '' && saved_chat_data != null) {
								saved_chat_data = JSON.parse(saved_chat_data);
								files = saved_chat_data['media'];

								$('#chat_' + chat_id + ' .input textarea').text(saved_chat_data['message']);
  
								var lines = saved_chat_data['message'].split(/\r|\r\n|\n/);
								var count = lines.length;

								$('.count_textarea_lines').text('')
								$('.count_textarea_lines_br').remove();
								for (var i = 0; i < count; i++) {
									if (lines[i] != '') {
										$('.count_textarea_lines').append('<p>' + lines[i] + '</p>');
									} else {
										$('.count_textarea_lines').append('<p class="count_textarea_lines_br"></p>')
									}
								}

								height = $('.count_textarea_lines').css('height');

								$('#chat_' + chat_id + ' .input textarea').css({'height' : height});
							
							}
						}

						for (file_index in files) {
							file = files[file_index];
							if (file == null) {
								delete(files[file_index]);
							}
						}
						// console.log('files', files);

						if (files != null && files != undefined) {
							files_list[chat_id] = files;

							for (file_index in files) {
								file = files[file_index];

								// fileId++;
								// files_list[chat_id][fileId] = file;
								// files_list[chat_id][fileId]['file_id'] = fileId;

								if (file['file_type'] == 'img') {
									$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><img src='" + link + '/uploads/user_files/' + file['server_name'] + "'><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + file['file_id'] + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
								} else {
									$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><div class='file_background'>" + file['mime'] + "</div><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + file['file_id'] + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
								}
							}

							if ($('#chat_' + chat_id + ' .input .upload-files-info').length == 0 && files_list[chat_id].length > 0) {
								$('#chat_' + chat_id + ' .input').append('<div class="upload-files-info">Файлов: <b>1</b> <button class="button-3 delete_all_media_files">Удалить все</button></div>')
							}

							setTimeout(function () {
								// console.log(files_list[chat_id])
								// console.log('files1', files)
								if (files != null && files != undefined) {
									$('#chat_' + chat_id + ' .upload-files-info').addClass('upload-files-info-display');
									// console.log('upload-files-info-display', files);
								}
								$('#chat_' + chat_id + ' .media_file').removeClass('hidden_media_file')
								$('#chat_' + chat_id + ' .input .upload-files-info b').text($('.uploaded-files .media_file').length);
							}, 50)

						}
						
						
						

					}
				}

				$('.input textarea').bind('textarea paste', function(e) {
		      		var e = e.originalEvent;
		      		if (e.clipboardData.files.length != 0) {
		      			$('.opened_chat .input textarea').css({"display" : "none"})
						$('.opened_chat .input .upload-files').css({"display" : "flex"})
						$('.opened_chat .input .add-media-file').css({"display" : "none"})
				    	addFile($(this).parents('.chats-block').attr('id').replace('chat_', ''), e.clipboardData.files)
		      		}
		      		
				});

				<?
					// Запланировано открытие существующего чата
					if (($get_local_user_data -> num_rows != 0 and $thisChat -> num_rows != 0 and $get_id != $user_id) or ($get_local_user_data -> num_rows != 0 and $thisChat -> num_rows == 0 and $get_id != $user_id and in_array($get_id, $user_friends))) {
						echo 'openChat(' . $get_id . ');';
					}
				?>	
			}
		})

		// Если приходит сообщение от чата, который у нас не отображён, то отправляем запрос
		function getChat (id, open_id) {
			if ($('.chats #chat_' + id).length != 0) {
				return;
			}
			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				cache: false,
				type: 'POST',
				data: {
					type: 'get-chat',
					secret_id: '<?= md5('user_' . $user_token . '_getChat')?>',
					id: id,
					html: true
				},
				success: function (result) {
					// console.log('r: ' + result)
					// console.log(JSON.parse(result))
					// console.log('chat getted - ' + id);
					$('.main .chats').prepend(result);
					// openChat(19);
					$('.input textarea').bind('textarea paste', function(e) {
			      		var e = e.originalEvent;
			      		if (e.clipboardData.files.length != 0) {
			      			$('.opened_chat .input textarea').css({"display" : "none"})
							$('.opened_chat .input .upload-files').css({"display" : "flex"})
							$('.opened_chat .input .add-media-file').css({"display" : "none"})
					    	addFile($(this).parents('.chats-block').attr('id').replace('chat_', ''), e.clipboardData.files)
			      		}
			      		
					});
					if (open_id) {
						openChat(open_id);
					}
					// console.log(id)
				}
			})
		}


		// Добавление сообщения в чат
		function addMessage(id, message) {
			// console.log('addmessage: ' + message)
			if ( $('.chats #chat_' + id + ' .chating_date:eq(0)').text() != 'Сегодня' ) {
				
				$('.chats #chat_' + id + ' .chat').prepend('<div class="chating_date">Сегодня</div>');
				
				// console.log('index', $('.chats #chat_' + id + ' .chating_date:eq(0)').index())
				if ($('#chat_' + id + ' div:eq(' + ($('.chats #chat_' + id + ' .chating_date:eq(0)').index() - 1) + ')').hasClass('unread')) {

					// $('.chats #chat_' + id + ' .chating_date:eq(0)').addClass(class_unread);
				}


			}

			$('.main #chat_' + id + ' .chat').prepend(message);

			setTimeout(function () {
				$('.main #chat_' + id + ' .chat .incoming_hidden_msg, .main #chat_' + id + ' .chat .outgoing_hidden_msg').removeClass('incoming_hidden_msg').removeClass('outgoing_hidden_msg');
			}, 50)

			setTimeout(function () {
				$('.main #chat_' + id + ' .chat .unread_messages').css({'height' : '0', 'padding' : '0', 'opacity' : '0'});
			}, 1000)

			// Добавляем уведомление
			if ($('.main #chat_' + id).css('display') == 'none' || $('.main #chat_' + id).css('display') == undefined) {
				$('.main #user_' + id).addClass('unread');
				notification_count = Number($('.main #user_' + id + ' .notification').text());
				if (notification_count > 0) {
					$('.main #user_' + id + ' .notification').text(notification_count + 1)
				} else {
					$('.main #user_' + id).append('<div class="notification">1</div>')
				}
			} else {
				viewMessages(id)
			}
			// Изменяем последнее сообщение в блоке пользователя в .users
			// console.log(message.length)
			message_text = $('.main #chat_' + id + ' .message_block:eq(0) .message_text').text();

			if ($('.main #chat_' + id + ' .message_block:eq(0)').hasClass('incoming_msg')) {
				if (message_text.length == 0) {
					$('.main #user_' + id + ' p:eq(1)').text('').prepend('Вы: <b class="media">Вложение</b>');
				} else {
					if (message_text.length > 75) {
						$('.main #user_' + id + ' p:eq(1)').text(message_text.substr(0, 70) + '...').prepend();
					} else {
						$('.main #user_' + id + ' p:eq(1)').text(message_text).prepend();
					}
				}
				
			} else {
				if (message_text.length == 0) {
					$('.main #user_' + id + ' p:eq(1)').text('').prepend('<b class="media">Вложение</b>');
				} else {
					if (message_text.length > 75) {
						$('.main #user_' + id + ' p:eq(1)').text('Вы: ' + message_text.substr(0, 70) + '...');
					} else {
						$('.main #user_' + id + ' p:eq(1)').text('Вы: ' + message_text);
					}
				}
				if (!$('.main #user_' + id + ' p:eq(1)').hasClass('unread')) {
					$('.main #user_' + id + ' p:eq(1)').addClass('unread');
				}
				
			}
			

			// $('.main #chat_' + id + ' .chat').scrollTop = $('.main #chat_' + id + ' .chat').scrollHeight;
			// $('.main #chat_' + id + ' .chat').scrollTo({top: $('.main #chat_' + id + ' .chat').height(), behavior: 'smooth'});

			if ($('.main #chat_' + id + ' .chat .message_block:eq(0)').hasClass('outgoing_msg')) {
				$('.main #chat_' + id + ' .chat').animate({
		        	scrollTop: $(".message_block:eq(0)").offset().top
		    	}, 500);
			}
		}

		// Отправка сообщения
		function sendMessage (id, message) {

			if ((message.replace(' ', '').replace(/[\n\r]/g, '').replace(/<\/?[^>]+(>|$)/g, "").length != 0 && message.length < 8000) || files_list[id].length != 0) {
				// console.log(message.length)
				output_files = [];
				console.log('message', message);

				if (files_list.length != 0) {
					for (file_index in files_list[id]) {
						file = files_list[id][file_index];
						output_files[file['file_id']] = new Object();
						output_files[file['file_id']]["name"] = file['name'];
						output_files[file['file_id']]["server_name"] = file['server_name'];
						output_files[file['file_id']]["size"] = file['size'];
						output_files[file['file_id']]["last_modified_date"] = file['lastModifiedDate'];
						output_files[file['file_id']]["owner_id"] = file['owner_id'];
					}
				}

				$('.main #chat_' + id + ' .chat').animate({
			        scrollTop: $('.main #chat_' + id + ' .chat').offset().top
			    }, 500);

			    $('#chat_' + id + ' .input textarea').val('');
			    files_list[id] = [];
			    $('#chat_' + id + ' .media_file').addClass('hidden_media_file');
				setTimeout(function () {
					$('#chat_' + id + ' .hidden_media_file').remove();
				}, 50)
				

				// console.log('output_files', output_files)
				// console.log('addFileInterval', addFileInterval)
				$('#chat_' + id + ' .input .upload-files-info').removeClass('upload-files-info-display');
				$.ajax({
					url: '<?= $link ?>/inc/messages.php',
					type: 'POST',
					cache: false,
					data: {
						type: 'send-message',
						message: message.replace(/<\/?[^>]+(>|$)/g, ""),
						media_files: JSON.stringify(output_files),
						id: id,
						// message_type: 'msg',
						secret_id: '<?= md5('user_' . $user_token . '_sendMessage')?>'
					},
					success: function (result) {
						console.log(result);
						if (result != '0 failed' && result != '1 failed' && result != '3 failed' && result != '4 failed' &&  result != 'user not found') {

							$('.chats-block .input textarea').css({"display" : "flex"})
							$('.chats-block .input .upload-files').css({"display" : "none"})
							$('.chats-block .input .add-media-file').css({"display" : "flex"})
							localStorage.removeItem('saved_chat_data_' + id);
							
							if ($('.main #user_' + id).length == 0) {
								getUsers();
							}
						}
						if (result == '0 failed') {
							changeMessagePrivacy(id, 0);
						}
						if (result == '1 failed') {
							changeMessagePrivacy(id, 1);
						}
						if (result == '3 failed') {
							changeMessagePrivacy(id, 3);
						}
						if (result == '4 failed') {
							changeMessagePrivacy(id, 4);
						}
						
					}
				})
				viewMessages(id);
			}
		}

		function saveMessage (chat_id, message) {
			files = files_list[chat_id];

			// console.log(chat_id, message)
			// if ((message.replace(' ', '').replace(/[\n\r]/g, '').replace(/<\/?[^>]+(>|$)/g, "").length != 0 && message.length < 8000) || files_list[chat_id].length != 0) {
				// console.log(message.length)
				output_files = [];

				count = 1;
				if (files_list.length != 0) {
					for (file_index in files_list[chat_id]) {
						file = files_list[chat_id][file_index];

						output_files[count] = new Object();
						output_files[count]["name"] = file['name'];
						output_files[count]["server_name"] = file['server_name'];
						output_files[count]["size"] = file['size'];
						output_files[count]["last_modified_date"] = file['lastModifiedDate'];
						output_files[count]["owner_id"] = file['owner_id'];
						output_files[count]["mime"] = file['mime'].replace('.', '');
						output_files[count]["file_type"] = file['file_type'];

						// console.log("file['mime']", file)

						if (output_files[count]["file_type"] == '') {
							if ("png bmp ecw gif ico ilbm jpeg mrsid pcx tga tiff webp xbm xps rla rpf pnm jpg jfif".indexOf(file['mime']) != -1) {
								output_files[count]['file_type'] = 'img';
							} else if ("mp4 mov wmv avi avchd flv f4v swf mkv webm html5 mpeg-2 vob ogv qt rmvb viv asf amv mpg mp2 mpeg mpe mpv".indexOf(file['mime']) != -1) {
								output_files[count]['file_type'] = 'video';
							} else {
								output_files[count]['file_type'] = 'unknown';
							}
						}

						
						count++;
					}
				}

				// console.log('output_files', output_files)

				$.ajax({
					url: '<?= $link ?>/inc/messages.php',
					type: 'POST',
					cache: false,
					data: {
						type: 'save-message',
						message: message.replace(/<\/?[^>]+(>|$)/g, ""),
						media_files: JSON.stringify(output_files),
						chat_id: chat_id,
						message_id: message_id,
						// message_type: 'msg',
						secret_id: '<?= md5('user_' . $user_token . '_saveMessage')?>'
					},
					success: function (result) {
						// console.log(result);
						if (result == 'success') {
							viewMessages(chat_id);
							closeEditingMessage(chat_id);
							getUsers();
						}
					}
				})
			// }
		}

		// Если пользователь изменил настройки приватности и ему больше нет возможности написать, то убираем поле ввода 
		function changeMessagePrivacy (id, type) {
			// console.log('cmp' + type)
			$('.main #chat_' + id + ' .input *').remove();

			if (type == 0) {
				// Никто не может присылать сообщения пользователю
				$('.main #chat_' + id + ' .input').prepend('<p class="privacy_error">Пользователь запретил присылать ему сообщения</p>')
			}
			if (type == 1) {
				// Никто не может присылать сообщения пользователю
				$('.main #chat_' + id + ' .input').prepend('<p class="privacy_error">Чтобы присылать сообщения пользователю необходимо состоять с ним в друзьях</p>')
			}
			if (type == 3) {
				// Второй пользователь заблокирован
				$('.main #chat_' + id + ' .input').prepend('<p class="privacy_error">Уберите пользователя из чёрного списка, чтобы начать переписку</p>')
			}

			if (type == 4) {
				// Основной пользователь заблокирован
				$('.main #chat_' + id + ' .input').prepend('<p class="privacy_error">Пользователь запретил Вам присылать ему сообщения</p>')
			}	
		}

		// Получение новых сообщений
		function getMessages () {
			chats_length = $('.chats-block').length - 1;
			messages = new Object();

			for (let i = 0; i < chats_length; i++) {
				chat_id = $('.chats-block:eq(' + i + ')').attr("id").replace('chat_', "");

				messages[chat_id] = new Object();

				if ($('.chats-block:eq(' + i + ') .outgoing_msg:eq(0)').length != 0) {
					messages[chat_id]['og'] = $('.chats-block:eq(' + i + ') .outgoing_msg:eq(0)').attr("id").replace('message_', "");
				} else {
					messages[chat_id]['og'] = '';
				}
				
				if ($('.chats-block:eq(' + i + ') .incoming_msg:eq(0)').length != 0) {
					messages[chat_id]['ic'] = $('.chats-block:eq(' + i + ') .incoming_msg:eq(0)').attr("id").replace('message_', "");
				} else {
					messages[chat_id]['ic'] = '';
				}

				

				// messages_count = Math.min(100, messages_count);

				messages[chat_id]['ur'] = '';

				if ($('.chats-block:eq(' + i + ') .unread').length != 0) {
					messages[chat_id]['ur'] = $('.chats-block:eq(' + i + ') .unread:eq(0)').attr('id').replace('message_', '');
				}
			}

			messages['ed'] = new Object();
			messages_count = $('.message_block[editability="true"]').length - 1;
			for (let e = 0; e < messages_count; e++) {
				// if (.attr('editability') == 'true') {
					
					messages['ed'][$('.message_block[editability="true"]:eq(' + e + ')').attr('id').replace('message_', '')] = $('.message_block[editability="true"]:eq(' + e + ')').attr('edited_version');
				// }
			}

			// console.log('send', messages);
			// console.log(JSON.stringify(messages))
			
			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				type: 'POST',
				cache: false,
				data : {
					type: 'get-messages',
					messages: JSON.stringify(messages),
					secret_id: '<?= md5('user_' . $user_token . '_getMessages')?>'
				},
				success: function (result) {
					// Список пришедших сообщений
					// console.log('get', result)
					// return;
					if (result != '' && result != 'chats not found') {
						result = JSON.parse(result);
						// console.log('get', result)

						for (var chat_id in result) {
							// console.log(result[key]);
							if (chat_id != 'ed') {
								if ($('#chat_' + chat_id).length == 0) {
									getChat(chat_id);
								} else {
									// console.log('ic', result[chat_id]['ic'])
									if (result[chat_id]['ic']) {
										playNotificationAudio();
									}

									local_messages = [];

									for (var message_id in result[chat_id]['og']) {
										local_messages[message_id] = result[chat_id]['og'][message_id];
									}

									for (var message_id in result[chat_id]['ic']) {
										local_messages[message_id] = result[chat_id]['ic'][message_id];
									}

									for (var message_id in local_messages) {
										if (local_messages[message_id] == '') {
											delete local_messages[message_id];
											local_messages.filter(function(f) { return f !== message_id })
										}
										if ($('#message_' + message_id).length == 0) {
											addMessage(chat_id, local_messages[message_id]);
										} else {
											delete local_messages[message_id];
											local_messages.filter(function(f) { return f !== message_id })
										}
									}

									// console.log(result)
									// console.log('read ' + chat_id, result[chat_id]['ur'])
									if (result[chat_id]['ur'] == 'readed') {
										// console.log('readed ' + chat_id, result[chat_id]['ur'])
										$('#chat_' + chat_id + ' .unread').removeClass('unread');
										$('#user_' + chat_id + ' .unread').removeClass('unread');
									}

									if ($('#chat_' + chat_id + ' .unread').length == 0) {
										$('#user_' + chat_id + ' p.unread').removeClass('unread');
									}

									for (var message_id in result['ed']) {
										message_data = result['ed'][message_id];

										if (message_data['editability'] == 0) {
											$('#message_' + message_id).attr('editability', 'false');
										}
										if (message_data['ed_ver'] != null) {
											$('#message_' + message_id).attr('edited_version', message_data['ed_ver']);
											$('#message_' + message_id).text('').append(message_data['html']);
											if (!$('#message_' + message_id).hasClass('message_edited')) {
												$('#message_' + message_id).addClass('message_edited')
											}
										}
									}
								}
							}

							
						}
					}
					setTimeout(() => getMessages(), 700);
				}
			})
		}

		// getMessages()
		// setInterval(() => getMessages(), 1000);

		// Отправка сообщения
		$('body').on('click', '.send_message', function () {
			id = $(this).parent().parent().parent().attr('id').replace('chat_', '');
			message = $('.main #chat_' + id + ' .input textarea').val();
			sendMessage(id, (message));
			$('#chat_' + id + ' .input textarea').focus();
		})

		// Набор сообщения
		$('body').on('keydown', '.input textarea', function (e) {
			if (e.keyCode == 13 && !e.shiftKey) {
				e.preventDefault();

				id = $(this).attr('alt');
				message = $('.main #chat_' + id + ' .input textarea').val();

				if (files_list[id] == undefined) {
					files_list[id] = [];
				}
				// Отправка сообщения
				if ($('#chat_' + id + ' .send_message').length != 0) {
					
					if (message.replace(' ', '').replace(/<\/?[^>]+(>|$)/g, "").length > 0 || files_list[id].length != 0) {
						sendMessage(id, message);
						$(this).css({'height' : '41px'})
						$('.main #chat_' + id + ' .input textarea').val('').focus();
					}
						
				} else {
					saveMessage(id, message);
					$(this).css({'height' : '41px'})
					$('.main #chat_' + id + ' .input textarea').val('');
				}

				
				
			}	
		})

		// Набор сообщения и сохранение его в локальное хранилище
		$('body').on('keyup', '.input textarea', function (e) {
			let keyCode = e.which;
			// console.log(keyCode)
			let id = $(this).attr('alt');
			message = $('.main #chat_' + id + ' .input textarea').val();

			saved_chat_data = localStorage.getItem('saved_chat_data_' + id);

			if (saved_chat_data != '' && saved_chat_data != null) {
				saved_chat_data = JSON.parse(saved_chat_data);
			} else {
				saved_chat_data = new Object();
			}

			if (message.replace(' ', '') != '' || message.length == 0) {
				saved_chat_data['message'] = message;
				localStorage.setItem('saved_chat_data_' + id, JSON.stringify(saved_chat_data));
			}	

			// Стрелка вверх (редактирование сообщения)
			if ($(this).css('height').replace('px', '') == 41) {
				if (keyCode == 38) {
					if ($('#chat_' + id + ' .outgoing_msg:eq(0)').length != 0) {
						message_id = $('#chat_' + id + ' .outgoing_msg:eq(0)').attr('id').replace('message_', '');
						editMessage(id, message_id)
					}
					
				}
			}

			if ($('#chat_' + id + ' .editing_message_info_display').length != 0) {
				// esc прекращение редактирования сообщения
				if (keyCode == 27) {
					closeEditingMessage(id)
				}
			}
			
		})

		$('body').on('click', '.message_media_file img', function () {
			message_id = $(this).parents('.message_block').attr('id');

			media_file_name = $(this).attr('title');

			number_media_files = $('#' + message_id + ' .media_file').length;
			message_id = [message_id.replace('message_', '')];

			// console.log(JSON.stringify(message_id));

			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				type: 'POST',
				data: {
					type: 'get-media-files',
					message_ids: JSON.stringify(message_id),
					secret_id: '<?= md5('user_' . $user_token . '_getMediaFiles')?>'
				},
				success: function (result) {
					if (result != 'access denied') {
						result = JSON.parse(result);
						// console.log(result[message_id])
						openImageViewer(result[message_id], media_file_name)
					}
				}
			})

		})

		function editMessage (chat_id, message_id) {
			// let chat_id = chat_id;

			$('#chat_' + chat_id + ' .uploaded-files .media_file').addClass('hidden_media_file');
			setTimeout(function () {
				$('#chat_' + chat_id + ' .upload-files-info').removeClass('upload-files-info-display');
				$('#chat_' + chat_id + ' .media_file').remove();
				// $('#chat_' + chat_id + ' .uploaded-files').text('');
				// console.log('deleting .media_file');
			}, 200)

 
			local_obj = new Object();
			local_obj["message"] = $('#chat_' + chat_id + ' .input textarea').val();

			local_obj["media_files"] = [];

			for (file_index in files_list[chat_id]) {
				file = files_list[chat_id][file_index];
				// console.log('file1', file);
				// local_array = new Object();
				if (file != null && file != undefined) {
					local_obj["media_files"][file['file_id']] = new Object();

					local_obj["media_files"][file['file_id']]['server_name'] = file['server_name'];
					local_obj["media_files"][file['file_id']]['file_id'] = file['file_id'];
					local_obj["media_files"][file['file_id']]['name'] = file['name'];
					local_obj["media_files"][file['file_id']]['size'] = file['size'];
					local_obj["media_files"][file['file_id']]['file_type'] = file['file_type'];
				}
			}

			delete(files_list[chat_id]);

			localStorage.setItem('temporary_storage_' + chat_id, JSON.stringify(local_obj));
			// $('#chat_' + chat_id + ' .input textarea').val('');
			// $('#chat_' + chat_id + ' .input textarea').focus();

			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				type: 'POST',
				data: {
					type: 'get-message-data',
					message_id: message_id,
					secret_id: '<?= md5('user_' . $user_token . '_getMessageData')?>'
				},
				success: function (result) {
					// console.log(result)
					if (result != 'access denied') {
						result = JSON.parse(result);
						// console.log(JSON.parse(result['media']));

						$('#chat_' + chat_id + '  .editing_message_info div:eq(0)').text('').append('Редактирование <b class="message_anchor" message_id="' + message_id + '">сообщения</b>');
						$('#chat_' + chat_id + '  .editing_message_info').addClass('editing_message_info_display')

						$('#chat_' + chat_id + '  .send_message').removeClass('send_message').addClass('save_message').find('img').css({'transform' : 'scale(0)'})

						setTimeout(function () {
							$('#chat_' + chat_id + '  .rows button img').attr('src', link + '/assets/img/icons/check.svg').css({'transform' : 'scale(.9)'})
						}, 100);

						var text = result['text'];   
						var lines = text.split(/\r|\r\n|\n/);
						var count = lines.length;

						$('.count_textarea_lines').text('')
						$('.count_textarea_lines_br').remove();
						for (var i = 0; i < count; i++) {
							if (lines[i] != '') {
								$('.count_textarea_lines').append('<p>' + lines[i] + '</p>');
							} else {
								$('.count_textarea_lines').append('<p class="count_textarea_lines_br"></p>')
							}
						}

						height = $('.count_textarea_lines').css('height');

						$('#chat_' + chat_id + ' .input textarea').css({'height' : height});

						$('#chat_' + chat_id + ' .input textarea').val(result['text']);
						$('#chat_' + chat_id + ' .input textarea').focus();


						if (result['media'] != '') {
							files = JSON.parse(result['media']);
							files_list[chat_id] = [];

							setTimeout(function () {

								for (file_index in files) {
									file = files[file_index];

									fileId++;
									files_list[chat_id][fileId] = file;
									files_list[chat_id][fileId]['file_id'] = fileId;

									if (file['file_type'] == 'img') {
										$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><img src='" + link + '/uploads/user_files/' + file['server_name'] + "'><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + fileId + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
									} else {
										$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><div class='file_background'>" + file['mime'] + "</div><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + fileId + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
									}
								}

								if ($('#chat_' + chat_id + ' .input .upload-files-info').length == 0 && files_list[chat_id].length > 0) {
									$('#chat_' + chat_id + ' .input').append('<div class="upload-files-info">Файлов: <b>1</b> <button class="button-3 delete_all_media_files">Удалить все</button></div>')
								}

								setTimeout(function () {
									if (files_list[chat_id].length > 0) {
										$('#chat_' + chat_id + ' .upload-files-info').addClass('upload-files-info-display');
									}
									$('#chat_' + chat_id + ' .media_file').removeClass('hidden_media_file')
									$('#chat_' + chat_id + ' .input .upload-files-info b').text($('.uploaded-files .media_file').length);
								}, 50)
							}, 210)
						} else {

						}
					}
				}
			})
		}
		// var editing_message_id;
		$('body').on('click', '.message_block .edit_message', function () {
			// editing_message_id = $(this).parents('.message_block').attr('id').replace('message_', '');
			message_id = $(this).parents('.message_block').attr('id').replace('message_', '');
			var chat_id = $(this).parent().parent().parent().attr('id').replace('chat_', '');

			editMessage(chat_id, message_id);
		})

		function closeEditingMessage (chat_id) {
			var chat_id = chat_id;
			temporary_storage = JSON.parse(localStorage.getItem('temporary_storage_' + chat_id));
			localStorage.setItem('saved_chat_data_' + chat_id, localStorage.getItem('temporary_storage_' + chat_id));

			var text = temporary_storage['message'];   
			var lines = text.split(/\r|\r\n|\n/);
			var count = lines.length;

			$('.count_textarea_lines').text('')
			$('.count_textarea_lines_br').remove();
			for (var i = 0; i < count; i++) {
				if (lines[i] != '') {
					$('.count_textarea_lines').append('<p>' + lines[i] + '</p>');
				} else {
					$('.count_textarea_lines').append('<p class="count_textarea_lines_br"></p>')
				}
			}

			height = $('.count_textarea_lines').css('height');

			$('#chat_' + chat_id + ' .input textarea').css({'height' : height});

			$('#chat_' + chat_id + ' .input textarea').val(temporary_storage['message']);

			localStorage.setItem('saved_chat_data_' + chat_id, JSON.stringify(temporary_storage))

			$('#chat_' + chat_id + '  .save_message').removeClass('save_message').addClass('send_message').find('img').css({'transform' : 'scale(0)'})

			setTimeout(function () {
				$('#chat_' + chat_id + '  .send_message img').attr('src', link + '/assets/img/icons/brand-telegram.svg').css({'transform' : 'scale(.9)'})
			}, 100);

			$('#chat_' + chat_id + ' .media_file').addClass('hidden_media_file');
			$('#chat_' + chat_id + ' .upload-files-info').removeClass('upload-files-info-display');
			// console.log(1)
			setTimeout(function () {
				$('#chat_' + chat_id + ' .media_file').remove();
				$('#chat_' + chat_id + ' .upload-files-info').remove();
				// console.log(2)
			}, 200)
			// console.log(3)

			$('#chat_' + chat_id + '  .editing_message_info').removeClass('editing_message_info_display');

			files = temporary_storage['media_files'];

			files_list[chat_id] = files;
			
			setTimeout(function () {
				for (file_index in files) {
					file = files[file_index];

					if (file != null) {
						if (file['file_type'] == 'img') {
							$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><img src='" + link + '/uploads/user_files/' + file['server_name'] + "'><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + file['file_id'] + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
						} else {
							$('#chat_' + chat_id + ' .uploaded-files').append("<div title='" + file['name'] + "' class='media_file hidden_media_file'><div class='file_background'>" + file['mime'] + "</div><div class='file_name'>" + file['name'] + "</div><img class='delete_media_file' alt='file_" + file['file_id'] + "' src='<?= $link ?>/assets/img/icons/x.svg'></div>");
						}
					} else {
						delete(files_list[chat_id][file_index])
					}
				}

				if ($('#chat_' + chat_id + ' .input .upload-files-info').length == 0 && $('#chat_' + chat_id + ' .media_file').length > 0) {
					$('#chat_' + chat_id + ' .input').append('<div class="upload-files-info">Файлов: <b>1</b> <button class="button-3 delete_all_media_files">Удалить все</button></div>')
				}

				setTimeout(function () {
					$('.hidden_media_file').removeClass('hidden_media_file')
					$('#chat_' + chat_id + ' .input .upload-files-info b').text($('.uploaded-files .media_file').length);
					if ($('#chat_' + chat_id + ' .media_file').length > 0) {
							$('#chat_' + chat_id + ' .upload-files-info').addClass('upload-files-info-display');
					}
				}, 50)
			}, 210)
			$('#chat_' + chat_id + ' .input textarea').focus();
			localStorage.removeItem('temporary_storage_' + chat_id)
		}

		$('body').on('click', '.editing_message_info_display div:eq(1)', function () {
			chat_id = $(this).parents('.chats-block').attr('id').replace('chat_', '')
			closeEditingMessage(chat_id);
		})

		$('body').on('click', '.save_message', function () {
			chat_id = $(this).parents('.chats-block').attr('id').replace('chat_', '')
			message = $('#chat_' + chat_id + ' .input textarea').val();
			saveMessage(chat_id, message);
		})

		var files_sending = [];
		var characters_entered = [];
		var local_characters_entered = [];

		var user_activity = new Object();
		// user_activity['chatting'] = 0;
		// user_activity['sending_files'] = 0;

		function getUserActivity () {
			for (var i = 0; i < $('.chats-block').length - 1; i++) {
				chat_id = $('.chats-block:eq(' + i + ')').attr('id').replace('chat_', '');
				if (user_activity[chat_id] == undefined) {
						user_activity[chat_id] = new Object();
					}

				if (characters_entered[chat_id] != local_characters_entered[chat_id]) {
					local_characters_entered[chat_id] = characters_entered[chat_id];
					
					user_activity[chat_id]['chatting'] = 1;
				} else {
					user_activity[chat_id]['chatting'] = 0;
				}

				if (files_sending[chat_id] == 1) {
					user_activity[chat_id]['sending_files'] = 1;
				} else {
					user_activity[chat_id]['sending_files'] = 0;
				}

				if (user_activity[chat_id]['sending_files'] == 0 && user_activity[chat_id]['chatting'] == 0) {
					delete(user_activity[chat_id]);
				} else {
					if (user_activity[chat_id]['sending_files'] == 0) {
						delete(user_activity[chat_id]['sending_files']);
					}
					if (user_activity[chat_id]['chatting'] == 0) {
						delete(user_activity[chat_id]['chatting']);
					}
				}	
			}

			$.ajax({
				url: '<?= $link ?>/inc/messages.php',
				type: 'POST',
				data: {
					user_activity: JSON.stringify(user_activity),
					type: 'user-activity',
					secret_id: '<?= md5('user_' . $user_token . '_userActivity')?>'
				},
				success: function (result) {
					// console.log(result)
					if (result != '') {
						result = JSON.parse(result);

						for (chat_id in result) {
							// console.log('chat_id', chat_id);
							if (result[chat_id]['sending_files'] == 1) {
								$('#chat_' + chat_id + ' .status_sending_files').css({'display' : 'flex'});
								$('#chat_' + chat_id + ' .online_time').css({'display' : 'none'});
							} else if (result[chat_id]['chatting'] == 1) {
								$('#chat_' + chat_id + ' .status_chatting').css({'display' : 'flex'});
								$('#chat_' + chat_id + ' .online_time').css({'display' : 'none'});
							} else {
								$('#chat_' + chat_id + ' .status_sending_files').css({'display' : 'none'});
								$('#chat_' + chat_id + ' .status_chatting').css({'display' : 'none'});
								$('#chat_' + chat_id + ' .online_time').css({'display' : 'unset'});
							}
						}
					} else {
						$('.chats-block .status_sending_files').css({'display' : 'none'});
						$('.chats-block .status_chatting').css({'display' : 'none'});
						$('.chats-block .online_time').css({'display' : 'flex'});
					}
					setTimeout(() => getUserActivity(), 700);
				}
			})
		}

		getUserActivity();



		



		<?
			// Запланировано открытие несуществующего чата
			if ($get_local_user_data -> num_rows != 0 and $thisChat -> num_rows == 0 and $get_id != $user_id and !in_array($get_id, $user_friends)) {
				echo 'getChat(' . $get_id . ', ' . $get_id . ')';
			}
		?>
	</script>
	
	<?
		include_once '../inc/footer.php';
	?>
</body>
</html>