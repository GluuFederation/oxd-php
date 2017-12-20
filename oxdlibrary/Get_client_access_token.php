<?php

/**
 * Gluu-oxd-library
 *
 * An open source application library for PHP
 *
 *
 * @copyright Copyright (c) 2017, Gluu Inc. (https://gluu.org/)
 * @license      MIT   License            : <http://opensource.org/licenses/MIT>
 *
 * @package      Oxd Library by Gluu
 * @category  Library, Api
 * @version   3.0.1
 *
 * @author    Gluu Inc.          : <https://gluu.org>
 * @link      Oxd site           : <https://oxd.gluu.org>
 * @link      Documentation      : <https://gluu.org/docs/oxd/3.0.1/libraries/php/>
 * @director  Mike Schwartz      : <mike@gluu.org>
 * @support   Support email      : <support@gluu.org>
 * @developer Volodya Karapetyan : <https://github.com/karapetyan88> <mr.karapetyan88@gmail.com>
 *
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017, Gluu inc, USA, Austin
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

require_once 'Client_Socket_OXD_RP.php';
require_once 'Client_OXD_RP.php';

/**
 * Client Setup_client class
 *
 * Class is connecting to oxd-server via socket, and registering site in gluu server.
 *
 * @package          Gluu-oxd-library
 * @subpackage    Libraries
 * @category      Relying Party (RP) and User Managed Access (UMA)
 * @see            Client_Socket_OXD_RP
 * @see            Client_OXD_RP
 * @see            Oxd_RP_config
 */

class Get_client_access_token extends Client_OXD_RP
{

    /**
     * request_op_host
     * @var string $request_op_host Gluu server url
     */
    private $request_op_host = null;
    /**
     * request_authorization_redirect_uri
     * @var string $request_authorization_redirect_uri Site authorization redirect uri
     */
    private $request_authorization_redirect_uri = null;
    /**
     * request_client_name
     * @var string $request_client_name OpenID provider client name
     */
    private $request_client_name = null;
    /**
     * request_post_logout_redirect_uri
     * @var string $request_post_logout_redirect_uri Site logout redirect uri
     */
    private $request_post_logout_redirect_uri = null;
    /**
     * request_application_type
     * @var string $request_application_type web or mobile
     */
    private $request_application_type = 'web';
    /**
     * request_acr_values
     * @var array $request_acr_values Gluu login acr type, can be basic, duo, u2f, gplus and etc.
     */
    private $request_acr_values = array();
    /**
     * request_client_jwks_uri
     * @var string $request_client_jwks_uri
     */
    private $request_client_jwks_uri = '';
    /**
     * request_client_token_endpoint_auth_method
     * @var string $request_client_token_endpoint_auth_method
     */
    private $request_client_token_endpoint_auth_method = '';
    /**
     * request_client_sector_identifier_uri
     * @var array $request_client_sector_identifier_uri
     */
    private $request_client_sector_identifier_uri = '';
    /**
     * request_client_request_uris
     * @var array $request_client_request_uris
     */
    private $request_client_request_uris = null;
    /**
     * request_contacts
     * @var array $request_contacts
     */
    private $request_contacts = null;
    /**
     * request_scope
     * @var array $request_scope For getting needed scopes from gluu-server
     */
    private $request_scope = array();
    /**
     * request_grant_types
     * @var array $request_grant_types OpenID Token Request type
     */
    private $request_grant_types = array();
    /**
     * request_response_types
     * @var array $request_response_types OpenID Authentication response types
     */
    private $request_response_types = array();
    /**
     * request_client_logout_uris
     * @var array $request_client_logout_uris
     */
    private $request_client_logout_uris = null;
    /**
     * request_ui_locales
     * @var array $request_ui_locales
     */
    private $request_ui_locales = null;
    /**
     * request_claims_locales
     * @var array $request_claims_locales
     */
    private $request_claims_locales = null;
    /**
     * $request_oxd_id
     * @var string $request_oxd_id Gluu server url
     */
    private $request_oxd_id;

