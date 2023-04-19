<?php

setcookie('token', '', time() * 0, "/");
setcookie("email", '', time() * 0, "/");
// header("Location: " . $link . '/authorization');
header("Location: " . $link . '/');