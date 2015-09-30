<?php
/**
 * Oss 操作类
 * @author: lnnujxxy@gmail.com
 * @version 1.0
 */
Yaf_Loader::import(APPLICATION_PATH . "/application/thirdparty/oss_php_sdk/sdk.class.php");

class Oss {
	private $oss_sdk_service;

	public function __construct($access_id = NULL, $access_key = NULL, $hostname = NULL) {
		$this->oss_sdk_service = new ALIOSS($access_id, $access_key, $hostname);
		//设置是否打开curl调试模式
		$this->oss_sdk_service->set_debug_mode(FALSE);
	}

	public function createBucket($bucket) {
		$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE;
		$response = $this->oss_sdk_service->create_bucket($bucket, $acl);
		return $this->format($response);
	}

	public function setBucketAcl($bucket, $acl) {
		$response = $this->oss_sdk_service->set_bucket_acl($bucket, $acl);
		return $this->format($response);
	}

	public function getBucketAcl($bucket) {
		$options = array(
			ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
		);

		$response = $this->oss_sdk_service->get_bucket_acl($bucket, $options);
		return $this->format($response);
	}

	public function listBucket() {
		$response = $this->oss_sdk_service->list_bucket();
		return $this->format($response);
	}

	public function setBucketCors($bucket) {
		$options = array(
			ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
		);
		$response = $this->oss_sdk_service->get_bucket_acl($bucket, $options);
		$cors_rule[ALIOSS::OSS_CORS_ALLOWED_HEADER] = array("x-oss-test");
		$cors_rule[ALIOSS::OSS_CORS_ALLOWED_METHOD] = array("GET");
		$cors_rule[ALIOSS::OSS_CORS_ALLOWED_ORIGIN] = array("http://api.biaobaiapp.com");
		$cors_rule[ALIOSS::OSS_CORS_EXPOSE_HEADER] = array("x-oss-test1");
		$cors_rule[ALIOSS::OSS_CORS_MAX_AGE_SECONDS] = 10;
		$cors_rules = array($cors_rule);

		$response = $this->oss_sdk_service->set_bucket_cors($bucket, $cors_rules);
		return $this->format($response);
	}

	//通过路径上传文件
	public function uploadByFile($bucket, $file, $object) {
		$options = array(
			'content' => file_get_contents($file),
			'length' => filesize($file),
		);
		$response = $this->oss_sdk_service->upload_file_by_content($bucket, $object, $options);
		return $this->format($response);
	}

	public function getSignUrl($bucket, $filePath) {
		$object = basename($filePath);
		$timeout = 86400 * 365;
		return $this->oss_sdk_service->get_sign_url($bucket, $object, $timeout);
	}

	//格式化返回结果
	public function format($response) {
		return [
			'status' => $response->status,
			'body' => $response->body,
			'header' => $response->header,
		];
	}
}
