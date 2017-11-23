<?php
require_once './utils.php';
require_once './oxdlibrary/Uma_rp_get_rpt.php';
$config = include('./oxdlibrary/oxdHttpConfig.php');
$baseUrl = __DIR__;
$oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
$oxdObject = json_decode($oxdJSON);
$oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
if($oxdRpConfig->conn_type == "local"){
    $uma_rp_get_rpt = new Uma_rp_get_rpt();
} else {
    $uma_rp_get_rpt = new Uma_rp_get_rpt($config);
}
$uma_rp_get_rpt->setRequest_oxd_id($oxdObject->oxd_id);
$uma_rp_get_rpt->setRequest_protection_access_token(getClientProtectionAccessToken());
$uma_rp_get_rpt->setRequest_ticket($_REQUEST['ticket']);
$uma_rp_get_rpt->setRequest_state($_REQUEST['state']);
$uma_rp_get_rpt->request();
$response = getProtectedResource($oxdObject->protected_resource_url,$uma_rp_get_rpt->getResponse_access_token());
print_r($response);

?>

