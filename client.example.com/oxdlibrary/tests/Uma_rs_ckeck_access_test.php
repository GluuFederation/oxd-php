<?php
session_start();
require_once '../Uma_rs_check_access.php';

$uma_rs_authorize_rpt = new Uma_rs_check_access();
$uma_rs_authorize_rpt->setRequestOxdId($_SESSION['oxd_id']);
$uma_rs_authorize_rpt->setRequestRpt($_SESSION['uma_rpt']);
$uma_rs_authorize_rpt->setRequestPath("/uma/testresource");
$uma_rs_authorize_rpt->setRequestHttpMethod("GET");
$uma_rs_authorize_rpt->request();

var_dump($uma_rs_authorize_rpt->getResponseObject());

$_SESSION['uma_ticket'] = $uma_rs_authorize_rpt->getResponseTicket();