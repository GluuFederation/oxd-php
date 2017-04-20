<?php
session_start();
require_once '../Uma_rp_get_gat.php';

$uma_rp_get_gat = new Uma_rp_get_gat();
$uma_rp_get_gat->setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_get_gat->setRequestScopes(["http://photoz.example.com/dev/actions/add","http://photoz.example.com/dev/actions/view", "http://photoz.example.com/dev/actions/edit"]);
$uma_rp_get_gat->request();

var_dump($uma_rp_get_gat->getResponseObject());

$_SESSION['uma_gat']= $uma_rp_get_gat->getResponseGat();
echo $uma_rp_get_gat->getResponseGat();