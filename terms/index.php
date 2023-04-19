<?
	include_once '../inc/info.php';
	include_once '../inc/db.php';
	include_once '../inc/userData.php';

	include_once '../inc/redirect.php';
	redirect('pre-deleted', '/pre-deleted');

	include_once '../inc/head.php';	

?>

<html>
<head>
	<meta charset="utf-8">
	<title>FINDCREEK :: Правила</title>
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
			<a href="<?= $link ?>/">Главная</a>
			<img draggable="false" src="<?= $link ?>/assets/img/icons/chevron-right.svg">
			<a href="<?= $link ?>/rules">Правила</a>
		</div>
	</div>

	<div class="main">
		<div class="menu">
			<ul>
				<li id="menu-title-1" class="selected">Правила пользования сайтом</li>
				<li id="menu-title-2">Пользовательское соглашение</li>
				<li id="menu-title-3">Защита информации</li>
				<li id="menu-title-4">Лицензионное соглашение</li>
			</ul>
		</div>
		
		<div class="list">
			<div class="menu-section-1 menu-section">
				<h2>Правила пользования сайтом</h2>
				<ol>
					<li>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididuntffd ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor ind reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
					</li>
					<li>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
					</li>
					<li>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
					</li>
					<li>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
						</p>
					</li>
				</ol>

			</div>

			<div class="menu-section-2 menu-section">
				<h2>Пользовательское соглашение</h2>
				<ol>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
				</ol>
				
			</div>

			<div class="menu-section-3 menu-section">
				<h2>Защита информации</h2>
				<ol>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
				</ol>
				
			</div>

			<div class="menu-section-4 menu-section">
				<h2>Лицензионное соглашение</h2>
				<ol>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
					<li>Пункт</li>
				</ol>
				
			</div>
		</div>
	</div>


		

	<script type="text/javascript">
		selectTab('terms');
		function openMenuSection (selector) {
			$('.main .menu-section').css({"display" : "none"});
			$('.main .menu-section-' + selector.replace("menu-title-", '')).css({"display" : "inline"});
		}
		openMenuSection('menu-title-1');

		$('.main .menu ul li').click(function () {
			if ($(this).hasClass('selected')) {
				return;
			}

			$('.main .menu ul li').removeClass('selected');
			$(this).addClass('selected');

			openMenuSection($(this).attr("id"));
			
		})
	</script>
	<script type="text/javascript" src="<?=$link?>/assets/editPanel.js"></script>
	<?
		include_once '../inc/footer.php';
	?>
</body>
</html>