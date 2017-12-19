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

require_once 'Client_OXD_RP.php';

/**
 * Client authorization class
 *
 * Class is connecting to oxd-server via socket, and getting authorization url from gluu-server.
 *
 * @package          Gluu-oxd-library
 * @subpackage    Libraries
 * @category      Relying Party (RP) and User Managed Access (UMA)
 * @see            Client_Socket_OXD_RP
 * @see            Client_OXD_RP
 * @see            Oxd_RP_config
 */
class Get_authorization_url extends Client_OXD_RP
{
    /**
     * @var string $request_oxd_id This parameter you must get after registration site in gluu-server
     */
    private $request_oxd_id = null;
    /**
     * @var array $request_scope May be skipped (by default takes scopes that was registered during register_site command)
     */
    private $request_scope = null;
    /**
     * @var array $request_acr_values It is gluu-server login parameter type
     */
    private $request_acr_values = null;
    /**
     * @var string $request_prompt Skipped if no value specified or missed. prompt=login is required if you want to force alter current user session (in case user is already logged in from site1 and site2 construsts authorization request and want to force alter current user session)
     */
    private $request_prompt = null;
    /**
     * @var string $request_prompt Hosted domain google OP parameter https://developers.google.com/identity/protocols/OpenIDConnect#hd-param
     */
    private $request_hd = null;
    /**
     * It is authorization url to gluu server.
     * After getting this parameter go to that url and you can login to gluu server, and get response about your users
     * @var string $response_authorization_url
     */
    private $response_authorization_url;
    /**
     * @var string $request_access_token access token for each request
     */
    private $request_protection_access_token;
    /**
     * custom_parameters
     * @var string $custom_parameters
     */
    private $custom_parameters = [];
    /**
     * Get Custom parameters
     *
     * @return    void
     */
    function getCustom_parameters()
    {
        return $this->custom_parameters;
    }

    /**
     * Add Custom Parameters
     *
     * @param $key
     * @param $value
     */
    function addCustom_parameters($key, $value)
    {
        $this->custom_parameters[$key] = $value;
    }

    /**
     * Get Request_protection_access_token
     *
     * @return string
     */
    function getRequest_protection_access_token()
    {
        return $this->request_protection_access_token;
    }

    /**
     * setRequest_protection_access_token
     *
     * @param string $request_protection_access_token
     */
    function setRequest_protection_access_token($request_protection_access_token)
    {
        $this->request_protection_access_token = $request_protection_access_token;
    }

    /**
     * Get_authorization_url constructor.
     * @param null $config
     */
    public function
    __construct($config = null)
    {
        if (is_array($config)) {
            Client_Socket_OXD_RP::setUrl($config["host"] . $config["get_authorization_url"]);
        }
        parent::__construct(); // TODO: Change the autogenerated stub
    }
    /**
     * getRequestOxdId
     *
     * @return string
     */
    public function getRequestOxdId()
    {
        return $this->request_oxd_id;
    }

    /**
     * setRequestOxdId
     * @param $request_oxd_id
     */
    public function setRequestOxdId($request_oxd_id)
    {
        $this->request_oxd_id = $request_oxd_id;
    }
    /**
     * getRequestAcrValues
     * @return array
     */
    public function getRequestAcrValues()
    {
        return $this->request_acr_values;
    }
    /**
     * setRequestAcrValues
     * @param array $request_acr_values
     * @return void
     */
    public function setRequestAcrValues($request_acr_values)
    {
        $this->request_acr_values = $request_acr_values;
    }
    /**
     * getRequestScope
     * @return array
     */
    public function getRequestScope()
    {
        return $this->request_scope;
    }
    /**
     * setRequestScope
     * @param array $request_scope
     */
    public function setRequestScope($request_scope)
    {
        $this->request_scope = $request_scope;
    }
    /**
     * getRequestPrompt
     * @return string
     */
    public function getRequestPrompt()
    {
        return $this->request_prompt;
    }
    /**
     * setRequestPrompt
     * @param string $request_prompt
     */
    public function setRequestPrompt($request_prompt)
    {
        $this->request_prompt = $request_prompt;
    }
    /**
     * getRequestHd
     * @return string
     */
    public function getRequestHd()
    {
        return $this->request_hd;
    }
    /**
     * setRequestHd
     * @param string $request_hd
     */
    public function setRequestHd($request_hd)
    {
        $this->request_hd = $request_hd;
    }
    /**
     * getResponseAuthorizationUrl
     * @return string
     */
    public function getResponseAuthorizationUrl()
    {
        $this->response_authorization_url = $this->getResponseData()->authorization_url;
        return $this->response_authorization_url;
    }
    /**
     * Protocol command to oxd server
     * @return void
     */
    public function setCommand()
    {
        $this->command = 'get_authorization_url';
    }
    /**
     * Protocol parameter to oxd server
     * @return void
     */
    public function setParams()
    {
        $this->params = array(
            "oxd_id" => $this->getRequestOxdId(),
            "scope" => $this->getRequestScope(),
            "acr_values" => $this->getRequestAcrValues(),
            "prompt" => $this->getRequestPrompt(),
            "hd" => $this->getRequestHd(),
            "custom_parameters" => $this->getCustom_parameters(),
            "protection_access_token" => $this->getRequest_protection_access_token()
        );
    }
}
