<?php
/**
 * 错误处理
 *
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */
class ErrorController extends Yaf_Controller_Abstract {

	//从2.1开始, errorAction支持直接通过参数获取异常
	public function errorAction($exception) {

		$res = [
			'errno' => -999,
			'code' => $exception->getCode(),
			'type' => get_class($exception),
			'message' => $exception->getMessage(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
		];

		if ($_SERVER['env'] === 'test') {
			echo json_encode($res);
		} else {
			error_log(json_encode($error));
		}

		return false;
	}
}
