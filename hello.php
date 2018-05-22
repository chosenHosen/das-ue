<?php
include "security.php";
include "func_bcrypt.php";
$_SESSION["token"] = bcrypt_encode(uniqid());
?>

<h1>Your logged in!</h1>
<p>Nothing more to see here...</p>
<form method="post" action="logout.php" onsubmit="signOut();">
    <!-- Send token with the rest of the form data so we can validate it really is sent from our client -->
    <input type="hidden" name="csrf_token"value="<?=$_SESSION["token"]?>">
    <input type="submit" value="Logout">
</form>
