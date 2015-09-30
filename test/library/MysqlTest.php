<?php
/**
 * @backupGlobals disabled
 */
class MysqlTest extends PHPUnit_Framework_TestCase {
	public function testMysql() {
		$table = 'sz_test';
		$db = Mysql::getInstance(true);

		$sql = "INSERT INTO $table SET `key` = ?, `value` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array('key1', 'value1'));

		$this->assertTrue($db->errorCode() == '00000');
	}
}