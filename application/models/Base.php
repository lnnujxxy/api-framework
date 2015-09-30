<?php
/**
 * 基础model
 * @author lnnujxxy@gmail.com
 * @version 1.0
 */
class BaseModel {
	public $table;

	public function __construct() {

	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function getTable() {
		return $this->table;
	}

	public function __clone() {

	}

}