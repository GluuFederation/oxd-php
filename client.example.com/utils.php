<?php
    function checkOxdId(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        if(!$oxdOBJECT->oxd_id){
            return false;
        }
        return true;
    }
    
    function setOxdId($oxdId){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        $oxdOBJECT->oxd_id = $oxdId;
        $oxdJSON = json_encode($oxdOBJECT);
        file_put_contents($baseUrl.'/oxdId.json',$oxdJSON);
    }
    
    function getOxdId(){
        $baseUrl = __DIR__;
        $oxdJSON = file_get_contents($baseUrl.'/oxdId.json');
        $oxdOBJECT = json_decode($oxdJSON);
        return $oxdOBJECT->oxd_id;
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
    
?>

