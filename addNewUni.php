<?
	include_once 'inc/info.php';
	include_once 'inc/db.php';
	include_once 'inc/head.php';	
	include_once 'inc/userData.php';
	include_once 'inc/userData.php';

	if ($user_status != 'Admin') {
		header("Location: /");
	}
	if ($_GET['title'] != '' and $_GET['shortTitle'] != '') {
		$title = $_GET['title'];
		$shortTitle = $_GET['shortTitle'];

		$result = mysqli_query($connection, "SELECT * FROM `education` WHERE `title` = '$title' ");
		if ($result -> num_rows == 0) {
			mysqli_query($connection, "INSERT INTO `education` (`title`, `short_title`) VALUES ('$title', '$shortTitle') ");
		}
		header("Location: ../addNewUni.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<a class="back" href="../">Вернуться на главную страницу</a>
	<div class="main">
		<h1>Добавление нового учебного заведения</h1>
		<textarea class="title" placeholder="Полное название Учебного Заведения"></textarea>
		<input class="shortTitle" placeholder="Краткое название Учебного Заведения">
		<button class="button-3">Добавить</button>
	</div>

	<div class="unis">
		<center><h2>Добавленные</h2></center>
		<div class="list">
			<?
				$unis = mysqli_query($connection, "SELECT * FROM `education` ORDER BY `id` DESC");

				while ($u = mysqli_fetch_assoc($unis)) {
					echo "
					<div class='block'>
						<p>ID: " . $u['id'] . "</p>
						<p>" . $u['display_index'] . "</p>
						<p>" . $u['title'] . "</p>
						<p>" . $u['short_title'] . "</p>
					</div>";
				}
			?>
		</div>
		
	</div>
</body>
<style type="text/css">

body {
	position: absolute;
	display: flex;
	/*justify-content: center;*/
	align-items: center;
	flex-direction: column;
	padding-top: 100px;
	height: 100vh;
	width: 100vw;
}
a {
	position: absolute;
	top: 20px;
	left: 20px;
}
.main {
	display: flex;
	flex-direction: column;
	align-items: center;
}
input, textarea {
	padding: 7px;
	border-radius: 10px;
	border: 1px solid rgba(0, 0, 0, .2);
	width: 500px;
	resize: none;
	margin-bottom: 10px;
}
textarea {
	height: 70px;
}
button {
	width: 500px;
	margin-top: 20px;
}
h1 {
	margin-bottom: 20px;
}
.unis {
	margin-top: 50px;
	display: flex;
	flex-direction: column;
}
.unis .list {
	margin-top: 20px;
	display: flex;
	flex-direction: column;
}
.unis .list div {
	/*padding: 5px;*/
	border-radius: 10px;
	background-color: #fff;
	border: 1px solid rgba( 0, 0, 0, .2);
	display: flex;
	flex-direction: row;
	margin-bottom: 7px;
	overflow: hidden;
}
.unis .list div p {
	/*border: 1px solid;*/
	display: flex;
	align-items: center;
}
.unis .list div p:nth-child(1) {
	padding: 10px;
	width: 80px;
	background-color: rgba(0, 0, 0, .1);
}
.unis .list div p:nth-child(2) {
	padding: 10px;
	justify-content: center;
	width: 50px;
}
.unis .list div p:nth-child(3) {
	padding: 10px;
	height: auto;
	width: 500px;
}
.unis .list div p:nth-child(4) {
	padding: 10px;
	height: auto;
	width: 200px;
}
</style>

<script type="text/javascript">
	$('textarea').focus();
	
	$(document).keyup(function (e) {
		// console.log(e.which)

		if (e.which == 40 || e.which == 38) {
			if ($('textarea:focus').length !== 0) {
				// console.log('textarea is focusing')
				$('input').focus();
			} 
			else if ($('textarea:focus').length == 0 && $('input:focus').length == 0) {
				$('textarea').focus();
			} 
			else {
				// console.log('textarea is not focusing')
				$('textarea').focus();
			}
		}
		if (e.which == 13) {
			if ($('textarea').val().replace(' ', '').length == 0 || $('input').val().replace(' ', '').length == 0) {
				return;
			} else {
				location.href ='<?$link?>/addNewUni.php?title=' + $('textarea').val().replace(' ', '%20') + '&shortTitle=' + $('input').val().replace(' ', '%20');
			}
		}
	})

	$('button').click(function () {
		if ($('textarea').val().replace(' ', '').length == 0 || $('input').val().replace(' ', '').length == 0) {
			return;
		} else {
			location.href ='<?$link?>/addNewUni.php?title=' + $('textarea').val().replace(' ', '%20') + '&shortTitle=' + $('input').val().replace(' ', '%20');
		}
	})

</script>


<? include_once 'inc/footer.php'; ?>
</html>