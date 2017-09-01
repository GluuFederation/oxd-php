<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rp_get_claims_gathering_url.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rp_get_claims_gathering_url = new Uma_rp_get_claims_gathering_url();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rp_get_claims_gathering_url = new Uma_rp_get_claims_gathering_url($config);
        }
        $uma_rp_get_claims_gathering_url->setRequest_oxd_id($oxdObject->oxd_id);
        $uma_rp_get_claims_gathering_url->setRequest_ticket("eeb13b4d-fd18-43a4-916e-c6b61ea4249b");
        $uma_rp_get_claims_gathering_url->setRequest_claims_redirect_uri("https://oxd.example.com/");
        
        if($oxdRpConfig->conn_type == "local"){
            $uma_rp_get_claims_gathering_url->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rp_get_claims_gathering_url->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        $uma_rp_get_claims_gathering_url->request();
        echo $uma_rp_get_claims_gathering_url->getResponseJSON();
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>
