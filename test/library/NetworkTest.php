<?php
/**
 * @backupGlobals disabled
 */
class NetworkTest extends PHPUnit_Framework_TestCase {
	public function testDownload() {
		$url = 'http://biaobaiapp-circle.oss-cn-beijing.aliyuncs.com/0131B0213A9348B5AEC2B72F192B1465';
		$ret = Network::download($url);
		$this->assertTrue($ret != false);
	}
}