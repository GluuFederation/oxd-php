# oxd-php-library

oxd-php-library is a client library for the Gluu oxd Server. For information about oxd, visit [http://oxd.gluu.org](http://oxd.gluu.org) 

## Pre Requisite

#### Required

- [GLUU Server](https://www.gluu.org/)
- [OXD Server](https://gluu.org/docs/oxd)

#### Optional

OXD-TO-HTTP Server is required if you want to access OXD server over HTTP.
- [OXD-TO-HTTP Server](https://github.com/GluuFederation/oxd-to-http)

###### Attention
```
Applications will not be working if your hosts does not have https://.
```


## Installation

### Source

oxd-php-library source is available on Github:

- [Github sources](https://github.com/GluuFederation/oxd-php-library)

### Composer: oxd-php-api

- [Compose API source](https://github.com/GluuFederation/oxd-php-api)
- [Library version 3.0.1](https://github.com/GluuFederation/oxd-php-api/releases/tag/v3.0.1)

This is the preferred method. See the [composer](https://getcomposer.org) 
website for 
[installation instructions](https://getcomposer.org/doc/00-intro.md) if 
you do not already have it installed. 

To install oxd-php-api via Composer, execute the following command 
in your project root:

```
$ composer install `composer require "gluufederation/oxd-php-api": "3.0.1"`

```

**Note**: OpenID Connect requires *https.* This library will not 
work if your website uses *http* only.

## Configuration 

The oxd-php-library configuration file is located in 
'oxd-rp-settings.json'. The values here are used during 
registration. For a full list of supported
oxd configuration parameters, see the 
[oxd documentation](https://gluu.org/docs/oxd/protocol/)
Below is a typical configuration data set for registration:

``` {.code }
{
   "op_host":"<GLUU Server url>",
   "oxd_host":"<OXD server host IP>",
   "oxd_host_port":8099,
   "authorization_redirect_uri":"[https://client.example.com/welcome]",
   "post_logout_redirect_uri":"[https://client.example.com/welcome]",
   "scope":[
      "openid",
      "profile",
      "uma_protection",
      "uma_authorization"
   ],
   "application_type":"web",
   "response_types":[
      "code"
   ],
   "grant_types":[
      "authorization_code"
   ],
   "acr_values":[
      ""
   ]
}
                        
```

-   oxd_host_port - oxd port or socket


## OXD-To-HTTP Configuration

The oxd-php-library configuration file is located in 
'oxdHttpConfig.php'. The values here are used during 
the usage of all GLUU protocols.For a full list of supported protocols, see the [oxd protocol](https://gluu.org/docs/oxd/protocol/) documentation.

``` {.code }
return [
    'host' => '<OXD-TO-HTTP Host>',
    'get_authorization_url' => "get-authorization-url",
    'update_site_registration' => "update-site",
    'get_tokens_by_code' => "get-tokens-by-code",
    'get_user_info' => "get-user-info",
    'register_site' => "register-site",
    'get_logout_uri' => "logout"
];
                        
```

## Sample code


### Register.php (OXD-TO-HTTP)

- [Register_site protocol description](https://gluu.org/docs/oxd/protocol/#register-site).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Register_site.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');

    if(!checkOxdId())
    {
        setRedirectUrl($_REQUEST['redirectUrl']);
        try{
            $register_site = new Register_site();
            $register_site->setRequestOpHost(Oxd_RP_config::$op_host);
            $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $register_site->setRequestScope(Oxd_RP_config::$scope);
            $register_site->request($config["host"].$config[$register_site->getCommand()]);
            setOxdId($register_site->getResponseOxdId());
            $data["status"] = "ok";
            echo json_encode($data);
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        $data["status"] = "done";
        $rdpSettings = getOxdRpSettings();
        $data["redirectUrl"] = $rdpSettings->authorization_redirect_uri;
        echo json_encode($data);
   }

                        
```

### Register.php (SOCKET)

- [Register_site protocol description](https://gluu.org/docs/oxd/protocol/#register-site).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Register_site.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');

    if(!checkOxdId())
    {
        setRedirectUrl($_REQUEST['redirectUrl']);
        try{
            $register_site = new Register_site();
            $register_site->setRequestOpHost(Oxd_RP_config::$op_host);
            $register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $register_site->setRequestPostLogoutRedirectUri(Oxd_RP_config::$post_logout_redirect_uri);
            $register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $register_site->setRequestScope(Oxd_RP_config::$scope);
            $register_site->request();
            setOxdId($register_site->getResponseOxdId());
            $data["status"] = "ok";
            echo json_encode($data);
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        $data["status"] = "done";
        $rdpSettings = getOxdRpSettings();
        $data["redirectUrl"] = $rdpSettings->authorization_redirect_uri;
        echo json_encode($data);
   }

                        
```


### Update.php (OXD-TO-HTTP)

- [Update_site_registration protocol description](https://gluu.org/docs/oxd/protocol/#update-site-registration).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Update_site_registration.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $update_site_registration = new Update_site_registration();
            
            $update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $update_site_registration->setRequestOxdId($oxdId);
            $update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $update_site_registration->setRequestPostLogoutRedirectUri($_POST['postLogoutRedirectUrl']);
            $update_site_registration->setRequestContacts([$_POST['oxdEmail']]);
            $update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $update_site_registration->setRequestScope(Oxd_RP_config::$scope);
            $update_site_registration->request($config["host"].$config[$update_site_registration->getCommand()]);
            echo "{\"status\":\"ok\"}";
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        echo "{\"error\":\"Please register your site first\"";
    }

                        
```

### Update.php (SOCKET)

- [Update_site_registration protocol description](https://gluu.org/docs/oxd/protocol/#update-site-registration).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Update_site_registration.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $update_site_registration = new Update_site_registration();
            
            $update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $update_site_registration->setRequestOxdId($oxdId);
            $update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
            $update_site_registration->setRequestPostLogoutRedirectUri($_POST['postLogoutRedirectUrl']);
            $update_site_registration->setRequestContacts([$_POST['oxdEmail']]);
            $update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
            $update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
            $update_site_registration->setRequestScope(Oxd_RP_config::$scope);
            $update_site_registration->request();
            echo "{\"status\":\"ok\"}";
        }
        catch(Exception $e){
            echo "{\"error\":\"".$e->getMessage()."\"}";
        }
    }
    else {
        echo "{\"error\":\"Please register your site first\"";
    }

                        
```

### GetAuthorizationUrl.php (OXD-TO-HTTP)

- [Get_authorization_url protocol description](https://gluu.org/docs/oxd/protocol/#get-authorization-url).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Get_authorization_url.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_authorization_url = new Get_authorization_url();
            $get_authorization_url->setRequestOxdId($oxdId);
            $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
            $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $get_authorization_url->request($config["host"].$config[$get_authorization_url->getCommand()]);
            echo "{\"authorizationUrl\":\"".$get_authorization_url->getResponseAuthorizationUrl()."\"}";
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else {
        echo "Please register your site first";
    }
                        
```

### GetAuthorizationUrl.php (SOCKET)

- [Get_authorization_url protocol description](https://gluu.org/docs/oxd/protocol/#get-authorization-url).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Get_authorization_url.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_authorization_url = new Get_authorization_url();
            $get_authorization_url->setRequestOxdId($oxdId);
            $get_authorization_url->setRequestScope(Oxd_RP_config::$scope);
            $get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);
            $get_authorization_url->request();
            echo "{\"authorizationUrl\":\"".$get_authorization_url->getResponseAuthorizationUrl()."\"}";
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else {
        echo "Please register your site first";
    }
                        
```

### GetTokens.php (OXD-TO-HTTP)

- [Get_tokens_by_code protocol description](https://gluu.org/docs/oxd/protocol/#get-tokens-id-access-by-code).

**Example**

``` {.code}
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

```

### GetTokens.php (SOCKET)

- [Get_tokens_by_code protocol description](https://gluu.org/docs/oxd/protocol/#get-tokens-id-access-by-code).

**Example**

``` {.code}
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

```

### GetUserInfo.php (OXD-TO-HTTP)

- [Get_user_info protocol description](https://gluu.org/docs/oxd/protocol/#get-user-info).

**Example**

``` {.code}
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
                        
```

### GetUserInfo.php (SOCKET)

- [Get_user_info protocol description](https://gluu.org/docs/oxd/protocol/#get-user-info).

**Example**

``` {.code}
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
                        
```

### GetLogoutUri.php (OXD-TO-HTTP)

- [Get_logout_uri protocol description](https://gluu.org/docs/oxd/protocol/#log-out-uri).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
    if(checkOxdId())
    {
        $oxdId = getOxdId();
        try{
            $get_logout_uri = new Logout();
            $get_logout_uri->setRequestOxdId($oxdId);
            $get_logout_uri->request($config["host"].$config[$get_logout_uri->getCommand()]);
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
                        
```

### GetLogoutUri.php (SOCKET)

- [Get_logout_uri protocol description](https://gluu.org/docs/oxd/protocol/#log-out-uri).

**Example**

``` {.code}
	require_once './utils.php';
    require_once './oxdlibrary/Logout.php';
    $config = include('./oxdlibrary/oxdHttpConfig.php');
    
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
                        
```
