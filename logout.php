<?php
session_start();
if(isset($_POST["csrf_token"]) && $_POST["csrf_token"] == $_SESSION["token"])
{
    $_SESSION["logged_in"] = 0;
    setcookie("auth", null, -1, "/");
    setcookie("user", null, -1, "/");
    unset($_SESSION);
    session_destroy();
}
header("Location: index.php");