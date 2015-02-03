<?php
//php cli.php request_uri="/index/cli" "aaa=111&bbb=222"

define('APPLICATION_PATH', dirname(__FILE__));

$_SERVER['env'] = 'development';

if (isset($_SERVER['env']) &&  $_SERVER['env'] === 'development') {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/development/application.ini', 'common');
} else {
	$application = new Yaf_Application(APPLICATION_PATH . '/conf/product/application.ini', 'common');
}

$application->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple());
?>