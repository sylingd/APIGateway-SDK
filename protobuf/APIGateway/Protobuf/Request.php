<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Request.proto

namespace APIGateway\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>APIGateway.protobuf.Request</code>
 */
class Request extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string appid = 1;</code>
     */
    private $appid = '';
    /**
     * Generated from protobuf field <code>string action = 2;</code>
     */
    private $action = '';
    /**
     * Generated from protobuf field <code>string sign = 3;</code>
     */
    private $sign = '';
    /**
     * Generated from protobuf field <code>string uuid = 4;</code>
     */
    private $uuid = '';
    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.RequestType type = 5;</code>
     */
    private $type = 0;
    /**
     * Generated from protobuf field <code>.google.protobuf.Any data = 15;</code>
     */
    private $data = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $appid
     *     @type string $action
     *     @type string $sign
     *     @type string $uuid
     *     @type int $type
     *     @type \Google\Protobuf\Any $data
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string appid = 1;</code>
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * Generated from protobuf field <code>string appid = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setAppid($var)
    {
        GPBUtil::checkString($var, True);
        $this->appid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string action = 2;</code>
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Generated from protobuf field <code>string action = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setAction($var)
    {
        GPBUtil::checkString($var, True);
        $this->action = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string sign = 3;</code>
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Generated from protobuf field <code>string sign = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setSign($var)
    {
        GPBUtil::checkString($var, True);
        $this->sign = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string uuid = 4;</code>
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Generated from protobuf field <code>string uuid = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setUuid($var)
    {
        GPBUtil::checkString($var, True);
        $this->uuid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.RequestType type = 5;</code>
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.RequestType type = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkEnum($var, \APIGateway\Protobuf\RequestType::class);
        $this->type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Any data = 15;</code>
     * @return \Google\Protobuf\Any
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Any data = 15;</code>
     * @param \Google\Protobuf\Any $var
     * @return $this
     */
    public function setData($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Any::class);
        $this->data = $var;

        return $this;
    }

}

