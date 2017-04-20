<?php
    require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_logout_uri = new Logout();
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

