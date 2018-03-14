<?php
session_start();
require_once '../../Introspect_access_token.php';

$introspectAccessToken = new Introspect_access_token();
$introspectAccessToken->setRequest_oxd_id($_SESSION['oxd_id']);
$introspectAccessToken->setRequest_access_token($_SESSION['protection_access_token']);
$introspectAccessToken->request();

echo $introspectAccessToken->getResponseJSON();

