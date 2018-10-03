<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Response.proto

namespace APIGateway\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>APIGateway.protobuf.Response</code>
 */
class Response extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.ResponseCode code = 2;</code>
     */
    private $code = 0;
    /**
     * Generated from protobuf field <code>string uuid = 3;</code>
     */
    private $uuid = '';
    /**
     * Generated from protobuf field <code>string error = 4;</code>
     */
    private $error = '';
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
     *     @type int $code
     *     @type string $uuid
     *     @type string $error
     *     @type \Google\Protobuf\Any $data
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Response::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.ResponseCode code = 2;</code>
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>.APIGateway.protobuf.ResponseCode code = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkEnum($var, \APIGateway\Protobuf\ResponseCode::class);
        $this->code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string uuid = 3;</code>
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Generated from protobuf field <code>string uuid = 3;</code>
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
     * Generated from protobuf field <code>string error = 4;</code>
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Generated from protobuf field <code>string error = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setError($var)
    {
        GPBUtil::checkString($var, True);
        $this->error = $var;

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

