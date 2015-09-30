<?php
/**
 * @backupGlobals disabled
 */
class RedisClientTest extends PHPUnit_Framework_TestCase {
	private $redis;

	public function testHashRedis() {
		$this->redis = RedisClient::getInstance("default")->getHashConfig("hash")->getRedis();
		$this->redis->set("foo", "bar");
		$this->assertTrue($this->redis->get("foo") == "bar");
	}

	public function testMSRedis() {
		$this->redis = RedisClient::getInstance("default")->getMSConfig(true)->getRedis();
		$this->redis->set("foo1", "bar1");
		$this->assertTrue($this->redis->get("foo1") == "bar1");
	}

}
?>