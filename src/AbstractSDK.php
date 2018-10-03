<?php
/**
 * APIGateway SDK
 * 
 * @author ShuangYa
 * @package APIGateway
 * @category SDK
 * @link https://www.sylingd.com/
 * @copyright Copyright (c) 2018 ShuangYa
 * @license https://www.sylibs.com/go/apigateway/license
 */
namespace APIGateway;

use APIGateway\Protobuf\Request;
use APIGateway\Protobuf\EmptyResponse;
use APIGateway\Protobuf\RequestType;
use APIGateway\Protobuf\Response;
use Google\Protobuf\Any;
use Google\Protobuf\Internal\Message;

abstract class AbstractSDK {
	protected $config;

	protected $connection = NULL;

	public function __construct($config = []) {
		// Check env
		if (!class_exists('Google\\Protobuf\\Internal\\Message')) {
			throw new Exception('Extension protobuf or package google/protobuf is required');
		}
		if (!class_exists('APIGateway\\Protobuf\\Request')) {
			throw new Exception('You must include a copy of APIGateway Protobuf');
		}
		// import classes
		$c = new EmptyResponse();
		unset($c);
		$this->config = array_merge([
			'protocol' => Helper::PROTOCOL_TCP, // TCP or HTTP
			'timeout' => 1,
			/* required for TCP */
			'addr' => '127.0.0.1',
			'port' => '9502',
			/* required for HTTP */
			'url' => 'http://127.0.0.1:9501/',
			/* APIGateway App info */
			'appid' => 1,
			'appsecret' => ''
		], $config);
	}
	abstract public function __destruct();
	/**
	 * Call APIGateway API
	 * 
	 * @access public
	 * @param string $action action name
	 * @param Message $data
	 * @return array
	 */
	abstract public function call($action, $data);
	/**
	 * Send a heartbeat package
	 */
	abstract public function heartbeat();
	/**
	 * Encode an array to a string
	 * 
	 * @access protected
	 * @param string $action action name
	 * @param Message $data
	 * @return string
	 */
	protected function packRequest($action, $data): Request {
		$uuid = $this->generateUniqueId();
		$result = new Request();
		$result->setAppid($this->config['appid']);
		$result->setAction($action);
		$result->setUuid($uuid);
		$result->setType(RequestType::SYNC);
		$any = new Any();
		$any->pack($data);
		$result->setData($any);
		$result->setSign($this->createSign($uuid, $any->getValue()));
		return $result;
	}
	/**
	 * Parse result
	 * 
	 * @access protected
	 * @param string $data
	 * @return Response
	 */
	protected function unpackResponse($data): Response {
		$result = new Response();
		$result->mergeFromString($data);
		return $result;
	}
	/**
	 * Create a UUID
	 * 
	 * @return string
	 */
	protected static function generateUniqueId() {
		static $i = 0;
		$i == 0 && $i = mt_rand(1, 0x7FFFFF);
		return sprintf('%08x%06x%04x%06x',
			//4-byte value representing the seconds since the Unix epoch
			time() & 0xFFFFFFFF,
			//3-byte machine identifier
			crc32(substr((string)gethostname(), 0, 256)) >> 8 & 0xFFFFFF,
			//2-byte process id
			getmypid() & 0xFFFF,
			//3-byte counter, starting with a random value
			$i = $i > 0xFFFFFE ? 1 : $i + 1
		);
	}
	/**
	 * Create a signature string
	 * 
	 * @access protected
	 * @param string $data
	 * @return string
	 */
	protected function createSign($uuid, $data) {
		$sign_str = strlen($data) > 18 ? substr($data, 0, 9) . substr($data, -9) : $data;
		return hash_hmac('sha1', $uuid . $sign_str, $this->config['appsecret']);
	}
}
