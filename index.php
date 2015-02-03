<?php
define('APPLICATION_PATH', dirname(__FILE__));
//这个值可以从服务器配置中读取到
$_SERVER['env'] = 'development';

if (isset($_SERVER['env']) &&  $_SERVER['env'] === 'development') {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/development/application.ini', 'common');
} else {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/product/application.ini', 'common');
}

$application->bootstrap()->run();
?>