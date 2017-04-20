<?php
session_start();
require_once '../Uma_rs_protect.php';

$uma_rs_protect = new Uma_rs_protect();
$uma_rs_protect->setRequestOxdId($register_site->getResponseOxdId());

$uma_rs_protect->addConditionForPath(["GET"],["http://vlad.umatest.com/dev/actions/view"], ["http://vlad.umatest.com/dev/actions/view"]);
$uma_rs_protect->addConditionForPath(["POST"],[ "http://vlad.umatest.com/dev/actions/add"],[ "http://vlad.umatest.com/dev/actions/add"]);
$uma_rs_protect->addConditionForPath(["DELETE"],["http://vlad.umatest.com/dev/actions/remove"], ["http://vlad.umatest.com/dev/actions/remove"]);
$uma_rs_protect->addResource('/uma/testresource');

$uma_rs_protect->request();
echo '<br/>Uma_rs_protect<pre>';
var_dump($uma_rs_protect->getResponseObject());
echo '</pre><br/>';

