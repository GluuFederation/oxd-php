<?php
/*
 * Created by Volodya Karapetyan
*/
    session_start();
if(!empty($_SESSION['state']) and !empty($_SESSION['user_oxd_id_token']) and !empty($_SESSION['session_state'])){
    //var_dump($_SESSION);exit;
    echo '<p>User login process via OpenID.</p>';
    require_once '../Logout.php';
    echo '<p>Logout.</p>';

    $logout = new Logout();
    $logout->setRequestOxdId($_SESSION['oxd_id']);
    $logout->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
    $logout->setRequestIdToken($_SESSION['user_oxd_id_token']);
    $logout->setRequestSessionState($_SESSION['session_state']);
    $logout->setRequestState($_SESSION['state']);
    $logout->request();

    session_destroy();
    header("Location: ".$logout->getResponseObject()->data->uri);
    exit;
}else{
    header("Location: https://client.example.com/");
}
