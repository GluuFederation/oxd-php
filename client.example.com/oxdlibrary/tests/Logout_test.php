<?php
session_start();
require_once '../Logout.php';

$logout = new Logout();
$logout->setRequestOxdId($_SESSION['oxd_id']);
$logout->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
$logout->setRequestIdToken($_SESSION['user_oxd_access_token']);
$logout->setRequestSessionState($_SESSION['session_states']);
$logout->setRequestState($_SESSION['states']);
$logout->request();

echo $logout->getResponseHtml();

