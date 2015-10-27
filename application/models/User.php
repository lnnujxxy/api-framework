<?php
/**
 * User数据获取类, 可以访问数据库，文件，其它系统等
 * @author lnnujxxy@gmail.com
 * @version 1.0
 */
class UserModel extends BaseModel {

	public function __construct($db) {
		$this->table = 'sz_user';
		parent::__construct($db);
	}

	public function login($username, $oriPassword) {
		$row = $this->getByUsername($username);
		if (!$row) {
			return false;
		}

		if (!$this->verifyPassword($row['password'], $oriPassword, $row['salt'])) {
			return false;
		}

		return $row;
	}

	public function getByUsername($nick) {
		$sql = "SELECT `uid`, `nick` FROM " . $this->getTable() . " WHERE `nick` = ?";
		$sth = $this->db->prepare($sql);
		$sth->execute(array($nick));
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public function verifyPassword($password, $oriPassword, $salt) {
		return $password === $this->hashPassword($oriPassword, $salt);
	}

	public function hashPassword($password, $salt) {
		$options = array(
			"cost" => 4, //这个值越大，安全性越高，性能相对变慢，目前设置4
			"salt" => $salt,
		);
		return password_hash($password, PASSWORD_BCRYPT, $options);
	}

}
