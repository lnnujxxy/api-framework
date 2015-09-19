<?php
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

$application = new Yaf_Application(APPLICATION_PATH . "/conf/{$env}/application.ini", 'common');

$application->bootstrap()->run();