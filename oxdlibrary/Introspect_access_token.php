<?php
	
	/**
	 * Gluu-oxd-library
	 *
	 * An open source application library for PHP
	 *
	 *
	 * @copyright Copyright (c) 2017, Gluu Inc. (https://gluu.org/)
	 * @license	  MIT   License            : <http://opensource.org/licenses/MIT>
	 *
	 * @package	  Oxd Library by Gluu
	 * @category  Library, Api
	 * @version   3.1.2
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

	/**
	 * Client Introspect_access_token class
	 *
	 * Class is connecting to oxd-server via socket, and doing introspection of access token from gluu-server.
	 *
	 * @package		  Gluu-oxd-library
	 * @subpackage	Libraries
	 * @category	  Relying Party (RP) and User Managed Access (UMA)
	 * @see	        Client_Socket_OXD_RP
	 * @see	        Client_OXD_RP
	 * @see	        Oxd_RP_config
	 */
	require_once 'Client_OXD_RP.php';
	
	class Introspect_access_token extends Client_OXD_RP
	{
	    /**
	     * @var string $request_oxd_id                             Need to get after registration site in gluu-server
	     */
	    private $request_oxd_id = null;
	    /**
	     * @var string $request_id_token                           Access token from get_client_token command
	     */
	    private $request_access_token = null;
            
            /**
	     * @var string $response_active
	     */
	    private $response_active = null;
            
            /**
	     * @var string $response_client_id
	     */
	    private $response_client_id = null;
            
            /**
	     * @var string $response_username
	     */
	    private $response_username = null;
            
            /**
	     * @var array $response_scopes
	     */
	    private $response_scopes = null;
            
            /**
	     * @var string $response_token_type
	     */
	    private $response_token_type = null;
            
            /**
	     * @var string $response_sub
	     */
	    private $response_sub = null;
            
            /**
	     * @var string $response_aud
	     */
	    private $response_aud = null;
            
            /**
	     * @var string $response_iss
	     */
	    private $response_iss = null;
            
            /**
	     * @var string $response_exp
	     */
	    private $response_exp = null;
            
            /**
	     * @var string $response_iat
	     */
	    private $response_iat = null;
            
            /**
	     * @var array $response_acr_values
	     */
	    private $response_acr_values = null;
            
            /**
	     * @var string $response_extension_field
	     */
	    private $response_extension_field = null;
            
	    /**
	     * Constructor
	     *
	     * @return	void
	     */
	    public function __construct($https_extension_config = null)
	    {
                if(is_array($https_extension_config)){
                    Client_Socket_OXD_RP::setUrl(substr($https_extension_config["host"], -1) !== '/'?$https_extension_config["host"]."/".$https_extension_config["introspect_access_token"]:$https_extension_config["host"].$https_extension_config["introspect_access_token"]);
                }
	        parent::__construct(); // TODO: Change the autogenerated stub
	    }

            /**
	     * @return string
	     */
            function getRequest_oxd_id() {
                return $this->request_oxd_id;
            }
            /**
	     * @return string
	     */
            function getRequest_access_token() {
                return $this->request_access_token;
            }
            /**
	     * @return string
	     */
            function getResponse_active() {
                $this->response_active = $this->getResponseData()->active;
                return $this->response_active;
            }
            /**
	     * @return string
	     */
            function getResponse_client_id() {
                $this->response_client_id = $this->getResponseData()->client_id;
                return $this->response_client_id;
            }
            /**
	     * @return string
	     */
            function getResponse_username() {
                $this->response_username = $this->getResponseData()->username;
                return $this->response_username;
            }
            /**
	     * @return array
	     */
            function getResponse_scopes() {
                $this->response_scopes = $this->getResponseData()->scopes;
                return $this->response_scopes;
            }
            /**
	     * @return string
	     */
            function getResponse_token_type() {
                $this->response_token_type = $this->getResponseData()->token_type;
                return $this->response_token_type;
            }
            /**
	     * @return string
	     */
            function getResponse_sub() {
                $this->response_sub = $this->getResponseData()->sub;
                return $this->response_sub;
            }
            /**
	     * @return string
	     */
            function getResponse_aud() {
                $this->response_aud = $this->getResponseData()->aud;
                return $this->response_aud;
            }
            /**
	     * @return string
	     */
            function getResponse_iss() {
                $this->response_iss = $this->getResponseData()->iss;
                return $this->response_iss;
            }
            /**
	     * @return string
	     */
            function getResponse_exp() {
                $this->response_exp = $this->getResponseData()->exp;
                return $this->response_exp;
            }
            /**
	     * @return string
	     */
            function getResponse_iat() {
                $this->response_iat = $this->getResponseData()->iat;
                return $this->response_iat;
            }
            /**
	     * @return array
	     */
            function getResponse_acr_values() {
                $this->response_acr_values = $this->getResponseData()->acr_values;
                return $this->response_acr_values;
            }
            /**
	     * @return string
	     */
            function getResponse_extension_field() {
                $this->response_extension_field = $this->getResponseData()->extension_field;
                return $this->response_extension_field;
            }
            /**
	     * @param string $request_oxd_id
	     * @return	void
	     */
            function setRequest_oxd_id($request_oxd_id) {
                $this->request_oxd_id = $request_oxd_id;
            }
            /**
	     * @param string $request_access_token
	     * @return	void
	     */
            function setRequest_access_token($request_access_token) {
                $this->request_access_token = $request_access_token;
            }
                        
	    /**
	     * Protocol command to oxd server
	     * @return void
	     */
	    public function setCommand()
	    {
	        $this->command = 'introspect_access_token';
	    }
	    /**
	     * Protocol parameter to oxd server
	     * @return void
	     */
	    public function setParams()
	    {
	        $this->params = array(
	            "oxd_id" => $this->getRequest_oxd_id(),
	            "access_token" => $this->getRequest_access_token()
	        );
	    }
	
	}