<?php
session_start();

require_once 'google-api-php-client-2.2.1/vendor/autoload.php';

$client = new Google_Client(['client_id' => '280359176332-30opuubdvu7gb7pjhq2nulkns0h5d5hd.apps.googleusercontent.com']);

$payload = $client->verifyIdToken($_POST["idtoken"]);
if ($payload)
{
 // $userid = $payload['sub'];
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
else
{	
	abort();
}

function abort()
{
    $_SESSION["logged_in"] = 1;
    header('Location: index.php');
    die();
}

?>