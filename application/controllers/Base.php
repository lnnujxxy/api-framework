<?php
/**
 * 基础控制器
 *
 * @authaesr lnnujxxy@gmail.com
 * @version  1.0
 */

class BaseController extends Yaf_Controller_Abstract {
	/**
	 * 统一格式输出
	 *
	 * @param Int $code 返回码
	 * @param String $msg 描述
	 * @param Mixed $content 返回数据
	 *
	 * @return Json
	 */
	protected function output($code, $msg, $content = null) {
		$ret = [
			'code' => $code,
			'msg' => base64_encode($msg),
			'content' => $content,
		];

		if (is_null($ret['content'])) {
			unset($ret['content']);
		}

		if ($ret['code'] == 0) {
			unset($ret['msg']);
		}

		if ($_SERVER['env'] !== 'phpunit') {
			$this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8');
		}

		$json = json_encode($ret, JSON_UNESCAPED_UNICODE);
		//echo $json;
		$this->getResponse()->setBody($json);
		$this->getResponse()->response();
		$this->getResponse()->clearBody();
		return false;
	}

	/**
	 * 错误返回输出
	 * @param  Mixed $code 错误码
	 * @param  String $msg 描述信息
	 * @return Json
	 */
	protected function error($code, $msg) {
		$ret = [
			'code' => $code,
			'errmsg' => base64_encode($msg),
		];

		if ($_SERVER['env'] !== 'phpunit') {
			$this->getResponse()->setHeader('Content-Type', 'application/json; charset=utf-8');
		}

		$json = json_encode($ret, JSON_UNESCAPED_UNICODE);
		$this->getResponse()->setBody($json);
		$this->getResponse()->response();
		$this->getResponse()->clearBody();
		return false;
	}

	/**
	 * 根据module name判断请求来源设备
	 *
	 * @return String 设备名
	 */
	protected function getDevice() {
		if ($this->getModuleName() === Constants::MODULE_NAME_ANDROID) {
			return Constants::DEVICE_ANDROID;
		} elseif ($this->getModuleName() === Constants::MODULE_NAME_IOS) {
			return Constants::DEVICE_IOS;
		} else {
			return 'unkown';
		}
	}

	/**
	 * 升级接口
	 * @param  Array $params 参数数组
	 * @return
	 */
	protected function upgradeApi($params) {
		if (isset($params['sv']) && $params['sv'] > 1) {
			$action = $this->getRequest()->getActionName();
			$method = str_replace('Action', '', $action) . $params['sv'] . 'Action';

			if (method_exists($this, $method)) {
				$this->$method($params);
				exit;
			}
		}
	}

	/**
	 * 获取接口参数
	 *
	 * @return Array 参数数组
	 */
	protected function getParams() {
		$sv = $this->getRequest()->get('sv');
		$key = $this->getRequest()->get('ky');
		$pm = $this->getRequest()->get('pm');

		$params = ['sv' => $sv];
		//加密方式
		if ($key && $pm) {
			if (urlencode(urldecode($key)) === $key) {
				$key = urldecode($key);
			}

			$key = $_SERVER['aes_key'] = Phprsa::privDecrypt($key);

			if (urlencode(urldecode($pm)) === $pm) {
				$pm = urldecode($pm);
				$pm && $param = $this->aesDecrypt($pm, $key);
			}

			if (!$param) {
				$pm && $param = $this->aesDecrypt($pm, $key);
			}

			$_params = json_decode($param, true);
			$params = array_merge((array) $_params, $params);
		} elseif ($pm) {
			//不加密方式, json参数必须要base64
			$_params = json_decode(base64_decode($pm), true);
			$params = array_merge((array) $_params, $params);
		}

		return $params;
	}

	/**
	 * AES 加密
	 *
	 * @param String $str 未加密字符串
	 * @param String $key 加密秘钥
	 *
	 * @return String 加密字符串
	 */
	protected function aesEncrypt($str, $key) {
		$aes = new Aes($key);
		return $aes->encrypt($str);
	}

	/**
	 * AES 解密
	 *
	 * @param String $str 加密字符串
	 * @param String $key 解密秘钥
	 *
	 * @return String 解密字符串
	 */
	protected function aesDecrypt($str, $key) {
		$aes = new Aes($key);
		return $aes->decrypt($str);
	}

}