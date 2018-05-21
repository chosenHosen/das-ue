<?php include "security.php" ?>

<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut());
  }
</script>

<h1>Your logged in!</h1>
<p>Nothing more to see here...</p>
<a href="logout.php" onlick="signOut();"><button>Log Out</button></a>
