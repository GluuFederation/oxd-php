<?php
    require_once './utils.php';
    require_once './oxdlibrary/Uma_rp_get_rpt.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    $baseUrl = __DIR__;
    $oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
    $oxdObject = json_decode($oxdJSON);
    $oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
    try{
        if($oxdRpConfig->conn_type == "local"){
    //	    This is for OXD Socket
            $uma_rp_get_rpt = new Uma_rp_get_rpt();
        }
        else if($oxdRpConfig->conn_type == "web"){
    //	    This is for OXD-TO-HTTP
            $uma_rp_get_rpt = new Uma_rp_get_rpt($config);
        }
        $uma_rp_get_rpt->setRequest_oxd_id($oxdObject->oxd_id);
        $ticket = getProtectedResource($_REQUEST["protected_resource"]);

        $uma_rp_get_rpt->setRequest_ticket($ticket);
        
        if($oxdRpConfig->conn_type == "local"){
            $uma_rp_get_rpt->setRequest_protection_access_token(getClientProtectionAccessToken());
        }else if($oxdRpConfig->conn_type == "web"){
            $uma_rp_get_rpt->setRequest_protection_access_token(getClientProtectionAccessToken($config));
        }
        $uma_rp_get_rpt->request();
        if($uma_rp_get_rpt->getIs_needinfo()){
            if($oxdRpConfig->conn_type == "local"){
                $url = getClaimsGatheringUrl($uma_rp_get_rpt->getNeedinfo_ticket());
            } else if($oxdRpConfig->conn_type == "web"){
                $url = getClaimsGatheringUrl($uma_rp_get_rpt->getNeedinfo_ticket(),$config);
            }
            header("Location: $url");
        } else {
            $response = getProtectedResource($_REQUEST["protected_resource"],$uma_rp_get_rpt->getResponse_access_token());
            print_r($response);
        }
    }
    catch(Exception $e){
        echo "{\"error\":\"".$e->getMessage()."\"}";
    }
?>
