<?php
include "security.php";
require_once "func_bcrypt.php";
$_SESSION["token"] = bcrypt_encode(uniqid());
?>
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut();
  }
  function onLoad() {
      gapi.load('auth2', function() {
          gapi.auth2.init();
      });
  }
</script>

<h1>Your logged in!</h1>
<p>Nothing more to see here...</p>
<form method="post" action="logout.php">
    <!-- Send token with the rest of the form data so we can validate it really is sent from our client -->
    <input type="hidden" name="csrf_token" value="<?=$_SESSION["token"]?>">
    <a href="#" onclick="signOut()"><input type="submit" value="Logout"></a>
</form>
