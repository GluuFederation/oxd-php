<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_authorization_url.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
//	    This is for OXD Socket (HTTPS)
//            $get_authorization_url = new Get_authorization_url();
//	    This is for OXD-TO-HTTP
            $get_authorization_url = new Get_authorization_url($config);
            $get_authorization_url->setRequestOxdId($oxdId);
            $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
            $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $get_authorization_url->request();
            echo "{\"authorizationUrl\":\"".$get_authorization_url->getResponseAuthorizationUrl()."\"}";
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else {
        echo "Please register your site first";
    }
?>

