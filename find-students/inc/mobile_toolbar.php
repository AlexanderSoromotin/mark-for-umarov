<?
	include_once "config.php";
	include_once "userData.php";

	if (!isset($last_update_ver)) {
		$last_update_ver = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `updates` ORDER BY `id` DESC LIMIT 0, 1"))['id'];
		if ($_COOKIE['last_update_ver'] < $last_update_ver) {
			$menu_notification = true;
		}
	}

	$requests = mysqli_query($connection, "SELECT `id` FROM `group_membership_requests` WHERE `group_id` = '$user_group_id' LIMIT 0, 1");

	// $date = date('d.m.Y');
	// $archive = mysqli_query($connection, "SELECT `id` FROM `visits_archive` WHERE `group_id` = '$user_group_id' and `date` = '$date'");

	$head_student_notification = '';
	if ($requests -> num_rows != 0) {
		$head_student_notification = 'notification';
	}

	// $presence = '';
	// if ($archive -> num_rows != 0) {
	// 	$archive = mysqli_fetch_assoc($archive);
	// 	$students = json_decode($archive['students'], 1);
	// 	if (gettype($students[$user_id]) != 'array') {
	// 		// $presence = '1';
	// 	}
	// }
?>
<div class="mobile_footer">
	<ul>
		<li class="mobile_footer_tab_settings <? if ($menu_notification) {echo "notification";} ?>">
			<a href="<?= $link ?>/settings">
				<img src="<?= $link ?>/assets/img/icons/menu-2.svg">
				<p>Меню</p>
			</a>
		</li>

		<li class="mobile_footer_tab_presence <?= $presence ?>">
			<a href="<?= $link ?>/presence-history">
				<img src="<?= $link ?>/assets/img/icons/circle-plus.svg">
				<p>Отметка</p>
			</a>
		</li>

		<? if ($user_is_head_student or $user_status == 'Admin'): ?>
		<li class="mobile_footer_tab_head_student <?= $head_student_notification ?>">
			<a href="<?= $link ?>/head-student">
				<img src="<?= $link ?>/assets/img/icons/crown.svg">
				<p>Староста</p>
			</a>
		</li>
		<? endif; ?>

		<li class="mobile_footer_tab_group">
			<a href="<?= $link ?>/group">
				<img src="<?= $link ?>/assets/img/icons/users.svg">
				<p>Группа</p>
			</a>
		</li>

		<li class="mobile_footer_tab_profile">
			<a href="<?= $link ?>/profile">
				<img src="<?= $link ?>/assets/img/icons/user-circle.svg">
				<p>Профиль</p>
			</a>
		</li>
	</ul>
</div>

<script type="text/javascript">
	function select_mobile_footer_tab (name) {
		$('.mobile_footer_tab_' + name).addClass('selected');
	}
	$('.reload_page').click(function () {
		$('header .reload_page img').css({'transform': 'rotate(360deg)'})
		location.reload();
	})
</script>