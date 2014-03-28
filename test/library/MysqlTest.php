<?php
/**
* @backupGlobals disabled
*/
class MysqlTest extends PHPUnit_Framework_TestCase {
    /*
     CREATE TABLE `test` (
      `id` int(10) NOT NULL auto_increment,
      `name` varchar(5000) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB;
    */
    public function setUp() {
        $db = Mysql::getInstance()->getHashConfig()->getDB();
        $db->exec("drop table test;CREATE TABLE `test` (
      `id` int(10) NOT NULL auto_increment,
      `name` varchar(5000) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB;");
    }

    public function testGetDB() {
        $db = Mysql::getInstance()->getHashConfig()->getDB();

        $this->assertTrue(is_object($db));
    }

    public function testDB() {
        $db = Mysql::getInstance()->getHashConfig()->getDB();

        $sql = "INSERT INTO test SET `id` = ".mt_rand(0, 100).", name = 'test'";
        $sth = $db->prepare($sql);

        $db->beginTransaction(); 
        $sth->execute();
        $insertId = $db->lastInsertId('id');
        $db->commit();

        $this->assertTrue($insertId > 0);

        $sql = "SELECT count(*) AS count FROM test WHERE id = ?";
        $sth = $db->prepare($sql);
        $sth->execute(array($insertId));
        $count = $sth->fetchColumn();
        $this->assertTrue($count >= 1);

        $sql = "DELETE FROM test where id = ?";
        $sth = $db->prepare($sql);
        $sth->execute(array($insertId));
        $this->assertTrue($sth->errorCode() === '00000');
    }

}
?>