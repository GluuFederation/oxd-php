<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_user_info.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_user_info = new Get_user_info();
            $get_user_info->setRequestOxdId($oxdId);
            $get_user_info->setRequestAccessToken($_REQUEST['accessToken']);
//	    This is for OXD Socket
//            $get_user_info->request();
//	    This is for OXD-TO-HTTP
            $get_user_info->request($config["host"].$config[$get_user_info->getCommand()]);
            $data = $get_user_info->getResponseClaims();
            $response['userEmail'] = $data->email[0];
            $response['userName'] = $data->name[0];
            echo json_encode($response);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else {
        echo "Please register your site first";
    }
?>

