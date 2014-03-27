<?php
/**
 * @name UserController
 * @author zhouweiwei
 * @desc 控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class UserController extends Yaf_Controller_Abstract {

	const SUCC_NO_REGISTER = 0;
	const ERROR_NO_REGISTER_PARAMS = 1001; 
	const ERROR_NO_REGISTER_FAILURE = 1002;
	const SUCC_MSG = 'register successful';
	const ERROR_MSG_REGISTER_PARAMS = 'register params error';
	const ERROR_MSG_REGISTER_FAILURE = 'register failure';

	const SUCC_NO_LOGIN = 0;
	const ERROR_NO_LOGIN_PARAMS = 1003;
	const ERROR_NO_LOGIN_FAILURE = 1004;
	const SUCC_MSG = 'login successful';
	const ERROR_MSG_LOGIN_PARAMS = 'login params error';
	const ERROR_MSG_LOGIN_FAILURE = 'login failure';

	/** 
	 *
     */
	public function loginAction() {

		$username = $this->getRequest()->getQuery("username", "");
		$password = $this->getRequest()->getQuery("password", "");
		
		$db = Mysql::getInstance()->connect();
		if (UserModel::getInstance()->setDB($db)->loginUser($username, $password)) {
			
		} 

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}

	public function registerAction() {
		$username = $this->getRequest()->getQuery("username", "");
		$nickname = $this->getRequest()->getQuery("nickname", "");
		$password = $this->getRequest()->getQuery("password", "");
		$salt = md5(microtime(true).mt_rand(1000000, 9999999));

		$params = array(
			'username' => $username,
			'nickname' => $nickname,
			'password' => UserModel::getInstance()->hashPassword($password, $salt),
			'salt' => $salt;
		);
		
		if (!$this->checkRegisterParams($params)) {
			return false;
		}

		$db = Mysql::getInstance()->connect();
		if (!UserModel::getInstance()->setDB($db)->registerUser($params)) {
			return false;
		} 
		return true;
	}

	private function checkLoginParams($params) {
		if (!$params['username'] || !$params['password']) {
			return false;
		}
		return true;
	}

	private function checkRegisterParams($params) {
		if (!$params['username'] || !$params['password'] || !$params['salt']) {
			return false;
		}
		return true;
	}

	private function paramsToData($params) {
		$data = array();
		foreach((array)$params as $key => $value) {
			$data[':'.$key] = $value;
		}
		return $data;
	}

}
