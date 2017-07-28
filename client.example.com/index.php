<html>
<head>
<link rel="stylesheet" href="./css/bootstrap.min.css" />
<script src="./js/jquery.js"></script>
<script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</head>

<body>
<div class="container body-content">
<script type="text/javascript">
    
    jQuery(function () {
        jQuery("#notify").hide();
        function notify(message,type="info"){
            jQuery("#notify").show();
            var alertType = "alert-"+type;
            jQuery("#notify-message").removeClass();
            jQuery("#notify-message").addClass("alert");
            jQuery("#notify-message").addClass(alertType);
            jQuery("#notify-message").html(message);
        }
        jQuery('#btnRegister').click(function (event) {
            //event.preventDefault();
            
            var redirectUrl = jQuery("#authRedirectUrl");
            
            if (redirectUrl.val() === "") {
                notify("Redirect URL is must","danger");
                return;
            }

            jQuery.post("/Register.php",
                { redirectUrl: redirectUrl.val()},
                function (data) {
                    jQuery("#email").prop("disabled", false);
                    jQuery("#postLogoutRedirectUrl").prop("disabled", false);
                    jQuery("#btnUpdate").prop("disabled", false);
                    var dataJson = JSON.parse(data);
                    
                    // To register a new client, you must first edit oxdId.json
                    // and change oxd_id to an empty string, then save and quit.
                    if (dataJson.status === "done")
                    {
                        notify("You have already registered.", "info");
                        redirectUrl.val(dataJson.redirectUrl);
                    }
                    else if(dataJson.status === "ok")
                    {
                        notify("You have successfully registered.", "success");
                    }
                    else
                    {
                        notify(dataJson.error,"danger");
                    }
                }
            );
        });

        jQuery('#btnUpdate').click(function (event) {
            //event.preventDefault();
            var email = jQuery("#email");
            var postLogoutRedirectUrl = jQuery("#postLogoutRedirectUrl");
            jQuery.post("/Update.php",
                {oxdEmail: email.val(), postLogoutRedirectUrl: postLogoutRedirectUrl.val() },
                function (data) {
                    //jQuery("#oxdId").val(data.oxdId);
                    var dataJson = JSON.parse(data);
                    if (dataJson.status === "ok")
                    {
                        notify("Site update is successful.", "success");

                        jQuery("#authUrl").prop("disabled", false);
                        jQuery("#btnAuthUrl").prop("disabled", false);
                    }
                    else
                    {
                        notify(dataJson.error,"danger");
                    }
                }
            );
        });

        jQuery('#btnAuthUrl').click(function (event) {
            //event.preventDefault();

            jQuery.post("/GetAuthorizationUrl.php",
                {},
                function (data) {
                    data = JSON.parse(data);
                    jQuery("#authUrl").val(data.authorizationUrl);
                    notify("Got Auth Url.", "success");

                    jQuery("#btnCode").prop("disabled", false);
                }
            );
        });

        jQuery('#btnCode').click(function (event) {
            //event.preventDefault();

            jQuery("#authCode").prop("disabled", false);
            jQuery("#authState").prop("disabled", false);

            var w = 450;
            var h = 450;
            var url = jQuery("#authUrl").val();
            var left = (screen.width/2)-(w/2);
            var top = (screen.height/2)-(h/2);
            var w = window.open(url, "Gluu Login", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
            alert("Please authorize and copy paste the auth code and auth state in the new window.");
            jQuery("#btnToken").prop("disabled", false);
        });

        jQuery('#btnToken').click(function (event) {
            //event.preventDefault();
            var authCode = jQuery("#authCode");
            var authState = jQuery("#authState");            

            if (authCode.val() === "") {
                notify("Get the auth code by using above section", "danger");
                return;
            }

            if (authState.val() === "") {
                notify("Get the auth state by using above section", "danger");
                return;
            }

            jQuery.post("/GetTokens.php",
                { authCode: authCode.val(), authState: authState.val() },
                function (data) {
                    var dataJson = JSON.parse(data);
                    jQuery("#accessToken").val(dataJson.accessToken);
                    jQuery("#refreshToken").val(dataJson.refreshToken);
                    var html = "<li class=\"list-group-item\" style=\"overflow:auto;\">idToken : "+dataJson.idToken+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">at_hash : "+dataJson.idTokenClaims.at_hash[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">aud : "+dataJson.idTokenClaims.aud[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">sub : "+dataJson.idTokenClaims.sub[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">auth_time : "+dataJson.idTokenClaims.auth_time[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">iss : "+dataJson.idTokenClaims.iss[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">exp : "+dataJson.idTokenClaims.exp[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">iat : "+dataJson.idTokenClaims.iat[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">nonce : "+dataJson.idTokenClaims.nonce[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">oxValidationURI : "+dataJson.idTokenClaims.oxValidationURI[0]+"</li>";
                    html += "<li class=\"list-group-item\" style=\"overflow:auto;\">oxOpenIDConnectVersion : "+dataJson.idTokenClaims.oxOpenIDConnectVersion[0]+"</li>";
                    jQuery("#tokens").html(html);
                    notify("Token retrival is successful.", "success");

                    jQuery("#btnUser").prop("disabled", false);
                }
            );
        });

        jQuery('#btnUser').click(function (event) {
            //event.preventDefault();

            var accessToken = jQuery("#accessToken")

            if (accessToken.val() === "") {
                accessToken.notify("Get the tokens first", "danger");
                return;
            }

            jQuery.post("/GetUserInfo.php",
                { accessToken: accessToken.val() },
                function (data) {
                    var dataJson = JSON.parse(data);
                    jQuery("#userName").val(dataJson.userName);
                    jQuery("#userEmail").val(dataJson.userEmail);
                    notify("User information is successful.", "success");

                    //jQuery("#btnUser").prop("disabled", false);
                }
            );
        });

        jQuery('#btnUmaFull').click(function (event) {
            //event.preventDefault();

            var oxdHost = jQuery("#oxdHost");
            var oxdPort = jQuery("#oxdPort");
            var oxdId = jQuery("#oxdId");

            if (oxdHost.val() === "") {
                oxdHost.notify("Host is required", "warning");
                return;
            }

            if (oxdPort.val() === "") {
                oxdPort.notify("Port is required", "warning");
                return;
            }

            if (oxdId.val() === "") {
                oxdId.notify("Site registration is must. Register the site first.", "error");
                return;
            }

            jQuery.post("/FullUmaTest.php",
                { oxdHost: oxdHost.val(), oxdPort: oxdPort.val(), oxdId: oxdId.val() },
                function (data) {
                    alert("The UMA full test is executed with status : " + data.fullTestStatus);
                }
            );
        });

        jQuery('#btnGetGat').click(function (event) {
            //event.preventDefault();

            var oxdHost = jQuery("#oxdHost");
            var oxdPort = jQuery("#oxdPort");
            var oxdId = jQuery("#oxdId");

            if (oxdHost.val() === "") {
                oxdHost.notify("Host is required", "warning");
                return;
            }

            if (oxdPort.val() === "") {
                oxdPort.notify("Port is required", "warning");
                return;
            }

            if (oxdId.val() === "") {
                oxdId.notify("Site registration is must. Register the site first.", "error");
                return;
            }

            jQuery.post("/GetGat.php",
                { oxdHost: oxdHost.val(), oxdPort: oxdPort.val(), oxdId: oxdId.val()},
                function (data) {

                    jQuery("#txtGat").val(data.getGatResponse);
                    jQuery("#btnLogout").notify("Getting GAT is successful.", "success");
                }
            );
        });

        jQuery('#btnLogout').click(function (event) {
            //event.preventDefault();

            var oxdHost = jQuery("#oxdHost");
            var oxdPort = jQuery("#oxdPort")
            var oxdId = jQuery("#oxdId")

            if (oxdHost.val() === "") {
                oxdHost.notify("Host is required", "warning");
                return;
            }

            if (oxdPort.val() === "") {
                oxdPort.notify("Port is required", "warning");
                return;
            }

            if (oxdId.val() === "") {
                oxdId.notify("Site registration is must. Register the site first.", "error");
                return;
            }

            jQuery.post("/GetLogoutUri.php",
                {},
                function (data) {
                    var dataJson = JSON.parse(data);
                    jQuery("#logoutUri").val(dataJson.logoutUri);
                    window.location.href = dataJson.logoutUri;
                }
            );
        });
    });

</script>
<div class="row" id="notify" style="position: fixed;
    top: 0px;
    z-index: 1000;
    width: 100%;
    opacity: 100%;
    height:50px">
    <div class="col-md-8 col-md-offset-2">
        <div id="notify-message"></div>
    </div>
</div>

<div class="jumbotron" style="margin-top: 70px;">
    <h1>Gluu OXD PHP Library Demo web application</h1>
    <p class="lead">This PHP application is used to demo the Gluu's OXD Server PHP Library APIs.</p>
</div>

<br><br><br>
<div class="row">
    <div class="col-md-12">
        <h2>Register Site</h2>
        <form>
            <div class="form-group">
                <label for="authRedirectUrl" class="col-sm-2 control-label">Redirect URL</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="authRedirectUrl" placeholder="Redirect URL">
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Register" id="btnRegister">
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>Update Site</h2>
        <form>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="email" placeholder="Email address" disabled="disabled">
                </div>
                <label for="postLogoutRedirectUrl" class="col-sm-2 control-label">Post Logout Redirect Url</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="postLogoutRedirectUrl" placeholder="url" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Update" id="btnUpdate" disabled="disabled">
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>Get Auth URL</h2>
        <form>
            <div class="form-group">
                <label for="authUrl" class="col-sm-2 control-label">Auth URL</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="authUrl" placeholder="Redirect URL" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Get Auth Url" id="btnAuthUrl" disabled="disabled">
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>Getting Code and State</h2>
        <form>
            <div class="form-group">
                <label for="authCode" class="col-sm-2 control-label">Auth Code</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="authCode" placeholder="Auth Code" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <label for="authState" class="col-sm-2 control-label">Auth State</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="authState" placeholder="Auth State" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Get Code and State" id="btnCode" disabled="disabled">
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>Getting Tokens</h2>
        <form>
            <div class="form-group">
                <label for="accessToken" class="col-sm-2 control-label">Access Token</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="accessToken" placeholder="Access Token" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <label for="refreshToken" class="col-sm-2 control-label">Refresh Token</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="refreshToken" placeholder="Refresh Token" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Get Tokens" id="btnToken" disabled="disabled">
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <ul class="list-group" id="tokens">
        </ul>
    </div>

    <div class="col-md-12">
        <h2>Getting User Info</h2>
        <form>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Get UserInfo" id="btnUser" disabled="disabled">
            </div>
            <div class="form-group">
                <label for="userName" class="col-sm-2 control-label">User Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="userName" placeholder="user name" disabled="disabled">
                </div>
            </div>
            <div class="form-group">
                <label for="userEmail" class="col-sm-2 control-label">User Email</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="userEmail" placeholder="user email" disabled="disabled">
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>UMA Section</h2>
        <form>
            <div class="form-group">
                <input class="btn btn-default" type="button" value="Full UMA Test" id="btnUmaFull">
            </div>
            <div class="form-group">
                <label for="txtGat" class="col-sm-2 control-label">GAT Token</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="txtGat" disabled="disabled">
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>GAT Section</h2>
        <form>
            <div class="form-group">
                <input class="btn btn-default" type="button" value="Get GAT" id="btnGetGat">
            </div>
            <div class="form-group">
                <label for="txtGat" class="col-sm-2 control-label">GAT Token</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="txtGat" disabled="disabled">
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <h2>Getting Logout URI</h2>
        <form>
            <div class="form-group">
                <input class="btn btn-primary" type="button" value="Get Logout Uri" id="btnLogout">
            </div>
            <div class="form-group">
                <label for="logoutUri" class="col-sm-2 control-label">Logout URI</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="logoutUri" placeholder="logout uri">
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <h2>Web Hosting</h2>
        <p>You can easily find a web hosting company that offers the right mix of features and price for your applications.</p>
        <p><a class="btn btn-default" href="http://www.php.net/">Learn more &raquo;</a></p>
    </div>
</div>
	<footer>
		<p>&copy; <?php echo date("Y"); ?> - My PHP Application</p>
	</footer>
</div>
</body>
</html>
