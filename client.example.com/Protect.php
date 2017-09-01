<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rs_protect.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rs_protect = new Uma_rs_protect();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rs_protect = new Uma_rs_protect($config);
        }
        $uma_rs_protect->setRequestOxdId($oxdObject->oxd_id);
        $uma_rs_protect->addConditionForPath(["GET"], ["https://scim-test.gluu.org/identity/seam/resource/restv1/scim/vas1"], ["https://scim-test.gluu.org/identity/seam/resource/restv1/scim/vas1"]);
        $uma_rs_protect->addResource("/photo");
        if($oxdRpConfig->conn_type == "local"){
            $uma_rs_protect->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rs_protect->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        
        $uma_rs_protect->request();
        echo $uma_rs_protect->getResponseJSON();
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>

