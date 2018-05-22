<?php
session_start();
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}


if(!isset($_SESSION["logged_in"])) $_SESSION["logged_in"] = 0;

if($_SESSION["logged_in"] != 1 && isset($_COOKIE["auth"]) && isset($_COOKIE["user"]))
{
    require "db_connect.php";
    $query = $conn->prepare("SELECT cookie_auth FROM users WHERE username=?");
    $query->bind_param("s", $_COOKIE["user"]);
    $query->execute();
    $query->bind_result($auth);
    if($query->fetch() && $_COOKIE["auth"] == $auth)
    {
        $_SESSION["user"] = $_COOKIE["user"];
        $_SESSION["logged_in"] = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DAS UE</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="google-signin-client_id" content="280359176332-30opuubdvu7gb7pjhq2nulkns0h5d5hd.apps.googleusercontent.com">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://apis.google.com/js/platform.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
      gapi.load('auth2', function() {
        gapi.auth2.init();
      });

    function onSignIn(googleUser) {
        console.log("Success!");
      var id_token = googleUser.getAuthResponse().id_token;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'https://localhost/Dasue2_git/das-ue/verifyGoogle.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            location.reload();
        };

        var remember = "off";
        if(document.getElementById("remember").checked == true)
        {
            remember = "on";
        }
        xhr.send('idtoken=' + id_token+
               '&remember=' + remember);
    }

      function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
        });
      }
    </script>
</head>
<body>
<div class="container">

    <?php
    switch ($_SESSION["logged_in"])
    {
        case 1:
            include "hello.php";
            break;
        default:
            include "login.php";
            break;
    }
    ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
