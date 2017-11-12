<?php
ob_start();
require_once './utils.php';

$oxdId = getOxdId();
//echo $_SERVER['SERVER_NAME'];
if($oxdId != ""){
    header("location: https://".$_SERVER['SERVER_NAME']."/Login.php");
}else{
    header("location: https://".$_SERVER['SERVER_NAME']."/Settings.php");
}

