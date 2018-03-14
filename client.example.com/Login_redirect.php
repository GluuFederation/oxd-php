<?php
require_once './utils.php';
require_once './oxdlibrary/Get_tokens_by_code.php';
require_once './oxdlibrary/Get_user_info.php';
$config = include('./oxdlibrary/oxdHttpConfig.php');
$baseUrl = __DIR__;
$oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
$oxdObject = json_decode($oxdJSON);
$oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
$oxdId = getOxdId();
try {
    if ($oxdRpConfig->conn_type == "local") {
//              This is for OXD Socket
        $get_tokens_by_code = new Get_tokens_by_code();
    } else if ($oxdRpConfig->conn_type == "web") {
//              This is for OXD-TO-HTTP
        $get_tokens_by_code = new Get_tokens_by_code($config);
    }
    $get_tokens_by_code->setRequestOxdId($oxdId);
    $get_tokens_by_code->setRequestCode($_GET['code']);
    $get_tokens_by_code->setRequestState($_GET['state']);
    if ($oxdObject->has_registration_endpoint) {
        if($oxdRpConfig->conn_type == "local"){
            $get_tokens_by_code->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $get_tokens_by_code->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
    }
    $get_tokens_by_code->request();
    $data['accessToken'] = $get_tokens_by_code->getResponseAccessToken();
    $data['refreshToken'] = $get_tokens_by_code->getResponseRefreshToken();
    $data['idToken'] = $get_tokens_by_code->getResponseIdToken();
    $data['idTokenClaims'] = $get_tokens_by_code->getResponseIdTokenClaims();
    if ($oxdObject->has_registration_endpoint) {
        if($oxdRpConfig->conn_type == "local"){
            $is_active_token = introspectAccessToken($data['accessToken']);
        }else if($oxdRpConfig->conn_type == "web"){
            $is_active_token = introspectAccessToken($data['accessToken'],$config);
        }
        if(!$is_active_token){
            if($oxdRpConfig->conn_type == "local"){
                $data['accessToken'] = getAccessTokenFromRefreshToken($data['refreshToken']);
            }else if($oxdRpConfig->conn_type == "web"){
                $data['accessToken'] = getAccessTokenFromRefreshToken($data['refreshToken'],$config);
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
try {
    if ($oxdRpConfig->conn_type == "local") {
//      This is for OXD Socket
        $get_user_info = new Get_user_info();
        if(!$oxdObject->has_registration_endpoint){
            $accessToken = $get_tokens_by_code->getResponseAccessToken();
        }else{
            $accessToken = getAccessTokenFromRefreshToken($get_tokens_by_code->getResponseRefreshToken());
        }
    } else if ($oxdRpConfig->conn_type == "web") {
//	This is for OXD-TO-HTTP
        $get_user_info = new Get_user_info($config);
        if(!$oxdObject->has_registration_endpoint){
            $accessToken = $get_tokens_by_code->getResponseAccessToken();
        }else{
            $accessToken = getAccessTokenFromRefreshToken($get_tokens_by_code->getResponseRefreshToken(), $config);
        }
    }
    $get_user_info->setRequestOxdId($oxdId);
    $get_user_info->setRequestAccessToken($accessToken);
    if ($oxdObject->has_registration_endpoint) {
        if($oxdRpConfig->conn_type == "local"){
            $get_user_info->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $get_user_info->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
    }
    $get_user_info->request();
    $data = $get_user_info->getResponseClaims();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <script src="./js/jquery.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div class="container body-content">
            <div id="loadingDiv">
                <div>
                    <h7>Please wait...</h7>
                </div>
            </div>
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
                    <h2>Getting User Info</h2>
                    <form>
                        <div class="form-group">
                            <label for="userName" class="col-sm-2 control-label">User Name</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="userName" placeholder="user name" disabled="disabled" value="<?php echo $data->name[0]; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="userEmail" class="col-sm-2 control-label">User Email</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="userEmail" placeholder="user email" disabled="disabled" value="<?php echo $data->email[0] ? $data->email[0] : "N/A"; ?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <h2>Getting Logout URI</h2>
                    <form>
                        <div class="form-group">
                            <input class="btn btn-primary" type="button" value="Logout" id="btnLogout">
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
        <script>
            $(document).ready(function () {
                jQuery('#btnLogout').click(function (event) {
                    $("#loadingDiv").show();
                    jQuery.post("GetLogoutUri.php",
                            {},
                            function (data) {
                                var dataJson = JSON.parse(data);
                                window.location.href = dataJson.logoutUri;
                            }
                    );
                });
            });
        </script>
    </body>
</html>