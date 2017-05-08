<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_tokens_by_code.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_tokens_by_code = new Get_tokens_by_code();
            $get_tokens_by_code->setRequestOxdId($oxdId);
            $get_tokens_by_code->setRequestCode($_REQUEST['authCode']);
            $get_tokens_by_code->setRequestState($_REQUEST['authState']);
//	    This is for OXD Socket
//            $get_tokens_by_code->request();
//	    This is for OXD-TO-HTTP
            $get_tokens_by_code->request($config["host"].$config[$get_tokens_by_code->getCommand()]);
            $data['accessToken'] = $get_tokens_by_code->getResponseAccessToken();
            $data['refreshToken'] = $get_tokens_by_code->getResponseRefreshToken();
            $data['idToken'] = $get_tokens_by_code->getResponseIdToken();
            $data['idTokenClaims'] = $get_tokens_by_code->getResponseIdTokenClaims();
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

