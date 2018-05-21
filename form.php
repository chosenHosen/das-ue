<?php
//generate random token to prevent csrf
$_SESSION["token"] = bcrypt_encode(uniqid());
?>
<form method="post" action="process_logout.php">
    <!-- Send token with the rest of the form data so we can validate it really is sent from our client -->
    <input type="hidden" name="csrf_token" value="<?=$_SESSION["token"]?>">
    <input type="text" name="">
</form>