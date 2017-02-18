<p>oxd login page.</p>

<?php
/**
 * Created by  Volodya Karapetyan
 */
session_start();
//session_destroy();exit;
if(isset($_POST['submit']) && isset($_POST['your_mail']) && !empty($_POST['your_mail']) && isset($_POST['gluu_server_url']) && !empty($_POST['gluu_server_url'])){
    if(empty($_SESSION['oxd_id'])){
        require_once './Register_site.php';
        $register_site = new Register_site();
        $register_site->setRequestOpHost($_POST['gluu_server_url']);
        $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
        $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
        $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
        $register_site->setRequestContacts([$_POST['your_mail']]);
        $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
        $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
        $register_site->setRequestScope(Oxd_RP_config::$scope);
        $register_site->request();

        if($register_site->getResponseOxdId()){
            //save in your database
            $_SESSION['oxd_id'] = $register_site->getResponseOxdId();
            require_once './Update_site_registration.php';
            $update_site_registration = new Update_site_registration();
            $update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $update_site_registration->setRequestOxdId($_SESSION['oxd_id']);
            $update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $update_site_registration->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $update_site_registration->setRequestContacts([$_POST['your_mail']]);
            $update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $update_site_registration->setRequestScope(Oxd_RP_config::$scope);
            $update_site_registration->request();
            $_SESSION['oxd_id'] = $update_site_registration->getResponseOxdId();
        }

    }
    require_once './Get_authorization_url.php';
    $get_authorization_url = new Get_authorization_url();
    $get_authorization_url->setRequestOxdId($_SESSION['oxd_id']);
    $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
    $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
    $get_authorization_url->request();

    header("Location: ".$get_authorization_url->getResponseAuthorizationUrl());
    exit;
}
else{
    ?>
    <form method="post" action="/">
        <label for="your_mail">Your email. </label>
        <input type="email" name="your_mail" placeholder="Enter your email." />
        <br/><br/>
        <label for="gluu_server_url">Your Gluu server url. </label>
        <input type="url" name="gluu_server_url" placeholder="Enter Gluu server url." />
        <br/><br/>
        <input type="submit" name="submit" value="Login" />
    </form>
    <?php
}

