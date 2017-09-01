<?php
    require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    $oxdId = getOxdId();

    try{
        if($oxdRpConfig->conn_type == "local"){
//	    This is for OXD Socket
            $get_logout_uri = new Logout();
        }else if($oxdRpConfig->conn_type == "web"){
//	    This is for OXD Web
            $get_logout_uri = new Logout($config);
        }
        $get_logout_uri->setRequestOxdId($oxdId);
        if($oxdObject->has_registration_endpoint){
            $get_logout_uri->setRequest_protection_access_token(getClientProtectionAccessToken());
        }
        $get_logout_uri->request();
        $data["logoutUri"] = $get_logout_uri->getResponseObject()->data->uri;
        echo json_encode($data);
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
?>