    /**
     * request_client_id
     * @var string $request_client_id OpenID provider client id
     */
    private $request_client_id = null;

    /**
     * request_client_secret
     * @var string $request_authorization_redirect_uri OpenID provider client secret
     */
    private $request_client_secret = null;
    /**
     * Response parameter from oxd-server
     *
     * @var string $response_access_token
     */
    private $response_access_token;

    /**
     * response_scope
     * @var scopes from request's response
     */
    private $response_scope;

    /**
     * response_expires_in
     * @var expires_in from request's response
     */
    private $response_expires_in;

    /**
     * response_refresh_token
     * @var refresh_token from request's response
     */
    private $response_refresh_token;

    /**
     * returns scopes from response from get_client_access_token command
     * @return mixed
     */
    function getResponse_scope()
    {
        $this->response_scope = $this->response_object->data->scope;
        return $this->response_scope;
    }

    /**
     * returns Response_expires_in of response from get_client_access_token command
     * @return mixed
     */
    function getResponse_expires_in()
    {
        $this->response_expires_in = $this->response_object->data->expires_in;
        return $this->response_expires_in;
    }

    /**
     * returns response_refresh_token of response from get_client_access_token command
     * @return mixed
     */
    function getResponse_refresh_token()
    {
        $this->response_refresh_token = $this->response_object->data->refresh_token;
        return $this->response_refresh_token;
    }

    /**
     * setter function
     * @param $response_scope
     */
    function setResponse_scope($response_scope)
    {
        $this->response_scope = $response_scope;
    }

    /**
     * setResponse_expires_in
     * @param $response_expires_in
     */
    function setResponse_expires_in($response_expires_in)
    {
        $this->response_expires_in = $response_expires_in;
    }

    /**
     * setter function
     * @param $response_refresh_token
     */
    function setResponse_refresh_token($response_refresh_token)
    {
        $this->response_refresh_token = $response_refresh_token;
    }

    /**
     * getter function
     * @return string
     */
    function getResponse_access_token()
    {
        $this->response_access_token = $this->response_object->data->access_token;
        return $this->response_access_token;
    }

    /**
     * setter function
     * @param $response_access_token
     */
    function setResponse_access_token($response_access_token)
    {
        $this->response_access_token = $response_access_token;
    }

    /**
     * setter function
     * @return string
     */
    function getRequest_oxd_id()
    {
        return $this->request_oxd_id;
    }

    /**
     * getter function
     * @return string
     */
    function getRequest_client_id()
    {
        return $this->request_client_id;
    }

    /**
     * getRequest_client_secret
     * @return string
     */
    function getRequest_client_secret()
    {
        return $this->request_client_secret;
    }

    /**
     * setter function
     * @param $request_oxd_id
     */
    function setRequest_oxd_id($request_oxd_id)
    {
        $this->request_oxd_id = $request_oxd_id;
    }

    /**
     * setter function
     * @param $request_client_id
     */
    function setRequest_client_id($request_client_id)
    {
        $this->request_client_id = $request_client_id;
    }

    /**
     * setter function
     * @param $request_client_secret
     */
    function setRequest_client_secret($request_client_secret)
    {
        $this->request_client_secret = $request_client_secret;
    }

    /**
     * getter function
     * @return string
     */
    function getRequest_client_name()
    {
        return $this->request_client_name;
    }

    /**
     * setter function
     * @param $request_client_name
     */
    function setRequest_client_name($request_client_name)
    {
        $this->request_client_name = $request_client_name;
    }


    /**
     * Get_client_access_token constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
        if (is_array($config)) {
            Client_Socket_OXD_RP::setUrl(substr($config["host"], -1) !== '/' ? $config["host"] . "/" . $config["get_client_token"] : $config["host"] . $config["get_client_token"]);
        }
        parent::__construct();
    }

    /**
     * getter function
     * @return string
     */
    public function getRequestOpHost()
    {
        return $this->request_op_host;
    }

