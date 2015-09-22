<?php
/**
 * AES 加密
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */
class Aes {
	private $key; //私钥
	private $iv; //偏移量
	public function __construct($key, $iv = 0) {
		if (!$key || strlen($key) < 16) {
			die("param key invalid");
		}

		$this->key = $key;
		if ($iv == 0) {
			$this->iv = $key;
		} else {
			$this->iv = $iv;
		}
	}

	public function encrypt($data) {
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $data, MCRYPT_MODE_CBC, $this->iv));
	}

	public function decrypt($encrypted) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($encrypted), MCRYPT_MODE_CBC, $this->iv));
	}
}