<?php
/**
 * @name FeedController
 * @author zhouweiwei
 * @desc 控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class FeedController extends Yaf_Controller_Abstract {

	/*
	 *
     */
	public function listAction() {
		
		if (!UserModel::getInstance()->isLogin()) {
			exit('please login!');
		}
		
		echo "list";
		return false;

	}

}
