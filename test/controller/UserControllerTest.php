<?php
/**
* @backupGlobals disabled
*/
Class UserControllerTest extends PHPUnit_Framework_TestCase {
 
    private $application = NULL;

    // 初始化实例化YAF应用，YAF application只能实例化一次
    public function __construct() {
        if (!$this->application = Yaf_Registry::get('Application')) {
            $this->application = new Yaf_Application(APPLICATION_PATH . '/conf/phpunit/application.ini', 'common');
            Yaf_Registry::set('Application', $this->application);
        }
    }
 
    // 创建一个简单请求，并利用调度器接受Repsonse信息，指定分发请求。
    private function requestActionAndParseBody($action, $params=array()) {
        $request = new Yaf_Request_Simple("CLI", "Ios", "User", $action, $params);
        $response = $this->application->getDispatcher()->returnResponse(true)
            ->dispatch($request);
        return $response->getBody();
    }
 
    public function testUserAction() {  
        $response = $this->requestActionAndParseBody('login');
        $arr = json_decode($response, true);
        $this->assertEquals($arr['errno'], 0);
    }
}
