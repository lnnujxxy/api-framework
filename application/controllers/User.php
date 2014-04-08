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
	const SUCC_REGISTER_MSG = 'register successful';
	const ERROR_MSG_REGISTER_PARAMS = 'register params error';
	const ERROR_MSG_REGISTER_FAILURE = 'register failure';

	const SUCC_NO_LOGIN = 0;
	const ERROR_NO_LOGIN_PARAMS = 1003;
	const ERROR_NO_LOGIN_FAILURE = 1004;
	const SUCC_LOGIN_MSG = 'login successful';
	const ERROR_MSG_LOGIN_PARAMS = 'login params error';
	const ERROR_MSG_LOGIN_FAILURE = 'login failure';

	/** 
	 *
     */
	public function loginAction() {
		if ($this->getRequest()->getPost('doLogin', '')) {
			$username = $this->getRequest()->getPost('username', '');
			$password = $this->getRequest()->getPost('password', '');

			if (!$this->checkLoginParams(array('username'=>$username, 'password'=>$password))) {
				$msg = array(
					'errno' => self::ERROR_NO_LOGIN_PARAMS,
					'errmsg' => self::ERROR_MSG_LOGIN_PARAMS,
				);
				$this->getView()->assign('msg', $msg);
				return true;
			}

			$db = Mysql::getInstance()->getHashConfig()->getDB();
			if (!UserModel::getInstance()->setDB($db)->loginUser($username, $password)) {
				
				$msg = array(
					'errno' => self::ERROR_NO_LOGIN_FAILURE,
					'errmsg' => self::ERROR_MSG_LOGIN_FAILURE,
				);
				$this->getView()->assign('msg', $msg);

				return true;
			} else {
				$user = UserModel::getInstance()->setDB($db)->getUser($username);
				UserModel::getInstance()->setSession($user);
				$this->forward("feed", "list");
				return false;
			}
		}

		return true;
	}

	public function registerAction() {

		if ($this->getRequest()->getPost('doRegister', '')) {

			$username = $this->getRequest()->getPost('username', '');
			$nickname = $this->getRequest()->getPost('nickname', '');
			$password = $this->getRequest()->getPost('password', '');
			$salt = md5(microtime(true).mt_rand(1000000, 9999999));
			$params = array(
				'username' => $username,
				'nickname' => $nickname,
				'password' => UserModel::getInstance()->hashPassword($password, $salt),
				'salt' => $salt
			);
			
			if (!$this->checkRegisterParams($params)) {
				$msg = array(
					'errno' => self::ERROR_NO_REGISTER_PARAMS,
					'errmsg' => self::ERROR_MSG_REGISTER_PARAMS,
				);
				$this->getView()->assign('msg', $msg);
				return true;
			}
			
			$db = Mysql::getInstance()->getHashConfig()->getDB();

			if (!($params['uid'] = UserModel::getInstance()->setDB($db)->registerUser(array_values($params)))) {
				
				$msg = array(
					'errno' => self::ERROR_NO_REGISTER_FAILURE,
					'errmsg' => self::ERROR_MSG_REGISTER_FAILURE,
				);
				
				$this->getView()->assign('msg', $msg);
				return true;
			} else {
				unset($params['password'], $params['salt']);
				UserModel::getInstance()->setSession($params);
				$this->forward("feed", "list");
				return false;
			}
			
		}

		return true;

	}

	public function logoutAction() {
		
		UserModel::getInstance()->delSession();
		return false;

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

}
