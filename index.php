<?php
define('APPLICATION_PATH', dirname(__FILE__));
if (isset($_SERVER['env']) &&  $_SERVER['env'] === 'development') {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/application.ini', 'development');
} else {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/application.ini', 'product');
}

$application->bootstrap()->run();
?>
