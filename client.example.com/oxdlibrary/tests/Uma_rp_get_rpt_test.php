<?php
session_start();
require_once '../Uma_rp_get_rpt.php';

$uma_rp_get_rpt = new Uma_rp_get_rpt();
$uma_rp_get_rpt->setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_get_rpt->request();

var_dump($uma_rp_get_rpt->getResponseObject());

$_SESSION['uma_rpt']= $uma_rp_get_rpt->getResponseRpt();
echo $uma_rp_get_rpt->getResponseRpt();

