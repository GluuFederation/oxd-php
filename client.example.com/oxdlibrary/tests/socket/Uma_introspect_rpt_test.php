<?php
session_start();
require_once '../../Uma_introspect_rpt.php';

$umaIntrospectRpt = new Uma_introspect_rpt();
$umaIntrospectRpt->setRequest_oxd_id($_SESSION['oxd_id']);
$umaIntrospectRpt->setRequest_rpt($_SESSION['uma_rpt']);
$umaIntrospectRpt->setRequest_protection_access_token($_SESSION['protection_access_token']);
$umaIntrospectRpt->request();

echo $umaIntrospectRpt->getResponseHtml();

