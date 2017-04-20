<?php
    require_once './utils.php';
    require_once './oxdlibrary/Get_tokens_by_code.php';
    
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_tokens_by_code = new Get_tokens_by_code();
            $get_tokens_by_code->setRequestOxdId($oxdId);
            $get_tokens_by_code->setRequestCode($_REQUEST['authCode']);
            $get_tokens_by_code->setRequestState($_REQUEST['authState']);
            $get_tokens_by_code->request();
            $data['accessToken'] = $get_tokens_by_code->getResponseAccessToken();
            $data['refreshToken'] = $get_tokens_by_code->getResponseRefreshToken();
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

