<?php
session_start();
$_SESSION["logged_in"] = 0;
setcookie("auth", null, -1, "/");
unset($_SESSION);
session_destroy();
header("Location: index.php");