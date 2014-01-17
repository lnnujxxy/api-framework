<?php
/**
* @backupGlobals disabled
*/
/*
class SimpleSSDBTest extends PHPUnit_Framework_TestCase {
    private $ssdb;

    public function setUp() {
        $this->ssdb = new SimpleSSDB('localhost', 8888);
        if (!$this->ssdb) {
            return;
        }
    }

    public function testSSDB() {
        $this->ssdb->set('foo', 'bar');
        $this->assertEquals($this->ssdb->get('foo'), 'bar');
        $this->ssdb->del('foo');
        $this->assertNull($this->ssdb->get('foo'));
        $this->ssdb->hset('test', 'a', 1);
        $this->assertEquals($this->ssdb->hget('test', 'a'), 1);
        $this->ssdb->hdel('test', 'a');
        $this->assertNull($this->ssdb->hget('test', 'a'));
        $this->ssdb->zset('set', 'a', 1);
        $this->assertEquals($this->ssdb->zget('set', 'a'), 1);
        $this->ssdb->zdel('set', 'a');
        $this->assertNull($this->ssdb->zget('test', 'a'));
    }
}
*/
?>