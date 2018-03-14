<?php
require_once './utils.php';
require_once './oxdlibrary/Setup_client.php';
$config = include('./oxdlibrary/oxdHttpConfig.php');
$baseUrl = __DIR__;
$oxdJSON = file_get_contents($baseUrl . '/oxdId.json');
$oxdObject = json_decode($oxdJSON);
$oxdRpConfig = json_decode(file_get_contents($baseUrl . '/oxdlibrary/oxd-rp-settings.json'));
if (!checkOxdId()) {
    $msg = 'Enter data to register';
} else {
    $msg = 'Client already registered';
}
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>oxd OpenID Connect</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">
    </head>
    <body class="container-fluid">
        <div id="loadingDiv">
            <div>
                <h7>Please wait...</h7>
            </div>
        </div>
        <div id='bod_div' class="col-md-12" >
            <div class="col-md-6" >
                <h3>Register</h3>
                <hr class="hr_modified">
                <form action="" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-sm-4">URI of the OpenID Provider:</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdRpConfig->op_host; ?>" size="50" class="form-control ip_box" name="ophost" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Client Redirect URI</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdRpConfig->authorization_redirect_uri; ?>" size="50" class="form-control ip_box" name="redirect_uri" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Post logout URI</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdRpConfig->post_logout_redirect_uri; ?>" size="50" class="form-control ip_box" name="post_logout_uri" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Connection Type:</label>
                        <div class="ip_box col-sm-8" >
                            <label class="radio-inline"> <input type="radio" name="conn_type_radio" value="local" checked <?php if ($oxdObject->oxd_id) { echo "disabled";}?>> local</label>
                            <label class="radio-inline"> <input type="radio"  name="conn_type_radio" value="web" <?php if ($oxdObject->oxd_id) { echo "disabled";}?>> web</label>
                        </div>
                        <!-- for setting values-->
                        <label class="control-label col-sm-4 " id="conn_label">oxd Port</label>
                        <div class="col-sm-8" id="conn_ip">
                            <input type="number" value="<?php echo $oxdRpConfig->oxd_host_port?$oxdRpConfig->oxd_host_port:8099;?>"  id="oxdLocal" class="form-control ip_box" name="oxd_local_value" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?> />
                            <input type="text" value="<?php echo $oxdRpConfig->oxd_host;?>" id="oxdWeb" class="form-control ip_box" name="oxd_web_name" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?> />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" id="set_id_secret">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Client Name</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdObject->oxd_client_name; ?>" size="50" class="form-control ip_box" name="client_name" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>
                    <?php if(!$oxdObject->has_registration_endpoint || ($oxdObject->oxd_client_id != "" && $oxdObject->oxd_client_secret != "")){ ?>
                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Client Id</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdObject->oxd_client_id; ?>" size="50" class="form-control ip_box" name="client_id" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4 ">Client Secret</label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $oxdObject->oxd_client_secret; ?>" size="50" class="form-control ip_box" name="client_secret" required <?php if ($oxdObject->oxd_id) { echo "disabled";}?>/>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6" id="buttons">
                            <?php if ($oxdObject->oxd_id) { ?>
                                <input type="button" value="Update" class="btn btn-primary" id="update">
                                <input type="button" value="Edit" class="btn btn-primary" id="edit">
                                <input type="button" value="Delete" class="btn btn-primary" id="del">
                                <input type="button" value="Login" class="btn btn-primary" id="login">
                                <input type="button" value="UMA" class="btn btn-primary" id="uma">
                            <?php }else{ ?>
                                <input type="button" value="Register" class="btn btn-primary" id="register">
                            <?php } ?>
                        </div>
                    </div>

                </form>

                <div class="form-group">
                    <?php if ($oxdObject->oxd_id) { ?>
                        <label class="control-label col-sm-4 ">oxd id:</label>
                        <div class="col-sm-8">
                            <p><strong> <?php echo $oxdObject->oxd_id; ?> </strong></p>
                        </div>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-8">
                        <div id="msg_box" class="alert <?php echo $oxdObject->message == ""?"alert-warning":"alert-success"; ?> fade in">
                            <strong><?php echo $oxdObject->message == ""?$msg:$oxdObject->message; ?></strong>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script src="./js/jquery.js"></script>
        <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#oxdWeb").hide();
                $("#update").hide();
                if( "<?php echo $oxdRpConfig->conn_type; ?>" == "web"){
                    $("#oxdLocal").removeAttr("required");
                    $("#oxdLocal").hide();
                    $("#oxdWeb").show();
                    $("#conn_label").html("oxd Web address");
                    $('input:radio[name="conn_type_radio"]').filter('[value="web"]').attr('checked', 'checked');
                }
                $('input:radio[name="conn_type_radio"]').change(function(){
                    if($('input:radio[name="conn_type_radio"]:checked').val() == "web"){
                        $("#oxdLocal").removeAttr("required");
                        $("#oxdLocal").hide();
                        $("#oxdWeb").attr('required');
                        $("#oxdWeb").show();
                        $("#conn_label").html("oxd Web address");
                        $('input:radio[name="conn_type_radio"]').filter('[value="web"]').attr('checked', 'checked');
                    }else{
                        $("#oxdWeb").removeAttr("required");
                        $("#oxdWeb").hide();
                        $("#oxdLocal").attr('required');
                        $("#oxdLocal").show();
                        $("#conn_label").html("oxd Port");
                        $('input:radio[name="conn_type_radio"]').filter('[value="web"]').attr('checked', 'checked');
                    }
                });
                
                function isJson(str) {
                    try {
                        JSON.parse(str);
                    } catch (e) {
                        return false;
                    }
                    return true;
                }
                
                $("#register").click(function(){
                    var request_data = {
                            redirect_uri: $('input:text[name="redirect_uri"]').val(),
                            op_host: $('input:text[name="ophost"]').val(),
                            post_logout_uri: $('input:text[name="post_logout_uri"]').val(),
                            conn_type: $('input:radio[name="conn_type_radio"]:checked').val(),
                            oxd_local_value: $("#oxdLocal").val(),
                            oxd_web_value: $('#oxdWeb').val(),
                            client_name: $('input:text[name="client_name"]').val()
                        };
                    if(<?php echo $oxdObject->has_registration_endpoint?1:0; ?> == "0"){
                        request_data.client_id = $('input:text[name="client_id"]').val();
                        request_data.client_secret = $('input:text[name="client_secret"]').val();
                    }
                    $("#loadingDiv").show();
                    jQuery.post(
                        "SetupClient.php",
                        request_data,
                        function (data) {
                            if(isJson(data)){
                                location.reload();
                            }else{
                                $("#msg_box").html("<strong>"+data+"</strong>");
                                $("#msg_box").removeClass("alert-warning");
                                $("#msg_box").addClass("alert-danger");
                            }
                            $("#loadingDiv").hide();
                        }
                    );
                });
                
                $("#edit").click(function(){
                    $('input:text[name="redirect_uri"]').removeAttr("disabled");
                    $('input:text[name="post_logout_uri"]').removeAttr("disabled");
                    $("#oxdLocal").removeAttr("disabled");
                    $('#oxdWeb').removeAttr("disabled");
                    $('input:radio[name="conn_type_radio"]').removeAttr("disabled");
                    $("#update").show();
                    $("#edit").hide();
                });
                
                $("#update").click(function(){
                    $("#loadingDiv").show();
                    jQuery.post(
                        "Update.php",
                        { 
                            redirect_uri: $('input:text[name="redirect_uri"]').val(),
                            op_host: $('input:text[name="ophost"]').val(),
                            post_logout_uri: $('input:text[name="post_logout_uri"]').val(),
                            conn_type: $('input:radio[name="conn_type_radio"]:checked').val(),
                            oxd_local_value: $("#oxdLocal").val(),
                            oxd_web_value: $('#oxdWeb').val(),
                            client_name: $('input:text[name="client_name"]').val()
                        },
                        function (data) {
                            if(isJson(data)){
                                location.reload();
                            }else{
                                $("#msg_box").html("<strong>"+data+"</strong>");
                                $("#msg_box").removeClass("alert-warning");
                                $("#msg_box").addClass("alert-danger");
                            }
                            $("#loadingDiv").hide();
                        }
                    );
                    
                });
                
                $("#del").click(function(){
                    $("#loadingDiv").show();
                    jQuery.post(
                        "Delete.php",
                        {},
                        function (data) {
                            location.reload();
//                            alert(data);
                        }
                    );
                });
                
                $("#login").click(function(){
                    window.location = "Login.php";
                });
                $("#uma").click(function(){
                    window.location = "Uma.php";
                });
            });
        </script>
    </body>
</html>
