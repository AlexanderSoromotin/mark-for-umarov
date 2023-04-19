<?php

include "db.php";
include "info.php";

if (isset($_POST)) {
	echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
} else if (isset($_GET)) {
	echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
}



























