<?php
/**
 * @name UserModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author zhouweiwei
 */
class UserModel {
    const KEY_USER_INFO = 'user_info';

	private $db = null;
    public static $instance = null; 

    private function __construct() {
    	
    }   

    public function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function setDB($db) {
        $this->db = $db;
        return $this;
    }

    public function registerUser($data) {

        $sql = "INSERT INTO 
        		wb_user(`username`, `nickname`, `password`, `salt`)
        		VALUES(?, ?, ?, ?)";
        $sth = $this->db->prepare($sql);

        $insertId = 0;
        try {
            $this->db->beginTransaction();
            $sth->execute($data);
            $insertId = $this->db->lastInsertId(); 
            $this->db->commit(); 
        } catch(PDOExecption $e) { 
            $this->db->rollback(); 
            Logger::getInstance(APPLICATION_PATH.'log', Logger::ERR)->logError($e->getMessage());
        } 
        
        return $insertId;

    }

    public function loginUser($username, $oriPassword) {

        $row = $this->getUser($username);

        if (!$row || !$row['username'] || !$row['password']) {
            return false;
        }

        if (!$this->verifyPassword($row['password'], $oriPassword, $row['salt'])) {
            return false;
        }

        return true;

    }

    public function getUser($username) {

        $sql = "SELECT `uid`, `username`, `nickname` 
                FROM wb_user WHERE `username` = ?";
        
        $sth = $this->db->prepare($sql);
        $sth->execute(array($username));

        return $sth->fetch(PDO::FETCH_ASSOC);

    }

    public function delUser($username) {

        $sql = "DELETE FROM wb_user WHERE `username` = ?";
        $sth = $this->db->prepare($sql);

        try {
            return $sth->execute(array($username));
        } catch(PDOExecption $e) { 
            Logger::getInstance(APPLICATION_PATH.'log', Logger::ERR)->logError($e->getMessage());
        } 

    }
    
    public function verifyPassword($password, $oriPassword, $salt) {

        return $password === $this->hashPassword($oriPassword, $salt);

    }

    public function hashPassword($password, $salt) {

        if (function_exists('password_hash')) {
            $options = array(
                "cost" => 10, 
                "salt" => $salt
            );
            return password_hash($password, PASSWORD_BCRYPT, $options);
        } else {
            return crypt($password, $salt);
        }

    }

    public function setSession($params) {

        Yaf_Session::getInstance()->start();
        Yaf_Session::getInstance()->set(self::KEY_USER_INFO, Crypt::execute(json_encode($params)));
    
    }

    public function getSession() {

        $crypt = Yaf_Session::getInstance()->get(self::KEY_USER_INFO);

        if ($crypt) {
            $json = Crypt::execute($crypt, 'decrypt');
            return json_decode($json, true);
        }
        return array();

    }

    public function delSession() {

        Yaf_Session::getInstance()->del(self::KEY_USER_INFO);
        unset(Yaf_Session::getInstance()->user_info);

    }

    public function isLogin() {

        $session = $this->getSession();
        return isset($session['username']) && $session['username'];

    }

    private function __clone() {

    }

    private function __wakeup() {

    }
}
