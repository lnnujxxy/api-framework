<?php
//php cli.php request_uri="/index/index" "param='value'"

define('APPLICATION_PATH', dirname(__FILE__));

$_SERVER['env'] = 'development';

if (isset($_SERVER['env']) &&  $_SERVER['env'] === 'development') {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/application.ini', 'development');
} else {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/application.ini', 'product');
}

$application->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple());
?>
