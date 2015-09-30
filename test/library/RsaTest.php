<?php
/**
 * @backupGlobals disabled
 */
class RsaTest extends PHPUnit_Framework_TestCase {
	public function testRsa() {
		$str = 'test';
		$this->assertEquals($str, Rsa::privDecrypt(Rsa::pubEncrypt($str)));
	}
}