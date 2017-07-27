<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_user_info.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
//	    This is for OXD Socket (HTTPS)
//            $get_user_info = new Get_user_info();
//	    This is for OXD-TO-HTTP
            $get_user_info = new Get_user_info($config);
            $get_user_info->setRequestOxdId($oxdId);
            $get_user_info->setRequestAccessToken($_REQUEST['accessToken']);
            $get_user_info->request();
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

