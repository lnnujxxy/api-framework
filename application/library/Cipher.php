<?php

class Cipher {
    private $securekey, $iv;
    public function __construct($key) {
    	if (!function_exists('mcrypt_create_iv')) {
    		throw new Exception('Please install mcrypt extension!');
    	}
        $this->securekey = hash('sha256',$key,TRUE);
        $this->iv = mcrypt_create_iv(32);
    }

    public function encrypt($input) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }

    public function decrypt($input) {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }
}

$key = '#$&G111';
$cipher = new Cipher($key);
$str = '1234';
$encryptStr = $cipher->encrypt($str);
var_dump($cipher->decrypt($encryptStr));

?>