<?php
    require_once './utils.php';
    require_once './oxdlibrary/Register_site.php';

    if(!checkOxdId())
    {
        setRedirectUrl($_REQUEST['redirectUrl']);
        try{
            $register_site = new Register_site();
            $register_site->setRequestOpHost(Oxd_RP_config::$op_host);
            $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $register_site->setRequestScope(Oxd_RP_config::$scope);
            $register_site->request();
            setOxdId($register_site->getResponseOxdId());
            $data["status"] = "ok";
            echo json_encode($data);
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        $data["status"] = "done";
        $rdpSettings = getOxdRpSettings();
        $data["redirectUrl"] = $rdpSettings->authorization_redirect_uri;
        echo json_encode($data);
   }
?>

