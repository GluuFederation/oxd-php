<?php
    require_once './utils.php';
    require_once './oxdlibrary/Remove_site.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $remove_site = new Remove_site();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD Web
            $remove_site = new Remove_site($config);
        }
        $remove_site->setRequestOxdId($oxdObject->oxd_id);
        if ($oxdObject->has_registration_endpoint) {
            if($oxdRpConfig->conn_type == "local"){
                $remove_site->setRequest_protection_access_token(getClientProtectionAccessToken());
            }else if($oxdRpConfig->conn_type == "web"){
                $remove_site->setRequest_protection_access_token(getClientProtectionAccessToken($config));
            }
        }
        
        $remove_site->request();
        $oxdObject->oxd_id = "";
        $oxdObject->oxd_client_id = "";
        $oxdObject->oxd_client_secret = "";
        $oxdObject->oxd_client_name = "";
        $oxdObject->message = "";
        $oxdObject->has_registration_endpoint = true;
        $oxdRpConfig->op_host = "";
        $oxdRpConfig->oxd_host = "";
        $oxdRpConfig->oxd_host_port = "8099";
        $oxdRpConfig->authorization_redirect_uri = "";
        $oxdRpConfig->post_logout_redirect_uri = "";
        $oxdRpConfig->conn_type = "";
        setOxdParamsFromObject($oxdObject);
        setOxdRpConfig($oxdRpConfig);
        $oxdId = getOxdId();
        echo "{\"status\":\"ok\"}";
    } catch(Exception $e) {
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>

