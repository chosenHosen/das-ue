<?php
session_start();
if(!isset($_POST["username"]) || !isset($_POST["password"]))
{
    abort();
}

require_once "db_connect.php";

$query = $conn->prepare("SELECT password FROM users WHERE username=?");
$query->bind_param("s",$_POST["username"]);
$query->execute();
$query->bind_result($password);
if($query->fetch())
{
    if(password_verify($_POST["password"], $password))
    {
        //uncomment this to get the value with which you can authenticate a user via http digest
        //file_put_contents("test.txt", md5($_POST["username"].":Top Secret Area:".$_POST["password"]));


        //valid login
        $_SESSION["auth"] = sha1(openssl_random_pseudo_bytes(20));
        if(isset($_POST["remember"]) && $_POST["remember"] === "on")
        {
            setcookie("auth", $_SESSION["auth"], (int)(time() + 60*60*24*365), "/", "", true);
        }
        $_SESSION["logged_in"] = 1;
        header('Location: index.php');
        die();
    }
}

//unvalid login
abort();

function abort()
{
    $_SESSION["logged_in"] = 0;
    header('Location: index.php');
    die();
}


