<?php
    require_once './utils.php';
    require_once './oxdlibrary/Update_site.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    if (isset($_POST['op_host'])) {
    $oxdRpConfig->op_host = $_POST['op_host'];
    }
    if (isset($_POST['redirect_uri'])) {
        $oxdRpConfig->authorization_redirect_uri = $_POST['redirect_uri'];
    }
    if (isset($_POST['post_logout_uri'])) {
        $oxdRpConfig->post_logout_redirect_uri = $_POST['post_logout_uri'];
    }
    if (isset($_POST['conn_type'])) {
        $oxdRpConfig->conn_type = $_POST['conn_type'];
        if (isset($_POST['oxd_socket_value']) && $_POST['conn_type'] == "socket") {
            $oxdRpConfig->oxd_host_port = $_POST['oxd_socket_value'];
            $oxdRpConfig->oxd_host = '';
        }

        if (isset($_POST['oxd_web_value']) && $_POST['conn_type'] == "web") {
            $oxdRpConfig->oxd_host = $_POST['oxd_web_value'];
            $config['host'] = $_POST['oxd_web_value'];
        }
    }
    setOxdParamsFromObject($oxdObject);
    setOxdRpConfig($oxdRpConfig);
    $oxdId = getOxdId();
    if(!$oxdObject->has_registration_endpoint){
        setMessage("Successfully Updated");
        echo "{\"status\":\"ok\"}";
        exit;
    }
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $update_site = new Update_site();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD Web
            $update_site = new Update_site($config);
        }
        $update_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
        $update_site->setRequestOxdId($oxdId);
        $update_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
        $update_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
        $update_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
        $update_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
        $update_site->setRequestScope(Oxd_RP_config::$scope);
        if($oxdObject->has_registration_endpoint){
            if($oxdRpConfig->conn_type == "local"){
                $update_site->setRequest_protection_access_token(getClientProtectionAccessToken());
            }else if($oxdRpConfig->conn_type == "web"){
                $update_site->setRequest_protection_access_token(getClientProtectionAccessToken($config));
            }
        }
        $update_site->request($update_site->getUrl());
        setMessage("Successfully Updated");
        echo "{\"status\":\"ok\"}";
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>

