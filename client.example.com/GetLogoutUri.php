<?php
    require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_logout_uri = new Logout();
            $get_logout_uri->setRequestOxdId($oxdId);
//	    This is for OXD Socket
//            $get_logout_uri->request();
//	    This is for OXD-TO-HTTP
            $get_logout_uri->request($config["host"].$config[$get_logout_uri->getCommand()]);
            $data["logoutUri"] = $get_logout_uri->getResponseObject()->data->uri;
            echo json_encode($data);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else {
        echo "Please register your site first";
    }
?>

