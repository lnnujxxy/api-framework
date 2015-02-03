<?php
define('APPLICATION_PATH', dirname(__FILE__).'/../');
$_SERVER['env'] = 'phpunit';
$app = new Yaf_Application(APPLICATION_PATH . '/conf/phpunit/application.ini', 'common');
$app->bootstrap();
Yaf_Registry::set('Application', $app);
?>