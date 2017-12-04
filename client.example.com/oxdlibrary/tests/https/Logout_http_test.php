<?php
session_start();
require_once '../../Logout.php';
$config = include('../../oxdHttpConfig.php');

$logout = new Logout($config);
$logout->setRequestOxdId($_SESSION['oxd_id']);
$logout->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
$logout->setRequestIdToken($_SESSION['user_oxd_access_token']);
$logout->setRequestSessionState($_SESSION['session_states']);
$logout->setRequestState($_SESSION['states']);
$logout->setRequest_protection_access_token($_SESSION['protection_access_token']);
$logout->request();

session_destroy();
echo $logout->getResponseHtml();

