<?php
/**
 * @name UserModel
 * @desc User数据获取类, 可以访问数据库，文件，其它系统等
 * @author zhouweiwei
 */
class UserModel {
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
        $sth->execute($data);
        return $sth->errorCode() === '00000';
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
        $sql = "SELECT `username`, `nickname`, `password`, `salt` 
                FROM wb_user WHERE `username` = ?";
        
        $sth = $this->db->prepare($sql);
        $sth->execute(array($username));

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function delUser($username) {
        $sql = "DELETE FROM wb_user WHERE `username` = ?";

        $sth = $this->db->prepare($sql);
        return $sth->execute(array($username));
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

    private function __clone() {

    }

    private function __wakeup() {

    }
}
