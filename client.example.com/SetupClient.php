<?php
require_once './utils.php';
require_once './oxdlibrary/Setup_client.php';
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
    if (isset($_POST['oxd_local_value']) && $_POST['conn_type'] == "local") {
        $oxdRpConfig->oxd_host_port = $_POST['oxd_local_value'];
        $oxdRpConfig->oxd_host = '';
    }

    if (isset($_POST['oxd_web_value']) && $_POST['conn_type'] == "web") {
        $oxdRpConfig->oxd_host = $_POST['oxd_web_value'];
        $config['host'] = $_POST['oxd_web_value'];
    }
}
setOxdRpConfig($oxdRpConfig);
if(check_registration_endpoints($oxdRpConfig->op_host))
{
    $oxdRpConfig->scope = ["openid", "email", "profile","uma_protection", "uma_authorization", "uma_rpt_policy", "sampleClaimsGathering"];
    setOxdRpConfig($oxdRpConfig);
    try {
        if($oxdRpConfig->conn_type == "local"){
            $register_site = new Setup_client();
        }
        else if($oxdRpConfig->conn_type == "web"){
            $register_site = new Setup_client($config);
        }
            $register_site->setRequestOpHost(Oxd_RP_config::$op_host);
            $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $register_site->setRequestScope(Oxd_RP_config::$scope);
            $register_site->setRequestClientName($_POST['client_name']);
	    $segment = explode('/',$_SERVER['REQUEST_URI']);
            array_pop($segment);
            $segment = implode("/",$segment);
            $register_site->setRequestClaimsRedirectUri(["https://".$_SERVER['SERVER_NAME'].$segment."/Claims_gathering_redirect.php"]);
            $register_site->request();
            $oxdObject->oxd_id = $register_site->getResponseOxdId();
            $oxdObject->oxd_client_name = $_POST['client_name'];
            $oxdObject->oxd_client_id = $register_site->getResponse_client_id();
            $oxdObject->oxd_client_secret = $register_site->getResponse_client_secret();
            $oxdObject->message = "Successfully Registered";
            setOxdParamsFromObject($oxdObject);
            $data["status"] = "ok";
            echo json_encode($data);
    } catch (Exception $e) {
        setOxdParams("", "", "",$e->getMessage());
        echo "{\"error\":\"" . $e->getMessage() . "\"}";
    }
}else{
    $oxdObject->has_registration_endpoint = false;
    $oxdRpConfig->scope = ["openid","profile","email"];
    setOxdRpConfig($oxdRpConfig);
    if (isset($_POST['client_id'])) {
        $oxdObject->oxd_client_id = $_POST['client_id'];
    }
    
    if (isset($_POST['client_secret'])) {
        $oxdObject->oxd_client_secret = $_POST['client_secret'];
    }
    
    if (isset($_POST['client_name'])) {
        $oxdObject->oxd_client_name = $_POST['client_name'];
    }
    setOxdParamsFromObject($oxdObject);
    if($oxdObject->oxd_client_id=="" && $oxdObject->oxd_client_secret==""){
        $data["status"] = "ok";
        echo json_encode($data);
        exit;
    }
    try {
        if($oxdRpConfig->conn_type == "local"){
            $register_site = new Setup_client();
        }
        else if($oxdRpConfig->conn_type == "web"){
            $register_site = new Setup_client($config);
        }
            $register_site->setRequestOpHost(Oxd_RP_config::$op_host);
            $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $register_site->setRequestClientId($oxdObject->oxd_client_id);
            $register_site->setRequestClientSecret($oxdObject->oxd_client_secret);
            $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $register_site->setRequestScope(Oxd_RP_config::$scope);
            $register_site->request();
            setOxdParams($register_site->getResponseOxdId(), $register_site->getResponse_client_id(), $register_site->getResponse_client_secret(),"Successfully Registered");
            $data["status"] = "ok";
            echo json_encode($data);
    } catch (Exception $e) {
        setOxdParams("", "", "",$e->getMessage());
        echo "{\"error\":\"" . $e->getMessage() . "\"}";
    }
}
?>
