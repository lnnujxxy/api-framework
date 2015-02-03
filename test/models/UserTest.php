<?php
/**
* @backupGlobals disabled
*/
class UserTest extends PHPUnit_Framework_TestCase {


    public function testUser() {

        $userModel = new UserModel();

        $this->assertEquals($userModel->login(), 'login');

        $this->assertTrue($userModel->register() == 'register');
    }

}
?>