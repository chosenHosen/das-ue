<?php
session_start();

require_once 'google-api-php-client-2.2.1/vendor/autoload.php';

$client = new Google_Client(['client_id' => '280359176332-30opuubdvu7gb7pjhq2nulkns0h5d5hd.apps.googleusercontent.com']);

$payload = $client->verifyIdToken($_POST["idtoken"]);
if ($payload)
{
  	//valid login
 	$userid = $payload['sub'];


    
    $_SESSION["user"] = $userid;
    $_SESSION["auth"] = sha1(openssl_random_pseudo_bytes(20));
    $_SESSION["logged_in"] = 1;

    if(isset($_POST["remember"]) && $_POST["remember"] === "on")
	{
		//set cookies
        $time = (int)(time() + 60*60*24*365);
        setcookie("auth", $_SESSION["auth"], $time, "/", $_SERVER["SERVER_NAME"], true);
        setcookie("user", $userid, $time, "/", $_SERVER["SERVER_NAME"], true);

    	//check for entry
		include "db_connect.php";
		$query = $conn->prepare("SELECT * FROM users WHERE username=?");
		$query->bind_param("s", $userid); 
		$query->execute();
		if($query->fetch()) //update entry
		{

	        include "db_connect.php";
	        $query = $conn->prepare("UPDATE users SET cookie_auth=? WHERE username=?");
	        $query->bind_param("ss",$_SESSION["auth"], $userid);
	        $query->execute();
		}
		else //new entry
		{
			$password = randomPassword();
			$password = password_hash($password, PASSWORD_DEFAULT);
		    include "db_connect.php";
            $query = $conn->prepare("INSERT INTO users (`username`, `password`, `http_digest_a1`, `cookie_auth`) VALUES (?,?,?,?)");
            $query->bind_param("ssss", $userid, $password, $password, $_SESSION["auth"]);
            $query->execute();
		}
    }
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

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

?>