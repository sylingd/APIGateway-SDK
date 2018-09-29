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

class Coro extends AbstraceSDK {
	protected $client_info = '';

	public function __construct($config = []) {
		parent::__construct($config);
		switch ($this->config['protocol']) {
			case Helper::PROTOCOL_TCP:
				$this->connection = new \Swoole\Coroutine\Client(SWOOLE_TCP);
				if (!$this->connection->connect($this->config['addr'], $this->config['port'], $this->config['timeout'])) {
					throw new Exception('connect failed', $this->connection->errCode);
				}
				break;
			case Helper::PROTOCOL_HTTP:
				$this->client_info = 'APIGateway/' . Helper::SDK_VERSION . ' PHP/' . PHP_VERSION . ' Swoole/' . SWOOLE_VERSION . ' Coroutine';
				$this->config['url_parsed'] = parse_url($this->config['url']);
				// If is a domain, get ip address
				if (strpos($this->config['url_parsed']['host'], '::') === FALSE && !preg_match('/^(?:(?:[01]?\d?\d|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d?\d|2[0-4]\d|25[0-5])$/', $this->config['url_parsed']['host'])) {
					$this->config['url_parsed']['ip'] = \Swoole\Coroutine::gethostbyname($this->config['url_parsed']['host']);
				} else {
					$this->config['url_parsed']['ip'] = $this->config['url_parsed']['host'];
				}
				break;
			default:
				throw new Exception('Unknow type');
		}
	}
	public function __destruct() {
		if ($this->config['protocol'] === Helper::PROTOCOL_TCP && is_object($this->connection)) {
			$this->connection->close();
		}
	}
	/**
	 * Check connection status
	 * 
	 * @access private
	 */
	private function checkConnection() {
		if ($this->config['protocol'] === Helper::PROTOCOL_TCP && !$this->connection->isConnected()) {
			if (!$this->connection->connect($this->config['addr'], $this->config['port'], $this->config['timeout'])) {
				throw new Exception('connect failed', $this->connection->errCode);
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
			if ($rs === FALSE) {
				throw new Exception('connect failed', $this->connection->errCode);
			}
		} else {
			try {
				$this->connection->send($data->serializeToString());
				$rs = $this->connection->recv();
			} catch (\Exception $e) {
				throw new Exception('connect failed', $e->errorInfo());
			}
			$rs = substr($rs, 4);
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
		$this->connection = new \Swoole\Coroutine\Http\Client($this->config['url_parsed']['ip'], isset($this->config['url_parsed']['port']) ? $this->config['url_parsed']['port'] : 80);
		$this->connection->setHeaders([
			'Host' => $this->config['url_parsed']['host'],
			'User-Agent' => $this->client_info,
			'X-Request-Type' => 'protobuf'
		]);
		$rs = $this->connection->post($url, $data);
		if ($rs === FALSE) {
			throw new Exception('connect failed', $this->connection->errCode);
		}
		$this->connection->close();
		return $rs->body;
	}
}
