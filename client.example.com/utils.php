<?php

    require_once './oxdlibrary/Get_client_access_token.php';
    require_once './oxdlibrary/Get_access_token_by_refresh_token.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));


    function checkOxdId(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        if(!$oxdOBJECT->oxd_id){
            return false;
        }
        return true;
    }
    
    function setOxdParams($oxdId,$oxdClientId,$oxdClientSecret,$message=""){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $oxdOBJECT->oxd_id = $oxdId;
        $oxdOBJECT->oxd_client_id = $oxdClientId;
        $oxdOBJECT->oxd_client_secret = $oxdClientSecret;
        $oxdOBJECT->message = $message;
        $oxdJSON = json_encode($oxdOBJECT);
        file_put_contents($baseUrl.'/oxdId.json',$oxdJSON);
    }
    
    function setOxdParamsFromObject($objOxd){
        $baseUrl = __DIR__;
        $oxdJSON = json_encode($objOxd);
        file_put_contents($baseUrl.'/oxdId.json',$oxdJSON);
    }
    
    function setMessage($message){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $oxdOBJECT->message = $message;
        $oxdJSON = json_encode($oxdOBJECT);
        file_put_contents($baseUrl.'/oxdId.json',$oxdJSON);
    }
    
    function setOxdRpConfig($oxdRpConfig){
        $baseUrl = __DIR__;
        $oxdRpConfig->oxd_host_port = (int)$oxdRpConfig->oxd_host_port;
        file_put_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json',json_encode($oxdRpConfig));
    }
    
    function getOxdId(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        return $oxdOBJECT->oxd_id;
    }
    
    function getOxdClientId(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        return $oxdOBJECT->oxd_client_id;
    }
    
    function getOxdClientSecret(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        return $oxdOBJECT->oxd_client_secret;
    }
    
    function setRedirectUrl($redirectUrl){
        $baseUrl = __DIR__;
        $settingJSON = file_get_contents($baseUrl.'/oxdlibrary/oxd-rp-settings.json');
        $settingOBJECT = json_decode($settingJSON);
        $settingOBJECT->authorization_redirect_uri = $redirectUrl;
        $settingJSON = json_encode($settingOBJECT);
        file_put_contents($baseUrl.'/oxdlibrary/oxd-rp-settings.json',$settingJSON);
    }
    
    function getOxdRpSettings(){
        $baseUrl = __DIR__;
        $settingJSON = file_get_contents($baseUrl.'/oxdlibrary/oxd-rp-settings.json');
        return json_decode($settingJSON);
    }
    
    function getClientProtectionAccessToken($config = null)
    {
        global $oxdRpConfig;
        try{
            if ($oxdRpConfig->conn_type == "local") {
//              This is for OXD Socket
                $getClientAccessToken = new Get_client_access_token();
            } else if ($oxdRpConfig->conn_type == "web") {   
//              This is for OXD-TO-HTTP
                $getClientAccessToken = new Get_client_access_token($config);
            }
            $getClientAccessToken->setRequestOpHost(Oxd_RP_config::$op_host);
            $getClientAccessToken->setRequest_scope(Oxd_RP_config::$scope);
            $getClientAccessToken->setRequest_client_id(getOxdClientId());
            $getClientAccessToken->setRequest_client_secret(getOxdClientSecret());
            $getClientAccessToken->request();
            return $getClientAccessToken->getResponse_access_token();
        }
        catch(Exception $e){
            return false;
        }
    }
    
    function getAccessTokenFromRefreshToken($refreshToken,$config=null){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        try{
            $getAccessTokenFromRefreshToken = new Get_access_token_by_refresh_token($config);
            $getAccessTokenFromRefreshToken->setRequestOxdId($oxdOBJECT->oxd_id);
            $getAccessTokenFromRefreshToken->setRequestRefreshToken($refreshToken);
            if($oxdOBJECT->has_registration_endpoint){
                $getAccessTokenFromRefreshToken->setRequest_protection_access_token(getClientProtectionAccessToken($config));
            }
            $getAccessTokenFromRefreshToken->request();
            return $getAccessTokenFromRefreshToken->getResponseAccessToken();
        } catch(Exception $e){
            return false;
        };
    }
    
    function check_registration_endpoints($op_host){
        if($op_host == ""){
            return true;
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$op_host/.well-known/openid-configuration");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $configurationObj = json_decode($data);
        
        if(empty($configurationObj->registration_endpoint)){
            return false;
        }
        return true;
    }
    
?>

