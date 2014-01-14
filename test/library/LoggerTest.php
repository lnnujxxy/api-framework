<?php
/**
* @backupGlobals disabled
*/
class LoggerTest extends PHPUnit_Framework_TestCase {
    
    public function testLog() {
        $log = new Logger(APPLICATION_PATH.'log', Logger::INFO);
        $log->logInfo('Returned a million search results');
        $log->logError('Returned a million search results', 'error');
    }
}
?>