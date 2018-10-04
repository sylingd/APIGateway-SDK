# APIGateway SDK

[![Packagist](https://img.shields.io/packagist/v/sylingd/apigateway-sdk.svg?style=flat-square)](https://packagist.org/packages/sylingd/apigateway-sdk)

### 环境需求

PHP 7.0+，[Protobuf扩展](https://pecl.php.net/package/protobuf)或[google/protobuf](https://packagist.org/packages/google/protobuf)

### 使用

```
composer require sylingd/apigateway-sdk
```

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
public function call(string $action, Google\Protobuf\Internal\Message $data): APIGateway\Protobuf\Response;
```

* string $action 接口名

* object(Google\Protobuf\Internal\Message) $data 接口所需的数据

例如调用user.get接口：

```php
// 例如user.get接口提供的请求Protobuf
$embed = new Proto\Api\User\Get\Request();
$embed->setId(1);

$result = $api->call('user.get', $embed);
```

### 返回内容

返回的是一个`APIGateway\Protobuf\Response`，包含以下内容：

```
/**
 * 状态码，位于APIGateway\Protobuf\ResponseCode中，分别有：
 * ResponseCode::SUCCESS 请求成功
 * ResponseCode::WRONG_REQUEST 请求参数错误，可能缺少必备参数，或使用了错误的Message
 * ResponseCode::INVALID_AUTH 签名校验失败或没有相应接口的权限
 * ResponseCode::INVALID_ACTION 请求的接口不存在
 * ResponseCode::SERVICE_UNREACHABLE 目前该服务不可用
 * ResponseCode::OTHER 其他错误
 */
echo $result->getCode();
//请求生成的唯一ID，一般不需要单独处理
echo $result->getUuid();
//文字描述的错误信息
echo $result->getError();
//请求成功时，返回接口内容
$result->getData();
```

处理返回内容的一般方式如下（需保证相应的Protobuf存在）：

```php
if ($result->getCode() === ResponseCode::SUCCESS) {
	$embed = $result->getData()->unpack();
	//下面就是你的业务代码了
	echo $embed->getName();
} else {
	//发生错误，进一步处理，如重新请求，或显示出错页面等
}
```