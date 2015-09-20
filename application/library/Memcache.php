<?php
/**
 * Memcache处理类
 *
 * @author lnnujxxy@gmail.com
 * @version   1.0
 *
 */
class Memcache {
	private static $hander;
	private static $instances;
	private $configs;
	private $config;

	/**
	 * 实例化
	 */
	public function __construct($key) {
		$this->configs = Yaf_Registry::get('config')->redis->toArray();
		$this->configs = $this->configs[$key];
		if (count($this->configs) === 1) {
			$this->config = $this->configs[0];
		}
		return $this;
	}

	public static function getInstance($key) {
		if (!self::$hander[$key]) {
			self::$hander[$key] = new self($key);
		}
		return self::$hander[$key];
	}

	/**
	 * 连接memcache
	 */
	public function getMecache() {
		if (!$this->config) {
			throw new Exception('redis config is empty');
		}
		$key = md5(serialize($this->config));
		try {
			if (isset(self::$instances[$key])) {
				$memcache = self::$instances[$key];
			} else {
				$memcache = new Memcache;
				$memcache->connect($this->config['host'], $this->config['port']);
				self::$instances[$key] = $memcache;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		return $memcache;
	}
}
?>

