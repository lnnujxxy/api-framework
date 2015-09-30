<?php
/**
 * Mysql处理类
 *
 * @author lnnujxxy@gmail.com
 * @version  1.0
 */
class Mysql {
	private $pdo;
	private $config;

	public static $instances = [];

	public function __construct() {
		$this->configs = Yaf_Registry::get('config')->db->toArray();
	}

	public static function getInstance($isMaster = false) {
		if (!self::$instances[$isMaster]) {
			self::$instances[$isMaster] = (new Mysql())->getMSConfig($isMaster)->getDB();
		}

		return self::$instances[$isMaster];
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

	/**
	 * 连接数据库返回PDO
	 * @return PDO
	 */
	public function getDB() {

		$config = $this->config;
		$dsn = 'mysql:host=' . $config['host'] . ';port=' . $config['port'] . ';dbname=' . $config['name'];
		$user = $config['user'];
		$pwd = $config['pwd'];
		$charset = $config['charset'];

		try {
			$this->pdo = new PDO($dsn, $user, $pwd,
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset)
			);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e) {
			Logger::getInstance(APPLICATION_PATH . 'log', Logger::ERR)->logError($e->getMessage(), Constant::MYSQL_EXCEPTION_CODE);
		}
		return $this->pdo;
	}

	private function __clone() {

	}

	private function __wakeup() {

	}
}
?>