# oxD-php
PHP Client Library for the [Gluu oxD server](https://oxd.gluu.org/docs/).

You can use simply Download the Release
- [Library public version 2.4.4 release](https://github.com/GluuFederation/oxd-php/releases/tag/v2.4.4).
- [Library demo version 2.4.3 release](https://github.com/GluuFederation/oxd-php/releases/tag/v2.4.3).
- [Library demo version 2.4.2 release](https://github.com/GluuFederation/oxd-php/releases/tag/v2.4.2).

**oxdphp** is a thin wrapper around the [communication protocol](https://oxd.gluu.org/docs/oxdserver/) of oxD server. This can be used to access the OpenID connect & UMA Authorization end points of the Gluu Server via the oxD RP. This library provides the function calls required by a website to access user information from a OpenID Connect Provider (OP) by using the OxD as the Relying Party (RP).

### Attention 
   - Every version library is working with corresponding version of oxD server.
   - Library will not be working if your host does not have https://.

### Prerequisites

* Install `gluu-oxd-server`

## Using the PHP Library to build your website

- [Github sources](https://github.com/GluuFederation/oxd-php)
- [Tests on github (client.example.com)](https://github.com/GluuFederation/oxd-php/tree/master/client.example.com)

# oxD-php (with namespaces) for composer

- [Github sources](https://github.com/GluuFederation/oxdphpapi)
- [Tests on github](https://github.com/GluuFederation/oxdphpapi/tree/master/protocolTests)

Every version library is working with corresponding version of server.

You can use Composer or simply Download the Release

The preferred method is via [composer](https://getcomposer.org). Follow the [installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have composer installed.
Once composer is installed, execute the following command in your project root to install this library:

* composer install `composer require "gluufederation/oxdphpapi": "2.4.2"`
* composer install `composer require "gluufederation/oxdphpapi": "2.4.3"`
* composer install `composer require "gluufederation/oxdphpapi": "2.4.4"`

- [Library public version 2.4.4 release](https://github.com/GluuFederation/oxdphpapi/releases/tag/v2.4.4).
- [Library demo version 2.4.3 release](https://github.com/GluuFederation/oxdphpapi/releases/tag/v2.4.3).
- [Library demo version 2.4.2 release](https://github.com/GluuFederation/oxdphpapi/releases/tag/v2.4.2).

### Configuration 

Configuration file is located in 'oxdlibrary/oxd-rp-settings.json' file in
distribution package.

(Save this as a file called `oxd-rp-settings.json`)

``` {.code }
{
    "op_host": "",
    "oxd_host_ip": "127.0.0.1",
    "oxd_host_port":8099,
    "authorization_redirect_uri" : "",
    "logout_redirect_uri" : "",
    "scope" : [ "openid", "profile","uma_protection","uma_authorization" ],
    "application_type" : "web",
    "redirect_uris" : [ "" ],
    "response_types" : ["code"],
    "grant_types":["authorization_code"],
    "acr_values" : [ "basic", "duo","u2f","gplus", "oxpush2" ]
}
                        
```

-   op_host - host of your gluu server url
-   oxd_host_port - port of oxd socket
-   oxd_host_ip - flag to restrict communication to localhost only (if false then it's not restricted to localhost only)

PHP classes parameters and function description for communicating with oxD. [Php library description](https://oxd.gluu.org/docs/).

Connecting to oxd server is doing via class Client\_Socket\_OXD\_RP
[Client\_Socket\_OXD\_RP.php]

## Client\_Socket\_OXD\_RP.php 

Client\_Socket\_OXD\_RP class is base class for connecting to oxd server. It is given all parameters from oxd-rp-settings.json for connection and parameters saving to static values in class Oxd\_RP\_config.


## Oxd\_RP\_config.php 

``` {.code}
class Oxd_RP_config
{
    public static $op_host;
    public static $oxd_host_ip;
    public static $oxd_host_port;
    public static $authorization_redirect_uri;
    public static $logout_redirect_uri;
    public static $scope;
    public static $application_type;
    public static $redirect_uris;
    public static $response_types;
    public static $grant_types;
    public static $acr_values;
}
                        
```

Base class for protocols is abstract Client\_OXD\_RP.php, for which
extends all protocols classes.

-   [Clinet\_OXD\_RP.php ](#Clinet\_OXD\_RP)
-   [Register\_site.php ](#Register_site)
-   [Update\_site\_registration.php](#Update_site_registration)
-   [Get\_authorization\_url.php](#Get_authorization_url)
-   [Get\_tokens\_by\_code.php](#Get_tokens_by_code)
-   [Get\_user\_info.php](#Get_user_info)
-   [Logout.php](#Logout)
-   [Uma\_rs\_protect.php](#Uma_rs_protect)
-   [Uma\_rs\_check\_access.php](#Uma_rs_check_access)
-   [Uma\_rp\_get\_rpt.php](#Uma_rp_get_rpt)
-   [Uma\_rp\_authorize\_rpt.php](#Uma_rp_authorize_rpt)
-   [Uma\_rp\_get\_gat.php](#Uma_rp_get_gat)

## Clinet\_OXD\_RP.php 

[Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Clinet_OXD_RP.html).
Client_OXD_RP class is abstract class, which extend from [Client_Socket_OXD_RP class](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Client_Socket_OXD_RP.html)..

## Register\_site.php 

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Register_site.html).
- [Register_site protocol description](https://oxd.gluu.org/docs/oxdserver/#register-site).

**Example**

``` {.code}
Register_site_test:

session_start();
session_destroy();
include_once '../Register_site.php';

$register_site = new Register_site();
$register_site->setRequestOpHost(Oxd_RP_config::$op_host);
$register_site->setRequestAcrValues(Oxd_RP_config::$acr_values);
$register_site->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
$register_site->setRequestRedirectUris(Oxd_RP_config::$redirect_uris);
$register_site->setRequestLogoutRedirectUri(Oxd_RP_config::$logout_redirect_uri);
$register_site->setRequestContacts(["test@test.test"]);
$register_site->setRequestClientJwksUri("");
$register_site->setRequestClientRequestUris([]);
$register_site->setRequestClientTokenEndpointAuthMethod("");
$register_site->setRequestGrantTypes(Oxd_RP_config::$grant_types);
$register_site->setRequestResponseTypes(Oxd_RP_config::$response_types);
$register_site->setRequestClientLogoutUri(Oxd_RP_config::$logout_redirect_uri);
$register_site->setRequestScope(Oxd_RP_config::$scope);

$register_site->request();
$_SESSION['oxd_id'] = $register_site->getResponseOxdId();

print_r($register_site->getResponseObject());

                        
```

## Update\_site\_registration.php 

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Update_site_registration.html).
- [Update_site_registration protocol description](https://oxd.gluu.org/docs/oxdserver/#update-site-registration).

**Example**

``` {.code}
Update_site_registration_test:

session_start();
include_once '../Update_site_registration.php';

$update_site_registration = new Update_site_registration();

$update_site_registration->setRequestAcrValues(Oxd_RP_config::$acr_values);
$update_site_registration->setRequestOxdId($_SESSION['oxd_id']);
$update_site_registration->setRequestAuthorizationRedirectUri(Oxd_RP_config::$authorization_redirect_uri);
$update_site_registration->setRequestRedirectUris(Oxd_RP_config::$redirect_uris);
$update_site_registration->setRequestLogoutRedirectUri(Oxd_RP_config::$logout_redirect_uri);
$update_site_registration->setRequestContacts(["test@test.test"]);
$update_site_registration->setRequestClientJwksUri("");
$update_site_registration->setRequestClientRequestUris([]);
$update_site_registration->setRequestClientTokenEndpointAuthMethod("");
$update_site_registration->setRequestGrantTypes(Oxd_RP_config::$grant_types);
$update_site_registration->setRequestResponseTypes(Oxd_RP_config::$response_types);
$update_site_registration->setRequestClientLogoutUri(Oxd_RP_config::$logout_redirect_uri);
$update_site_registration->setRequestScope(Oxd_RP_config::$scope);

$update_site_registration->request();

print_r($update_site_registration->getResponseObject());

                        
```

## Get\_authorization\_url.php 

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Get_authorization_url.html).
- [Get_authorization_url protocol description](https://oxd.gluu.org/docs/oxdserver/#get-authorization-url).

**Example**

``` {.code}
Get_authorization_url_test:
session_start();
require_once '../Get_authorization_url.php';

$get_authorization_url = new Get_authorization_url();
$get_authorization_url->setRequestOxdId($_SESSION['oxd_id']);
$get_authorization_url->setRequestAcrValues(Oxd_RP_config::$acr_values);

$get_authorization_url->request();

echo $get_authorization_url->getResponseAuthorizationUrl();
                        
```

## Get\_tokens\_by\_code.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Get_tokens_by_code.html).
- [Get_tokens_by_code protocol description](https://oxd.gluu.org/docs/oxdserver/#get-tokens-id-access-by-code).

**Example**

``` {.code}
Get_tokens_by_code_test:
session_start();
require_once '../Get_tokens_by_code.php';

$get_tokens_by_code = new Get_tokens_by_code();

$get_tokens_by_code->setRequestOxdId($_SESSION['oxd_id']);

//getting code from redirecting url, when user allowed.
$get_tokens_by_code->setRequestCode($_GET['code']);
$get_tokens_by_code->setRequestState($_GET['state']);
$get_tokens_by_code->setRequestScopes($_GET['scope']);

$get_tokens_by_code->request();
$_SESSION['id_token'] = $get_tokens_by_code->getResponseIdToken();
$_SESSION['access_token'] = $get_tokens_by_code->getResponseAccessToken();
print_r($get_tokens_by_code->getResponseObject());

```

## Get\_user\_info.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Get_user_info.html).
- [Get_user_info protocol description](https://oxd.gluu.org/docs/oxdserver/#get-user-info).

**Example**

``` {.code}
Get_user_info_test:

session_start();
require_once '../Get_user_info.php';
echo '<br/>Get_user_info <br/>';
$get_user_info = new Get_user_info();
$get_user_info->setRequestOxdId($_SESSION['oxd_id']);
$get_user_info->setRequestAccessToken($_SESSION['access_token']);
$get_user_info->request();
print_r($get_user_info->getResponseObject());
                        
```

## Logout.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Logout.html).
- [Get_logout_uri protocol description](https://oxd.gluu.org/docs/oxdserver/#log-out-uri).

**Example**

``` {.code}
Logout_test:
session_start();
require_once '../Logout.php';

$logout = new Logout();
$logout->setRequestOxdId($_SESSION['oxd_id']);
$logout->setRequestPostLogoutRedirectUri(Oxd_RP_config::$logout_redirect_uri);
$logout->setRequestIdToken($_SESSION['user_oxd_access_token']);
$logout->setRequestSessionState($_SESSION['session_states']);
$logout->setRequestState($_SESSION['states']);
$logout->request();

echo $logout->getResponseHtml();
                        
```

## Uma\_rs\_protect.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Uma_rs_protect.html).
- [Uma_rs_protect protocol description](https://oxd.gluu.org/docs/oxdserver/#uma-protect-resources).

**Example**

``` {.code}
Uma_rs_protect_test:

$uma_rs_protect = new Uma_rs_protect();
$uma_rs_protect->setRequestOxdId($register_site->getResponseOxdId());

$uma_rs_protect->addConditionForPath(["GET"],["http://vlad.umatest.com/dev/actions/view"], ["http://vlad.umatest.com/dev/actions/view"]);
$uma_rs_protect->addConditionForPath(["POST"],[ "http://vlad.umatest.com/dev/actions/add"],[ "http://vlad.umatest.com/dev/actions/add"]);
$uma_rs_protect->addConditionForPath(["DELETE"],["http://vlad.umatest.com/dev/actions/remove"], ["http://vlad.umatest.com/dev/actions/remove"]);
$uma_rs_protect->addResource('/uma/testresource');

$uma_rs_protect->request();
var_dump($uma_rs_protect->getResponseObject());

```

## Uma\_rs\_check\_access.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Uma_rs_check_access.html).
- [Uma_rs_check_access protocol description](https://oxd.gluu.org/docs/oxdserver/#uma-check-access).

**Example**

``` {.code}
Uma_rs_check_access_test:

session_start();
require_once '../Uma_rs_check_access.php';

$uma_rs_authorize_rpt = new Uma_rs_check_access();
$uma_rs_authorize_rpt->setRequestOxdId($_SESSION['oxd_id']);
$uma_rs_authorize_rpt->setRequestRpt($_SESSION['uma_rpt']);
$uma_rs_authorize_rpt->setRequestPath("/uma/testresource");
$uma_rs_authorize_rpt->setRequestHttpMethod("GET");
$uma_rs_authorize_rpt->request();

var_dump($uma_rs_authorize_rpt->getResponseObject());

$_SESSION['uma_ticket'] = $uma_rs_authorize_rpt->getResponseTicket();

```

## Uma\_rp\_get\_rpt.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Uma_rp_get_rpt.html).
- [Uma_rp_get_rpt protocol description](https://oxd.gluu.org/docs/oxdserver/).

**Example**

``` {.code}
Uma_rp_get_rpt_test:

$uma_rp_get_rpt = new Uma_rp_get_rpt();
$uma_rp_get_rpt->0setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_get_rpt->request();

var_dump($uma_rp_get_rpt->getResponseObject());

$_SESSION['uma_rpt']= $uma_rp_get_rpt->getResponseRpt();
echo $uma_rp_get_rpt->getResponseRpt();

```

## Uma\_rp\_authorize\_rpt.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Uma_rp_authorize_rpt.html).
- [Uma_rp_authorize_rpt protocol description](https://oxd.gluu.org/docs/oxdserver/).

**Example**

``` {.code}
Uma_rp_authorize_rpt_test:

session_start();
require_once '../Uma_rp_authorize_rpt.php';

$uma_rp_authorize_rpt = new Uma_rp_authorize_rpt();
$uma_rp_authorize_rpt->setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_authorize_rpt->setRequestRpt($_SESSION['uma_rpt']);
$uma_rp_authorize_rpt->setRequestTicket($_SESSION['uma_ticket']);
$uma_rp_authorize_rpt->request();

var_dump($uma_rp_authorize_rpt->getResponseObject());
                        
```

## Uma\_rp\_get\_gat.php

- [Class description](https://oxd.gluu.org/api-docs/oxd-php/2.4.4/classes/Uma_rp_get_gat.html).
- [Uma_rp_get_gat protocol description](https://oxd.gluu.org/docs/oxdserver/).

**Example**

``` {.code}
Uma_rp_get_gat_test:

$uma_rp_get_gat = new Uma_rp_get_gat();
$uma_rp_get_gat->setRequestOxdId($_SESSION['oxd_id']);
$uma_rp_get_gat->setRequestScopes(["http://photoz.example.com/dev/actions/add","http://photoz.example.com/dev/actions/view", "http://photoz.example.com/dev/actions/edit"]);
$uma_rp_get_gat->request();

var_dump($uma_rp_get_gat->getResponseObject());

$_SESSION['uma_gat']= $uma_rp_get_gat->getResponseGat();
echo $uma_rp_get_gat->getResponseGat();
                        
```