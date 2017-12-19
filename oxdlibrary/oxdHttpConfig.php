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


/*
 * configurations for oxd-php Will be use as dynamic array in project
 */
return [
    'host' => 'https://127.0.0.1:8443/',
    'get_authorization_url' => "get-authorization-url",
    'update_site_registration' => "update-site",
    'get_tokens_by_code' => "get-tokens-by-code",
    'get_user_info' => "get-user-info",
    'register_site' => "register-site",
    'setup_client' => "setup-client",
    'get_logout_uri' => "get-logout-uri",
    'get_client_token' => 'get-client-token',
    'get_access_token_by_refresh_token' => 'get-access-token-by-refresh-token',
    'uma_rs_protect' => 'uma-rs-protect',
    'uma_rs_check_access' => 'uma-rs-check-access',
    'uma_rp_get_rpt' => 'uma-rp-get-rpt',
    'uma_rp_get_claims_gathering_url' => 'uma-rp-get-claims-gathering-url'
];
?>

