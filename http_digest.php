<?php
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if(!isset($_SESSION["digest_tries"]))
{
    $_SESSION["digest_tries"] = 0;
}
$_SESSION["digest_tries"]++;

require_once "func_bcrypt.php";

$nonce = bcrypt_encode(uniqid());
$opaque = bcrypt_encode(uniqid());
$realm = "Top Secret Area";

if($_SESSION["digest_tries"] % 2 == 1)
{
    $_SESSION["digest_nonce"] = $nonce;
    $_SERVER['PHP_AUTH_DIGEST'] = array();
}


if (empty($_SERVER['PHP_AUTH_DIGEST']))
{
    header("HTTP/1.1 401 Unauthorized");
    header(sprintf('WWW-Authenticate: Digest realm="%s", nonce="%s", opaque="%s"',$realm, $nonce, $opaque));

    echo "<h1>401 Unauthorized</h1><a href='index.php'>Go to login page</a>";
    exit;
}

$data = http_digest_parse($_SERVER["PHP_AUTH_DIGEST"]);

if($_SESSION["digest_nonce"] != $data["nonce"])
{
    //this should never happen, as the client should always return the nonce unchanged
    echo "invalid nonce returned";
}

//check if user exists && password matches
require "db_connect.php";
$query = $conn->prepare("SELECT http_digest_a1 FROM users WHERE username=?");
$query->bind_param("s",$data["username"]);
$query->execute();
$query->bind_result($valid_a1);
if($query->fetch())
{
    //user exists
    //now check pw
    $a1 = $valid_a1;
    $a2 = md5($_SERVER["REQUEST_METHOD"].":".$data["uri"]);
    $response = md5("$a1:{$data["nonce"]}:$a2");
    if($response == $data["response"])
    {
        //valid password, log user in
        $_SESSION["logged_in"] = 1;
    }
}

if(isset($_GET["redirect"]))
{
    //this switch should be extended as more pages are added
    switch ($_GET["redirect"])
    {
        case "index":
        default:
            $header = "index";
            break;
    }
    if($_SESSION["logged_in"] != 1)
    {
        $header = "index";
    }
    header("Location: $header.php");
}



function http_digest_parse($txt) {
    // gegen fehlende Daten schützen
    $needed_parts = array('nonce'=>1, 'username'=>1, 'uri'=>1, 'response'=>1, 'realm'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@',
        $txt, $hits, PREG_SET_ORDER);

    foreach ($hits as $h) {
        $data[$h[1]] = $h[3] ? $h[3] : $h[4];
        unset($needed_parts[$h[1]]);
    }

    return $needed_parts ? false : $data;
}