# APIGateway SDK

[![Packagist](https://img.shields.io/packagist/v/sylingd/apigateway-sdk.svg?style=flat-square)](https://packagist.org/packages/sylingd/apigateway-sdk)

### 环境需求

PHP 7.0+，[Protobuf扩展](https://pecl.php.net/package/protobuf)或[google/protobuf](https://packagist.org/packages/google/protobuf)

### 初始化

PHP SDK提供了两种协议，分别为TCP和HTTP，初始化方式有些许差别：

```php
use APIGateway\Helper;

// TCP协议
$api = Helper::create(Helper::DRIVER_NATIVE, [
	'protocol' => Helper::PROTOCOL_TCP,
	'timeout' => 1,
	'addr' => '127.0.0.1',
	'port' => '9502',
	'appidappid' => 1,
	'appsecret' => ''
]);
// HTTP协议
$api = Helper::create(Helper::DRIVER_NATIVE, [
	'protocol' => Helper::PROTOCOL_HTTP,
	'timeout' => 1,
	'url' => 'http://127.0.0.1:9501/',
	'appid' => 1,
	'appsecret' => ''
]);
```

第一个参数为驱动类型，可选：

* Helper::DRIVER_NATIVE 原生组件，TCP使用socket，HTTP使用Curl

* Helper::DRIVER_CORO 协程组件，TCP与HTTP都使用Swoole

第二个参数为配置：

* protocol 协议类型，目前支持`APIGateway::PROTOCOL_TCP`和`APIGateway::PROTOCOL_HTTP`

* timeout 连接、调用超时（注意：此参数在非协程的TCP模式下无效）

* appid 应用ID

* appsecret 应用密钥

* addr 连接地址（仅TCP）

* port 连接端口（仅TCP）

* url 链接（仅HTTP）

### 调用接口

函数原型：

```php
public function call(string $action, Google\Protobuf\Internal\Message $data): Proto\Api\Gateway\Response;
```

* string $action 接口名

* object(Google\Protobuf\Internal\Message) $data 接口所需的数据

例如调用user.get接口：

```php
// 例如user.get接口提供的请求Protobuf
$embed = new Proto\Api\User\Get\Request();
$embed->setId(1);

$api->call('user.get', $embed);
```