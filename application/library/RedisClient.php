<?php
/**
 * @author lnnujxxy@gmail.com
 * @version   1.0
 *
 */
class RedisClient {
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

	/*
	 * 主从方式
	 */
	public function getMSConfig($isMaster = false) {
		if (count($this->configs) > 1) {
			if ($isMaster) {
				$this->config = $this->configs[0];
			} else {
				array_shift($this->configs);
				$this->config = $this->configs[array_rand($this->configs)];
			}
		}
		return $this;
	}

	/*
	 * Hash方式
	 */
	public function getHashConfig($hashKey = null) {
		if (count($this->configs) > 1 && $hashKey) {
			$count = count($this->configs);
			$this->config = $this->configs[$hashKey % $count];
		}
		return $this;
	}

	/**
	 * 连接redis
	 */
	public function getRedis() {
		if (!$this->config) {
			throw new Exception('redis config is empty');
		}
		$key = md5(serialize($this->config));
		try {
			if (isset(self::$instances[$key]) && $this->pingRedis(self::$instances[$key])) {
				$redis = self::$instances[$key];
			} else {
				$redis = new Redis();
				$redis->connect($this->config['host'], $this->config['port']);
				if (isset($this->config['auth'])) {
					$redis->auth($this->config['auth']);
				}
				self::$instances[$key] = $redis;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		if (!is_object($redis)) {
			throw new Exception('redis connect is failure');
		}
		return $redis;
	}

	private function pingRedis($instance) {
		if ($instance instanceof Redis) {
			return '+PONG' === $instance->ping() ? true : false;
		}
		return false;
	}
}
?>

