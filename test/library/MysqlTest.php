<?php
/**
* @backupGlobals disabled
*/
class MysqlTest extends PHPUnit_Framework_TestCase {
    /*
     CREATE TABLE `test` (
      `id` int(11) NOT NULL,
      `name` varchar(5000) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB
    */
    private $mysql;
    private $autoId;

    public function setUp() {
        $this->mysql = new Mysql();
    }

    public function testConnect() {
        $object = $this->mysql->connect();
        $this->assertEquals($object->isConnected, true);
    }

    public function testQuery() {
        $this->autoId = mt_rand(100000, 999999);
        $sql = "INSERT INTO test values(:autoId, 'tom')";
        $count = $this->mysql->query($sql, array('autoId' => $this->autoId));
        $this->assertEquals($count, 1);
        $sql = "SELECT * FROM test WHERE id = :autoId";
        $data = $this->mysql->query($sql, array('autoId' => $this->autoId));
        $this->assertGreaterThanOrEqual(count($data), 1);
    }

    public function testGetRow() {
        $sql = "SELECT * FROM test WHERE id = :autoId";
        $row = $this->mysql->getRow($sql, array('autoId'=>$this->autoId));
        $this->assertEquals(count($row), 1);
    }
}
?>