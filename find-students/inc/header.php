<?
	$site_settings = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `site_settings`"));
?>
<header>
	<div class="header">
		<h1>
			<a href="<?= $link ?>">
				<? 
					if ($site_settings['technical_break'] == 1) {
						echo '<b style="color: red;">!!!</b>';
					} 
				?>
				
			</a>
			<div class="header_title">
				
			</div>
		</h1>

		<div class="tabs">
		<ul>
			<a href="<?= $link ?>/group">
				<li>Группа</li>
			</a>
			<a href="<?= $link ?>/head-student">
				<li>Староста</li>
			</a>
			<a href="<?= $link ?>/presence-history">
				<li>Отметка</li>
			</a>
			<a href="<?= $link ?>/profile">
				<li>Профиль</li>
			</a>
			<a href="<?= $link ?>/settings">
				<li>Настройки</li>
			</a>
		</ul>


	</div>
	<div class="avatar">
		<img draggable="false" style="<?= $user_photo_style['ox_oy']?>transform: scale(<?= $user_photo_style['scale'] ?>);" src="<?= $user_photo ?>">
	</div>

	</div>

	

	<div class="reload_page">
		<img src="<?= $link ?>/assets/img/icons/refresh.svg">
	</div>
</header>

<script type="text/javascript">
	function setHeaderTitle (text) {
		$('header .header_title').text(text)
	}
	// x = new Date();
	// $(function() {
	// 	$.session.set("user_timezone", x.getTimezoneOffset() * -1);
	// });
</script>