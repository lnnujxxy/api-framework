<?php
/**
 * 基础model
 * @author lnnujxxy@gmail.com
 * @version 1.0
 */
class BaseModel {
	public $db;
	public $table;

	public function __construct($db) {
		$this->db = $db;
	}

	public function setTable($table) {
		$this->table = $table;
		return $this;
	}

	public function getTable() {
		return $this->table;
	}

	public function __clone() {

	}

}