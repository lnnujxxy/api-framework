<?php
/**
 * @backupGlobals disabled
 */
class BeanstalkTest extends PHPUnit_Framework_TestCase {
	public function testBeanstalk() {
		// For connection options see the
		// class documentation.
		$beanstalk = new Beanstalk(array('host' => '10.170.252.252', 'port' => 11300));
		$beanstalk->connect();
		$beanstalk->useTube('flux'); // Begin to use tube `'flux'`.
		var_dump($beanstalk->peekReady());
		var_dump($beanstalk->peekDelayed());
		$beanstalk->kick(100000);
		var_dump($beanstalk->peekBuried());exit;
		// $beanstalk->put(
		// 	23, // Give the job a priority of 23.
		// 	0, // Do not wait to put job into the ready queue.
		// 	60, // Give the job 1 minute to run.
		// 	str_repeat(md5(mt_rand()), 300) // The job's body.
		// );
		// $beanstalk->disconnect();

		//
		// A sample consumer.
		//
		// $beanstalk = new Beanstalk(array('host' => '10.170.252.252', 'port' => 11300));

		// $beanstalk->connect();
		// $beanstalk->watch('flux');

		// while (true) {
		// 	$job = $beanstalk->reserve(); // Block until job is available.
		// 	// Now $job is an array which contains its ID and body:
		// 	// ['id' => 123, 'body' => '/path/to/cat-image.png']
		// 	$beanstalk->bury($job['id'], 23);
		// 	// Processing of the job...

		// 	// $result = touch($job['body']);

		// 	// if ($result) {
		// 	// 	$beanstalk->delete($job['id']);
		// 	// } else {
		// 	// 	$beanstalk->bury($job['id']);
		// 	// }
		// }

		// When exiting i.e. on critical error conditions
		// you may also want to disconnect the consumer.
		// $beanstalk->disconnect();
	}
}
?>