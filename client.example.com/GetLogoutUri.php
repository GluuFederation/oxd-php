<?php
    require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
//	    This is for OXD Socket (HTTPS)
//            $get_logout_uri = new Logout();
//	    This is for OXD-TO-HTTP
            $get_logout_uri = new Logout($config);
            $get_logout_uri->setRequestOxdId($oxdId);
            $get_logout_uri->request();
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

