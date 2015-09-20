<?php
/**
 * 用户控制器
 *
 * @author lnnujxxy@gmail.com
 * @version 1.0
 */
class BaseUserController extends BaseController {

	/**
	 * 用户登录接口
	 */
	public function loginAction() {
		$userModel = new UserModel();
		$content = $userModel->login();
		return $this->output(0, 'ok', $content);
	}
}
