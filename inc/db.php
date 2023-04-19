<?php

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_url = "127.0.0.1";
$db_username = "root";
$db_password = "";
// $db_password = "root";
$db_name = "forum";

// $db_url = "localhost";
// $db_username = "findcreek";
// $db_password = "ilovefindcreek";
// $db_name = "findcreek";

// $db_url = "a372316.mysql.mchost.ru";
// $db_username = "a372316_forum";
// $db_password = "root";
// $db_password = "ilovefindcreek";
// $db_name = "a372316_forum";

// $db_url = "remotemysql.com";
// $db_username = "im8gI6Z3jx";
// $db_password = "hJ7AN7UpVY";
// $db_name = "im8gI6Z3jx";

// Соединение с БД
$connection = mysqli_connect($db_url, $db_username, $db_password, $db_name);
$connection -> set_charset("utf8mb4");

?>