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

class Helper {
	const SDK_VERSION = '1.0';

	const PROTOCOL_TCP = 1;
	const PROTOCOL_HTTP = 2;

	const DRIVER_NATIVE = 1;
	const DRIVER_CORO = 2;

	public static function create($type, $config) {
		switch ($type) {
			case self::DRIVER_NATIVE:
				return new Native($config);
			case self::DRIVER_CORO:
				return new Coro($config);
		}
	}
}