<?php
/**
 * @name UserController
 * @author zhouweiwei
 * @desc Android 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class UserController extends BaseUserController {

	public function registerAction() {
		$userModel = new UserModel();
		echo "Ios " . $userModel->register();
		return false;
	}
	
}