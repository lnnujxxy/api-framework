<?php

class Mysql {
	private $pdo;
	private $config;
	private $parameters;
	private $stmt;
	public  $isConnected;

	public function __construct() {
		$this->config = Yaf_Registry::get('config');
		return $this;
	}

	public function connect() {
		$config = $this->config;
		$dsn 	= 'mysql:host='.$config['db']['host'].';port='.$config['db']['port'].';dbname='.$config['db']['name'];
		$user 	= $config['db']['user'];
		$pwd 	= $config['db']['pwd'];
		$charset = $config['db']['charset'];
		try {
			$this->pdo = new PDO($dsn, $user, $pwd,
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $charset)
				);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->isConnected = true;
		} catch (PDOException $e) {
		    die('Connection failed: ' . $e->getMessage());
		}
		return $this;
	}

	private function init($query, $parameters) {
		if (!$this->isConnected) {
			$this->connect();
		}
		try {
			$this->stmt = $this->pdo->prepare($query);
			$this->bindMore($parameters);
            # Bind parameters
            if(!empty($this->parameters)) {
                foreach ($this->parameters as $param) {
                	$parameters = explode("\x7F",$param);
                	$this->stmt->bindParam($parameters[0],$parameters[1]);
                }                
            }
            # Execute SQL
            return $this->stmt->execute(); 
		}  catch(PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }

        # Reset the parameters
        $this->parameters = array();
	}

	private function bind($param, $value) {        
        $this->parameters[sizeof($this->parameters)] = ":" . $param . "\x7F" . $value;
	}
   
    private function bindMore($params) {
        if(empty($this->parameters) && is_array($params)) {
            $columns = array_keys($params);
            foreach ($columns as $i => &$column)        {
                $this->bind($column, $params[$column]);
            }
        }
    }

    public function query($query,$params = null, $fetchmode = PDO::FETCH_ASSOC) {
		$query = trim($query);

		$this->init($query,$params);

		# The first six letters of the sql statement -> insert, select, etc...
		$statement = strtolower(substr($query, 0 , 6));

		if ($statement === 'select') {
			return $this->stmt->fetchAll($fetchmode);
		} elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
			return $this->stmt->rowCount();        
		} else {
			return NULL;
		}
	}

	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}  

    public function getRow($query, $params = null,$fetchmode = PDO::FETCH_ASSOC) {                                
    	$this->init($query,$params);
    	return $this->stmt->fetch($fetchmode);                        
    }  

    public function getColumn($query, $params = null) {
    	$this->init($query,$params);
    	return $this->stmt->fetchColumn();
    }
}
?>