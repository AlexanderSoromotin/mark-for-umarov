<?php

setcookie('findstudents_token', '', time() * 0, "/");
setcookie("findstudents_email", '', time() * 0, "/");
header("Location: " . $link . '/authorization');