    /**
     * setter function
     * @param string $request_op_host
     * @return void
     */
    public function setRequestOpHost($request_op_host)
    {
        $this->request_op_host = $request_op_host;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestClientLogoutUris()
    {
        return $this->request_client_logout_uris;
    }

    /**
     * setter function
     * @param array $request_client_logout_uris
     * @return void
     */
    public function setRequestClientLogoutUris($request_client_logout_uris)
    {
        $this->request_client_logout_uris = $request_client_logout_uris;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestResponseTypes()
    {
        return $this->request_response_types;
    }

    /**
     * setter function
     * @param array $request_response_types
     * @return void
     */
    public function setRequestResponseTypes($request_response_types)
    {
        $this->request_response_types = $request_response_types;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestGrantTypes()
    {
        return $this->request_grant_types;
    }

    /**
     * setter function
     * @param array $request_grant_types
     * @return void
     */
    public function setRequestGrantTypes($request_grant_types)
    {
        $this->request_grant_types = $request_grant_types;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestScope()
    {
        return $this->request_scope;
    }

    /**
     * setter function
     * @param array $request_scope
     * @return void
     */
    public function setRequestScope($request_scope)
    {
        $this->request_scope = $request_scope;
    }

    /**
     * getter function
     * @return string
     */
    public function getRequestPostLogoutRedirectUri()
    {
        return $this->request_post_logout_redirect_uri;
    }

    /**
     * setter function
     * @param string $request_post_logout_redirect_uri
     * @return void
     */
    public function setRequestPostLogoutRedirectUri($request_post_logout_redirect_uri)
    {
        $this->request_post_logout_redirect_uri = $request_post_logout_redirect_uri;
    }

    /**
     * getter function
     * @return string
     */
    public function getRequestClientJwksUri()
    {
        return $this->request_client_jwks_uri;
    }

    /**
     * setter function
     * @param string $request_client_jwks_uri
     * @return void
     */
    public function setRequestClientJwksUri($request_client_jwks_uri)
    {
        $this->request_client_jwks_uri = $request_client_jwks_uri;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestClientSectorIdentifierUri()
    {
        return $this->request_client_sector_identifier_uri;
    }

    /**
     * setter function
     * @param array $request_client_sector_identifier_uri
     */
    public function setRequestClientSectorIdentifierUri($request_client_sector_identifier_uri)
    {
        $this->request_client_sector_identifier_uri = $request_client_sector_identifier_uri;
    }

    /**
     * getter function
     * @return string
     */
    public function getRequestClientTokenEndpointAuthMethod()
    {
        return $this->request_client_token_endpoint_auth_method;
    }

    /**
     * setter function
     * @param string $request_client_token_endpoint_auth_method
     * @return void
     */
    public function setRequestClientTokenEndpointAuthMethod($request_client_token_endpoint_auth_method)
    {
        $this->request_client_token_endpoint_auth_method = $request_client_token_endpoint_auth_method;
    }

    /**
     * setter function
     * @return array
     */
    public function getRequestClientRequestUris()
    {
        return $this->request_client_request_uris;
    }

    /**
     * setter function
     * @param array $request_client_request_uris
     * @return void
     */
    public function setRequestClientRequestUris($request_client_request_uris)
    {
        $this->request_client_request_uris = $request_client_request_uris;
    }

    /**
     * getRequestApplicationType
     * @return string
     */
    public function getRequestApplicationType()
    {
        return $this->request_application_type;
    }

    /**
     * setter function
     * @param string $request_application_type
     * @return void
     */
    public function setRequestApplicationType($request_application_type = 'web')
    {
        $this->request_application_type = $request_application_type;
    }

    /**
     * getter function
     * @return string
     */
    public function getRequestAuthorizationRedirectUri()
    {
        return $this->request_authorization_redirect_uri;
    }

    /**
     * getter function
     * @param string $request_authorization_redirect_uri
     * @return void
     */
    public function setRequestAuthorizationRedirectUri($request_authorization_redirect_uri)
    {
        $this->request_authorization_redirect_uri = $request_authorization_redirect_uri;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestAcrValues()
    {
        return $this->request_acr_values;
    }

    /**
     * getter function
     * @param array $request_acr_values
     * @return void
     */
    public function setRequestAcrValues($request_acr_values = 'basic')
    {
        $this->request_acr_values = $request_acr_values;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestContacts()
    {
        return $this->request_contacts;
    }

    /**
     * setter function
     * @param array $request_contacts
     * @return void
     */
    public function setRequestContacts($request_contacts)
    {
        $this->request_contacts = $request_contacts;
    }

    /**
     * getter function
     * @return string
     */
    public function getResponseOxdId()
    {
        $this->response_oxd_id = $this->getResponseData()->oxd_id;
        return $this->response_oxd_id;
    }

    /**
     * getter function
     * @return string
     */
    public function getResponseOpHost()
    {
        $this->response_op_host = $this->getResponseData()->op_host;
        return $this->response_op_host;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestUiLocales()
    {
        return $this->request_ui_locales;
    }

    /**
     * setter function
     * @param array $request_ui_locales
     */
    public function setRequestUiLocales($request_ui_locales)
    {
        $this->request_ui_locales = $request_ui_locales;
    }

    /**
     * getter function
     * @return array
     */
    public function getRequestClaimsLocales()
    {
        return $this->request_claims_locales;
    }

    /**
     * setter function
     * @param array $request_claims_locales
     */
    public function setRequestClaimsLocales($request_claims_locales)
    {
        $this->request_claims_locales = $request_claims_locales;
    }

    /**
     * Protocol command to oxd server
     * @return void
     */
    public function setCommand()
    {
        $this->command = 'get_client_token';
    }


    /**
     * Protocol parameter to oxd server
     * @return void
     */
    public function setParams()
    {
        $this->params = array(
            "authorization_redirect_uri" => $this->getRequestAuthorizationRedirectUri(),
            "op_host" => $this->getRequestOpHost(),
            "post_logout_redirect_uri" => $this->getRequestPostLogoutRedirectUri(),
            "application_type" => $this->getRequestApplicationType(),
            "response_types" => $this->getRequestResponseTypes(),
            "grant_types" => $this->getRequestGrantTypes(),
            "scope" => $this->getRequestScope(),
            "acr_values" => $this->getRequestAcrValues(),
            "client_name" => $this->getRequest_client_name(),
            "client_jwks_uri" => $this->getRequestClientJwksUri(),
            "client_token_endpoint_auth_method" => $this->getRequestClientTokenEndpointAuthMethod(),
            "client_request_uris" => $this->getRequestClientRequestUris(),
            "client_sector_identifier_uri" => $this->getRequestClientSectorIdentifierUri(),
            "contacts" => $this->getRequestContacts(),
            "ui_locales" => $this->getRequestUiLocales(),
            "claims_locales" => $this->getRequestClaimsLocales(),
            "oxd_id" => $this->getRequest_oxd_id(),
            "client_id" => $this->getRequest_client_id(),
            "client_secret" => $this->getRequest_client_secret(),
            "client_frontchannel_logout_uris" => $this->getRequestClientLogoutUris(),
            "claims_redirect_uri" => $this->getRequestClaimsRedirectUri(),
            "oxd_rp_programming_language" => 'php'
        );
    }

    /**
     * request_claims_redirect_uris
     * @var request_claims_redirect_uris
     */
    private $request_claims_redirect_uris;

    /**
     * getter function
     * @return request_claims_redirect_uris
     */
    public function getRequestClaimsRedirectUri()
    {
        return $this->request_claims_redirect_uris;
    }

    /**
     * setter function
     * @param $request_claims_redirect_uris
     */
    public function setRequestClaimsRedirectUri($request_claims_redirect_uris)
    {
        $this->request_claims_redirect_uris = $request_claims_redirect_uris;
    }

}