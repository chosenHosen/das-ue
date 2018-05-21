<script>
function onSignIn(googleUser) {
    console.log("Sucess!");
  var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'https://localhost/das-ue/verifyGoogle.php');
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
</script>

<script src="https://apis.google.com/js/platform.js" async defer></script>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" action="verify.php" method="post" role="form" style="display: block;">
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group text-center">
                                    <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                    <label for="remember"> Remember Me</label>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row" style="text-align: center">
                                        <div class="g-signin2" data-onsuccess="onSignIn" style="display: inline-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <a href="http_digest.php?redirect"><button style="margin: 0 auto; display: block;">Login via HTTP Digest</button></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>