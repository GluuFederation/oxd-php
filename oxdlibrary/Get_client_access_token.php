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

	/**
	 * Client Get_client_access_token class
	 *
	 * Class is connecting to oxd-server via socket, and registering site in gluu server.
	 *
	 * @package		  Gluu-oxd-library
	 * @subpackage	Libraries
	 * @category	Relying Party (RP) and User Managed Access (UMA)
	 * @see	        Client_Socket_OXD_RP
	 * @see	        Client_OXD_RP
	 * @see	        Oxd_RP_config
	 */
	require_once 'Client_Socket_OXD_RP.php';
	require_once 'Client_OXD_RP.php';
        
	class Get_client_access_token extends Client_OXD_RP
	{
            
            /**
	     * @var string $request_op_host                         Gluu server url
	     */
	    private $request_op_host = null;
	    
	    /**
	     * @var array $request_scope                            For getting needed scopes from gluu-server
	     */
	    private $request_scope = array();
	    
            /**
	     * @var string $request_op_discovery_path
	     */
	    private $request_op_discovery_path = null;
            
	    /**
	     * @var string $request_client_id                       OpenID provider client id
	     */
	    private $request_client_id = null;
            
	    /**
	     * @var string $request_authorization_redirect_uri      OpenID provider client secret
	     */
	    private $request_client_secret = null;
            /**
	     *
	     * @var string $response_access_token
	     */
            private $response_access_token;
            /**
	     *
	     * @var string $response_access_token
	     */
            private $response_scope;
            /**
	     *
	     * @var number $response_expires_in
	     */
            private $response_expires_in;
            /**
	     *
	     * @var string $response_refresh_token
	     */
            private $response_refresh_token;
            
            /**
	     * @return	string
	     */
            function getResponse_scope() {
                $this->response_scope = $this->response_object->data->scope;
                return $this->response_scope;
            }
            
            /**
	     * @return	number
	     */
            function getResponse_expires_in() {
                $this->response_expires_in = $this->response_object->data->expires_in;
                return $this->response_expires_in;
            }
            
            /**
	     * @return	string
	     */
            function getResponse_refresh_token() {
                $this->response_refresh_token = $this->response_object->data->refresh_token;
                return $this->response_refresh_token;
            }
            
            /**
	     * @return	string
	     */
            function getResponse_access_token() {
                $this->response_access_token = $this->response_object->data->access_token;
                return $this->response_access_token;
            }
            
            /**
	     * @return	string
	     */
            function getRequest_client_id() {
                return $this->request_client_id;
            }
            
            /**
	     * @return	string
	     */
            function getRequest_client_secret() {
                return $this->request_client_secret;
            }
            
            /**
             * @param string $request_client_id
	     * @return	void
	     */
            function setRequest_client_id($request_client_id) {
                $this->request_client_id = $request_client_id;
            }
            
            /**
             * @param string $request_client_secret
	     * @return	void
	     */
            function setRequest_client_secret($request_client_secret) {
                $this->request_client_secret = $request_client_secret;
            }
            
            /**
	     * @return	array
	     */
            function getRequest_scope() {
                return $this->request_scope;
            }
            
            /**
	     * @return	string
	     */
            function getRequest_op_discovery_path() {
                return $this->request_op_discovery_path;
            }
            
            /**
             * @param array $request_scope
	     * @return	void
	     */
            function setRequest_scope($request_scope) {
                $this->request_scope = $request_scope;
            }
            
            /**
             * @param string $request_op_discovery_path
	     * @return	void
	     */
            function setRequest_op_discovery_path($request_op_discovery_path) {
                $this->request_op_discovery_path = $request_op_discovery_path;
            }

                        
            /**
	     * @return string
	     */
	    public function getRequestOpHost()
	    {
	        return $this->request_op_host;
	    }
	
	    /**
	     * @param string $request_op_host
	     * @return void
	     */
	    public function setRequestOpHost($request_op_host)
	    {
	        $this->request_op_host = $request_op_host;
	    }
                        	    /**
	     * Constructor
	     *
             * @param array $https_extension_config
	     * @return	void
	     */
	    public function __construct($https_extension_config = null)
	    {
                if(is_array($https_extension_config)){
                    Client_Socket_OXD_RP::setUrl(substr($https_extension_config["host"], -1) !== '/'?$https_extension_config["host"]."/".$https_extension_config["get_client_token"]:$https_extension_config["host"].$https_extension_config["get_client_token"]);
                }
	        parent::__construct(); // TODO: Change the autogenerated stub
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
	            "op_host" => $this->getRequestOpHost(),
	            "scope" => $this->getRequest_scope(),
	            "client_id"=> $this->getRequest_client_id(),
                    "client_secret"=>$this->getRequest_client_secret(),
                    "op_discovery_path"=>$this->getRequest_op_discovery_path()
	        );
	    }
	
	}
