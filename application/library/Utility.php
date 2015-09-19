<?php
/**
 * 通用函数封装
 *
 */
class Utility {

	public static function microtime() {
		return intval(microtime(true) * 1000);
	}

	public static function arrayOrderBy() {
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row) {
					$tmp[$key] = $row[$field];
				}

				$args[$n] = $tmp;
			}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}

	/**
	 * @desc:按照字符编码截取汉字，中文按两个宽度，英文一个宽度
	 */
	public static function utfSubstr($str, $position, $length, $type = 0) {
		$startPos = strlen($str);
		$startByte = 0;
		$endPos = strlen($str);
		$count = 0;
		for ($i = 0, $len = strlen($str); $i < $len; $i++) {
			if ($count >= $position && $startPos > $i) {
				$startPos = $i;
				$startByte = $count;
			}
			if (($count - $startByte) >= $length) {
				$endPos = $i;
				break;
			}
			$value = ord($str[$i]);
			if ($value > 127) {
				$count++;
				if ($value >= 192 && $value <= 223) {
					$i++;
				} elseif ($value >= 224 && $value <= 239) {
					$i = $i + 2;
				} elseif ($value >= 240 && $value <= 247) {
					$i = $i + 3;
				} else {
					//logger
				}
				//else return self::raiseError("\"$str\" Not a UTF-8 compatible string", 0, __CLASS__, __METHOD__, __FILE__, __LINE__);
			}
			$count++;
		}
		if ($type == 1 && ($endPos - 6) > $length) {
			return substr($str, $startPos, $endPos - $startPos) . "...";
		} else {
			return substr($str, $startPos, $endPos - $startPos);
		}
	}

	public static function utf8_strlen($str) {
		$count = 0;
		for ($i = 0; $i < strlen($str); $i++) {
			$value = ord($str[$i]);
			if ($value > 127) {
				$count++;
				if ($value >= 192 && $value <= 223) {
					$i++;
				} elseif ($value >= 224 && $value <= 239) {
					$i = $i + 2;
				} elseif ($value >= 240 && $value <= 247) {
					$i = $i + 3;
				} else {
					die('Not a UTF-8 compatible string');
				}

			}
			$count++;
		}
		return $count;
	}

	public static function utf8Strlen($string) {
		// 将字符串分解为单元
		preg_match_all("/./us", $string, $match);
		// 返回单元个数
		return count($match[0]);
	}

	public static function utf8Substr($string, $length) {
		// 将字符串分解为单元
		preg_match_all("/./us", $string, $match);
		// 返回单元个数
		return join('', array_slice($match[0], 0, $length));
	}

	public static function debug($msg) {
		if ($_SERVER['env'] != 'product') {
			Logger::getInstance(Yaf_Registry::get('config')->application->logdir, Logger::DEBUG, 'debug')->logDEBUG($msg);
		}
	}

	public static function log($name, $msg) {
		Logger::getInstance(Yaf_Registry::get('config')->application->logdir, Logger::DEBUG, $name)->logInfo($msg);
	}

	public static function errorlog($msg) {
		Logger::getInstance(Yaf_Registry::get('config')->application->logdir, Logger::ERR, 'error')->logError($msg);
	}

	public static function logError($errno, $errstr, $errfile, $errline) {
		self::logException(new ErrorException($errstr, $errno, 1, $errfile, $errline));
	}

	public static function logException(Exception $e) {
		$log = sprintf("%s:%d %s (%d) [%s]\n", $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
		Utility::errorlog($log);
	}

	public static function baseEncode($val, $base = 36, $chars = '0123456789abcdefghijklmnopqrstuvwxyz') {
		if (!isset($base)) {
			$base = strlen($chars);
		}

		$str = '';
		do {
			$m = bcmod($val, $base);
			$str = $chars[$m] . $str;
			$val = bcdiv(bcsub($val, $m), $base);
		} while (bccomp($val, 0) > 0);
		return $str;
	}

	public static function baseDecode($str, $base = 36, $chars = '0123456789abcdefghijklmnopqrstuvwxyz') {
		if (!isset($base)) {
			$base = strlen($chars);
		}

		$len = strlen($str);
		$val = 0;
		$arr = array_flip(str_split($chars));
		for ($i = 0; $i < $len; ++$i) {
			$val = bcadd($val, bcmul($arr[$str[$i]], bcpow($base, $len - $i - 1)));
		}
		return $val;
	}

	/**
	 * 替换说明文本中变量
	 *
	 * @param String $text 文本字符串
	 * @param Array $data map数组
	 *
	 * @return String 替换后字符串
	 */
	public static function replaceText($text, $data) {
		return str_replace(array_keys($data), array_values($data), $text);
	}

	public static function isUnit() {
		return $_REQUEST['unit'] || $_SERVER['phpunit'] ? true : false;
	}

}
