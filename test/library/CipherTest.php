<?php
/**
* @backupGlobals disabled
*/
class CipherTest extends PHPUnit_Framework_TestCase {
    private $key = '1#2&NYa*sd(';
    public function testCipher() {
    	$cipher = new Cipher($this->key);
    	$str = '1234';
    	$encryptStr = $cipher->encrypt($str);
    	$this->assertEquals($cipher->decrypt($encryptStr), $str);
    }
}
?>