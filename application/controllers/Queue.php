<?php
/**
 * 队列处理
 *
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */
class QueueController extends Yaf_Controller_Abstract {
	/**
	 * @uses
	 */
	public function indexAction() {
		echo "queue";
		return false;
	}

	/**
	 * 队列模糊处理图片
	 *
	 * @use: /usr/local/php/bin/php /mnt/server_size/cli.php request_uri="/queue/blurImage" env=test
	 */
	public function blurImageAction() {
		$url = 'http://biaobaiapp-circle.oss-cn-beijing.aliyuncs.com/0131B0213A9348B5AEC2B72F192B1465';
		$_file = Network::download($url);

		if ($_file) {
			$image = new Gmagick($_file);
			$oldFilename = basename($_file);
			$newFilename = md5(basename($_file));
			//模糊滤镜效果,参数为半径，标准偏差
			$image->blurimage(40, 40);
			$file = str_replace($oldFilename, $newFilename, $_file);
			$image->write($file);
			$oss = new Oss(NULL, NULL, Yaf_Registry::get('config')->application->oss_host);
			$res = $oss->uploadByFile('biaobaiapp-circle', $file, $newFilename);

			if ($res['status'] != 200) {
				var_dump("error");exit;
			}
		}

		echo date('Y-m-d H:i:s') . " ok\n";
		return false;
	}
}
