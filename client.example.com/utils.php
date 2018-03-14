<?php

    require_once './oxdlibrary/Get_client_access_token.php';
    require_once './oxdlibrary/Get_access_token_by_refresh_token.php';
    require_once './oxdlibrary/Uma_rp_get_claims_gathering_url.php';
    require_once './oxdlibrary/Introspect_access_token.php';
    require_once './oxdlibrary/Uma_introspect_rpt.php';
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
    
    
    function getProtectedResource($resource,$access_token = null){
        
        error_reporting(E_ALL); 
        ini_set('display_errors', 1);

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $resource); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $postArray = [];
        if($access_token != null){
            
            $postArray["RPT"]=$access_token;
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postArray);
        $ticket = curl_exec($ch); 
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close($ch);
        if($ticket == ""){
            echo $errmsg;
        }
        $ticket = str_replace("\n", '', $ticket);
        $ticket = str_replace("\r", '', $ticket);
        return $ticket;
    }
    
    function getClaimsGatheringUrl($ticket,$config = null){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $claimsGatheringUrl = new Uma_rp_get_claims_gathering_url($config);
        $claimsGatheringUrl->setRequest_oxd_id($oxdOBJECT->oxd_id);
        $segment = explode('/',$_SERVER['REQUEST_URI']);
        array_pop($segment);
        $segment = implode("/",$segment);
        $claimsGatheringUrl->setRequest_claims_redirect_uri("https://".$_SERVER['SERVER_NAME'].$segment."/Claims_gathering_redirect.php");
        $claimsGatheringUrl->setRequest_ticket($ticket);
        $claimsGatheringUrl->setRequest_protection_access_token(getClientProtectionAccessToken());
        $claimsGatheringUrl->request();
        return $claimsGatheringUrl->getResponse_url();
    }
    
    function introspectAccessToken($access_token,$config = null){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $introspectaccesstoken = new Introspect_access_token($config);
        $introspectaccesstoken->setRequest_oxd_id($oxdOBJECT->oxd_id);
        $introspectaccesstoken->setRequest_access_token($access_token);
        $introspectaccesstoken->request();
        return $introspectaccesstoken->getResponse_active();
    }
    
    function introspectRpt($RPT,$config = null){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $introspectRpt = new Uma_introspect_rpt($config);
        $introspectRpt->setRequest_oxd_id($oxdOBJECT->oxd_id);
        $introspectRpt->setRequest_rpt($RPT);
        $introspectRpt->request();
        return $introspectRpt->getResponse_active();
    }
?>

