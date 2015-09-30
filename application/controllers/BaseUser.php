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
		$params = $this->getParams();
		$this->upgradeApi($params);

		$userModel = new UserModel();
		$content = $userModel->login();
		/*$arr = [
		'aaa' => "😂",
		'bbbb' => "http://zhidao.baidu.com/link?url=LAgH15AncbJn0jbW-dtze03-kv5Dw59wkRHL_R1wsLut5eNktHunnSwIQJks6Vg9Vy-W3E9sDwkePZ1DeIw7Ia\n",
		];
		echo json_encode($arr);exit;*/
		return $this->output(0, 'ok', $content);
	}

	public function login2Action() {
		return $this->output(0, 'ok', 'api 2');
	}
}
