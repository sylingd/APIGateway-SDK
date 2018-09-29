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

use Proto\Api\Gateway\Request;
use Proto\Api\Gateway\Request\Type;
use Proto\Api\Gateway\Response;
use Google\Protobuf\Any;
use Google\Protobuf\Internal\Message;

class Native extends AbstraceSDK {
	protected $client_info = '';

	public function __construct($config = []) {
		parent::__construct($config);
		switch ($this->config['protocol']) {
			case Helper::PROTOCOL_TCP:
				if(($this->connection = socket_create(AF_INET, SOCK_STREAM,SOL_TCP)) === FALSE) {
					throw new Exception('connect failed', socket_last_error($this->connection));
				}
				socket_set_block($this->connection);
				//连接socket
				if(($result = socket_connect($this->connection, $this->config['addr'], $this->config['port'])) === FALSE){
					throw new Exception('connect failed', socket_last_error($this->connection));
				}
				break;
			case Helper::PROTOCOL_HTTP:
				$curl = curl_version();
				$this->client_info = 'APIGateway/' . Helper::SDK_VERSION . ' PHP/' . PHP_VERSION . ' Curl/' . $curl['version'];
				if (isset($curl['ssl_version'])) {
					$this->client_info .= ' ' . $curl['ssl_version'];
				}
				break;
			default:
				throw new Exception('Unknow type');
		}
	}
	public function __destruct() {
		if ($this->config['protocol'] === Helper::PROTOCOL_TCP && is_object($this->connection)) {
			socket_close($this->connection);
		}
	}
	/**
	 * Check connection status
	 * 
	 * @access private
	 */
	private function checkConnection() {
		if ($this->config['protocol'] === Helper::PROTOCOL_TCP && !is_object($this->connection)) {
				if(($this->connection = socket_create(AF_INET, SOCK_STREAM,SOL_TCP)) === FALSE) {
				throw new Exception('connect failed', socket_last_error($this->connection));
			}
			socket_set_block($this->connection);
			//连接socket
			if(($result = socket_connect($this->connection, $this->config['addr'], $this->config['port'])) === FALSE){
				throw new Exception('connect failed', socket_last_error($this->connection));
			}
		}
	}
	/**
	 * Call APIGateway API
	 * 
	 * @access public
	 * @param string $action action name
	 * @param Message $data
	 * @return array
	 */
	public function call($action, $data) {
		$this->checkConnection();
		$data = $this->packRequest($action, $data);
		if ($this->config['protocol'] === Helper::PROTOCOL_HTTP) {
			$rs = $this->fetchUrl($url, $data->serializeToString());
			if (is_int($rs)) {
				throw new Exception('connect failed', $rs);
			}
		} else {
			$send = $data->serializeToString();
			if(!socket_write($this->connection, $send, strlen($send))) {
				throw new Exception('connect failed', socket_last_error($this->connection));
			}
			//4-bytes length
			$length = socket_read($socket, 4);
			$length = unpack('N', $length);
			$rs = $out = socket_read($socket, $length);
			if (!$rs) {
				throw new Exception('connect failed', socket_last_error($this->connection));
			}
		}
		$rs = $this->unpackResponse($rs);
		return $rs;
	}
	/**
	 * Send a heartbeat package
	 */
	public function heartbeat() {
		$sendStr = 'heartbeat';
		$sendStr = pack('N', strlen($sendStr)) . $sendStr;
		$this->checkConnection();
		$this->connection->send($sendStr);
		$this->connection->recv();
	}
	/**
	 * Fetch a url by curl
	 */
	private function fetchUrl($url, $data = '') {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'User-Agent: ' . $this->client_info,
			'X-Request-Type: protobuf'
		]);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if (substr($url, 0, 8) === 'https://') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->config['timeout']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$r = curl_exec($ch);
		if ($r === FALSE) {
			return curl_errno($ch);
		}
		@curl_close($ch);
		return $r;
	}
}
