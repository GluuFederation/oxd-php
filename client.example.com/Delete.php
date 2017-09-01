<?php
    require_once './utils.php';
    require_once './oxdlibrary/Update_site_registration.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdObject->oxd_id = "";
    $oxdObject->oxd_client_id = "";
    $oxdObject->oxd_client_secret = "";
    $oxdObject->oxd_client_name = "";
    $oxdObject->message = "";
    $oxdObject->has_registration_endpoint = true;
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
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
?>

