<?
	if (isset($_COOKIE['email']) and $user_status != 'Banned' and $user_status != 'pre-deleted') :
?>

<script type="text/javascript">
	function confirmOnline () {
		x = new Date();

		$.ajax({
		url: '<?= $info ?>/inc/online.php',
		type: "POST",
		cache: false,
		data: {
			user_timezone: x.getTimezoneOffset() * -1,
			hash_token: '<?= md5($_COOKIE['token'])?>',
			type: 'set-last-online'
		},
		success: function (result) {
			if (result == 'reload!') {
				location.reload();
			}
			if (result == 'technical_break') {
				location.reload();
			}
			// console.log('Online confirmed.')
			// console.log("success: " + result);
			// console.log(x.getTimezoneOffset() * -1)
		},
		error: function (result) {
			// console.log("error: " + result);
		}
	})
	}

// setTimeout(function () {
// 	confirmOnline()
// }, 1000)
confirmOnline()
setInterval(() => confirmOnline(), 10000);
</script>

<? endif; ?>