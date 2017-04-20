<?php
session_start();
require_once '../Uma_rp_authorize_rpt.php';

$uma_rp_authorize_rpt = new Uma_rp_authorize_rpt();
$uma_rp_authorize_rpt->setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_authorize_rpt->setRequestRpt($_SESSION['uma_rpt']);
$uma_rp_authorize_rpt->setRequestTicket($_SESSION['uma_ticket']);
$uma_rp_authorize_rpt->request();

var_dump($uma_rp_authorize_rpt->getResponseObject());

