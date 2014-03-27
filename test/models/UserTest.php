<?php
/**
* @backupGlobals disabled
*/
class UserTest extends PHPUnit_Framework_TestCase {


    public function testUser() {

        $db = Mysql::getInstance()->getHashConfig()->getDB();
        $userObj = UserModel::getInstance()->setDB($db);

        $username = 'test_username';
        $salt = 'test_salt';
        $password = 'test_password';
        
        $userObj->delUser($username);

        $data = array(
            $username,
            'test_nickname',
            UserModel::getInstance()->hashPassword($password, $salt),
            $salt
        );
        $userObj->registerUser($data);

        $row = $userObj->getUser($username);
        $this->assertEquals($row['username'], $username);

        $this->assertTrue($userObj->loginUser($username, $password));
    }

}
?>