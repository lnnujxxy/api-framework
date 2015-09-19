<?php

//php cli.php request_uri="/queue/cli" "env=test&aaa=111&bbb=222"
const APPLICATION_PATH = __DIR__;

if (isset($argv[2])) {
	foreach (explode("&", $argv[2]) as $item) {
		$value = explode("=", $item);
		${$value[0]} = $value[1];
	}
}

isset($env) || $env = 'product';
$_SERVER['env'] = $env;

$application = new Yaf_Application(APPLICATION_PATH . "/conf/{$env}/application.ini", 'common');
$application->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple());