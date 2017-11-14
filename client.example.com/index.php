<?php
ob_start();
require_once './utils.php';

$oxdId = getOxdId();
//echo $_SERVER['SERVER_NAME'];
if($oxdId != ""){
    header("location: https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."Login.php");
}else{
    header("location: https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."Settings.php");
}

