<?php
/**
 * 基础控制器
 *
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */

class BaseController extends Yaf_Controller_Abstract {
	/**
	 * 统一格式输出
	 *
	 * @param Int $errno 错误ID
	 * @param String $errmsg 错误描述
	 * @param Mixed $content 返回数据
	 *
	 * @return Json
	 */
	protected function output($errno, $errmsg, $content = null) {
		$ret = [
			'errno' => $errno,
			'errmsg' => base64_encode($errmsg),
			'content' => $content,
		];

		if (is_null($ret['content'])) {
			unset($ret['content']);
		}

		if ($ret['errno'] == 0) {
			unset($ret['errmsg']);
		}

		if ($_SERVER['env'] !== 'phpunit') {
			$this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8');
		}

		$json = json_encode($ret, JSON_UNESCAPED_UNICODE);
		$this->getResponse()->setBody($json);
		return false;
	}

	protected function error($errno, $errmsg) {
		$ret = [
			'errno' => $errno,
			'errmsg' => base64_encode($errmsg),
		];

		$json = json_encode($ret);
		exit;
	}
}