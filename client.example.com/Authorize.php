<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_authorization_url.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    $oxdId = getOxdId();
        try{
            if($oxdRpConfig->conn_type == "local"){
//          This is for OXD Socket
                $get_authorization_url = new Get_authorization_url();
            }
            else if($oxdRpConfig->conn_type == "web"){
//              This is for OXD-TO-HTTP
                $get_authorization_url = new Get_authorization_url($config);
            }
            $get_authorization_url->setRequestOxdId($oxdId);
            $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
            $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
            if($oxdObject->has_registration_endpoint){
                if($oxdRpConfig->conn_type == "local"){
                    $get_authorization_url->setRequest_protection_access_token(getClientProtectionAccessToken());
                }else if($oxdRpConfig->conn_type == "web"){
                    $get_authorization_url->setRequest_protection_access_token(getClientProtectionAccessToken($config));
                }
            }
            $get_authorization_url->request();
            header('Location: '.$get_authorization_url->getResponseAuthorizationUrl());
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
?>

