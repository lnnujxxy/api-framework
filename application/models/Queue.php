<?php
/**
 * 队列处理函数
 * @author  lnnujxxy@gmail.com
 * @version  1.0
 */
class QueueModel {
	/**
	 * 将数据写到队列
	 * @param Array $config 队列host,port配置信息
	 * @param String $tube 队列tube
	 * @param Mixed $body 数据内容
	 */
	public static function producer($config, $tube, $body) {
		$beanstalk = new Beanstalk($config);

		$beanstalk->connect();
		$beanstalk->useTube($tube); // Begin to use tube `'flux'`.
		$beanstalk->put(
			23, // Give the job a priority of 23.
			0, // Do not wait to put job into the ready queue.
			60, // Give the job 1 minute to run.
			$body // The job's body.
		);
		$beanstalk->disconnect();
	}

	/**
	 * 消费队列中数据
	 * @param  Array $config 队列host,port配置信息
	 * @param  String $tube 队列tube
	 * @param  Callback $func 回调函数
	 */
	public static function worker($config, $tube, $func) {
		$beanstalk = new Beanstalk($config);

		$beanstalk->connect();
		$beanstalk->watch($tube);

		while (true) {
			$job = $beanstalk->reserve(); // Block until job is available.

			// Processing of the job...
			//$result = touch($job['body']);
			$result = call_user_func($func, $job['body']);

			if ($result) {
				$beanstalk->delete($job['id']);
			} else {
				$beanstalk->bury($job['id']);
			}
		}

		// When exiting i.e. on critical error conditions
		// you may also want to disconnect the consumer.
		// $beanstalk->disconnect();
	}

	/**
	 * 将保留的job放回队列中
	 * @param  Array $config 队列host,port配置信息
	 * @param  String $tube 队列tube
	 * @param  integer $n   每次唤醒数目
	 */
	public static function kick($config, $tube, $n = 100) {
		$beanstalk = new Beanstalk($config);

		$beanstalk->connect();
		$beanstalk->watch($tube);

		while ($beanstalk->peekBuried() != false) {
			$beanstalk->kick($n);
		}
	}

}