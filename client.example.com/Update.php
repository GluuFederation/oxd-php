<?php
    require_once './utils.php';
    require_once './oxdlibrary/Update_site_registration.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $update_site_registration = new Update_site_registration();
            
            $update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $update_site_registration->setRequestOxdId($oxdId);
            $update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $update_site_registration->setRequestPostLogoutRedirectUri($_POST['postLogoutRedirectUrl']);
            $update_site_registration->setRequestContacts([$_POST['oxdEmail']]);
            $update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $update_site_registration->setRequestScope(Oxd_RP_config::$scope);
//	    This is for OXD Socket
//            $update_site_registration->request();
//	    This is for OXD-TO-HTTP
            $update_site_registration->request($config["host"].$config[$update_site_registration->getCommand()]);
            echo "{\"status\":\"ok\"}";
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        echo "{\"error\":\"Please register your site first\"";
    }
?>

