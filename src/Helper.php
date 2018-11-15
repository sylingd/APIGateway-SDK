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

use Google\Protobuf\Any;

class Helper {
	const TYPE_URL_PREFIX = 'type.googleapis.com/';

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
	
	public static function unpackAny(Any $any) {
		if (extension_loaded('protobuf')) {
			try {
				return $any->unpack();
			} catch (\Exception $e) {
				$fully_qualifed_name = substr($any->getTypeUrl(), strlen(self::TYPE_URL_PREFIX));
				$type = explode('.', $fully_qualifed_name);
				$qualifed_clazz = '';
				foreach ($type as $v) {
					$qualifed_clazz .= '\\' . ucfirst($v);
				}
				if (class_exists($qualifed_clazz)) {
					$t = new $qualifed_clazz();
					unset($t);
					return $any->unpack();
				}
			}
		} else {
			$fully_qualifed_name = substr($any->getTypeUrl(), strlen(self::TYPE_URL_PREFIX));
			$pool = DescriptorPool::getGeneratedPool();
			$desc = $pool->getDescriptorByProtoName('.' . $fully_qualifed_name);
			if (is_null($desc)) {
				$type = explode('.', $fully_qualifed_name);
				$clazz = '';
				foreach ($type as $v) {
					$clazz .= '\\' . ucfirst($v);
				}
				if (class_exists($clazz)) {
					$t = new $clazz();
					unset($t);
					return $any->unpack();
				}
			} else {
				return $any->unpack();
			}
		}
		return null;
	}
}