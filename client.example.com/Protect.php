<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rs_protect.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rs_protect = new Uma_rs_protect();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rs_protect = new Uma_rs_protect($config);
        }
        $segment = explode('/',$_SERVER['REQUEST_URI']);
        array_pop($segment);
        $segment = implode("/",$segment);
        $uma_rs_protect->setRequestOxdId($oxdObject->oxd_id);
        //with scope expression
//        $rule = [
//            'and' => [
//                ['or' => [
//                    ['var' => 0],
//                    ['var' => 1]]
//                ],
//                ['var' => 2]
//            ]
//        ];
//        $data = [
//			"https://rsapi.com",
//			"https://rsapi2.com",
//			"https://rsapi3.com"
//		];
//        $uma_rs_protect->addConditionForPath(
//                                                ["GET","POST"],
//                                                [], 
//                                                [],
//                                                ["rule"=>$rule,"data"=>$data]
//                                            );
                                            
//without scope expression                                            
        $uma_rs_protect->addConditionForPath(
                                                ["GET","POST"],
                                                ['https://rsapi.com'], 
                                                ['https://rsapi.com']
                                            );
        
        $segment = explode('/',$_SERVER['REQUEST_URI']);
        array_pop($segment);
        $segment = implode("/",$segment);
        $uma_rs_protect->addResource($segment."/api.php");
        $uma_rs_protect->setRequestOverwrite(true);
        if($oxdRpConfig->conn_type == "local"){
            $uma_rs_protect->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rs_protect->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        
        $uma_rs_protect->request();
        header("Location: https://".$_SERVER['SERVER_NAME'].$segment."/Settings.php");
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>

