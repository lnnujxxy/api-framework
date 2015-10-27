<?php
/**
 * 入口文件
 *
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */
const APPLICATION_PATH = __DIR__;

if ($_SERVER['env'] === 'test') {
	$env = 'test';
} elseif ($_SERVER['env'] === 'stage') {
	$env = 'stage';
} else {
	$env = 'product';
}

if ($_REQUEST['_profile']) {
	//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
	xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);

	register_shutdown_function(function () {
		$xhprofData = xhprof_disable();

		include_once "/mnt/xhprof/xhprof_lib/utils/xhprof_lib.php";
		include_once "/mnt/xhprof/xhprof_lib/utils/xhprof_runs.php";

		$xhprofRuns = new XHProfRuns_Default();
		$xhprofRuns->save_run($xhprofData, "xhprof");
	});
}
//记录错误日志
register_shutdown_function(function () {
	$error = error_get_last();
	if ($error['type'] != E_NOTICE) {
		error_log(json_encode($error));
	}
});

function exceptionHandler($exception) {
	// these are our templates
	$traceline = "#%s %s(%s): %s(%s)";
	$msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

	// alter your trace as you please, here
	$trace = $exception->getTrace();
	foreach ($trace as $key => $stackPoint) {
		// I'm converting arguments to their type
		// (prevents passwords from ever getting logged as anything other than 'string')
		$trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
	}

	// build your tracelines
	$result = array();
	foreach ($trace as $key => $stackPoint) {
		$result[] = sprintf(
			$traceline,
			$key,
			$stackPoint['file'],
			$stackPoint['line'],
			$stackPoint['function'],
			implode(', ', $stackPoint['args'])
		);
	}
	// trace always ends with {main}
	$result[] = '#' . ++$key . ' {main}';

	// write tracelines into main template
	$msg = sprintf(
		$msg,
		get_class($exception),
		$exception->getMessage(),
		$exception->getFile(),
		$exception->getLine(),
		implode("\n", $result),
		$exception->getFile(),
		$exception->getLine()
	);

	// log or echo as you please
	error_log($msg);
}
set_exception_handler("exceptionHandler");

$application = new Yaf_Application(APPLICATION_PATH . "/conf/{$env}/application.ini", 'common');

$application->bootstrap()->run();