<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rs_check_access.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rs_check_access = new Uma_rs_check_access();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rs_check_access = new Uma_rs_check_access($config);
        }
        $uma_rs_check_access->setRequestOxdId($oxdObject->oxd_id);
        $uma_rs_check_access->setRequestRpt("7f127550-211b-4933-b5c1-21934a478021_11E0.A770.B55E.A7F9.27F5.B594.A4AE.A7DE");
        $uma_rs_check_access->setRequestPath("/photo");
        $uma_rs_check_access->setRequestHttpMethod('GET');
        
        if($oxdRpConfig->conn_type == "local"){
            $uma_rs_check_access->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rs_check_access->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        $uma_rs_check_access->request();
        print_r($uma_rs_check_access->getResponseObject());
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>
