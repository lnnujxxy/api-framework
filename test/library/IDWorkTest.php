<?php
/**
 * @backupGlobals disabled
 */
class IDWorkTest extends PHPUnit_Framework_TestCase {
	public function testIDWork() {
		$id = IDWork::getInstance()->nextId();
		$this->assertTrue($id > 0);
	}
}
?>