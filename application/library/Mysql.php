<?php

class Mysql {
	private $pdo;
	private static $instance;

	public function __construct() {
		$config = Yaf_Registry::get('config');
		$dsn 	= 'mysql:host='.$config['db']['host'].';port='.$config['db']['port'].';dbname=test';
		$user 	= $config['db']['user'];
		$pwd 	= $config['db']['pwd'];
		$charset = $config['db']['charset'];
		try {
		    $this->pdo = new PDO($dsn, $user, $pwd,
		    	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset)
		    	);
		    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
		    echo 'Connection failed: ' . $e->getMessage();
		}
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new Mysql();
		}
		return self::$instance;
	}

	public function getPDO() {
		return $this->pdo;
	}
}
?>