<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Response.proto

namespace GPBMetadata;

class Response
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Protobuf\Any::initOnce();
        \GPBMetadata\ResponseCode::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0acc010a0e526573706f6e73652e70726f746f1213415049476174657761" .
            "792e70726f746f6275661a12526573706f6e7365436f64652e70726f746f" .
            "2288010a08526573706f6e7365122f0a04636f646518022001280e32212e" .
            "415049476174657761792e70726f746f6275662e526573706f6e7365436f" .
            "6465120c0a0475756964180320012809120d0a056572726f721804200128" .
            "0912220a0464617461180f2001280b32142e676f6f676c652e70726f746f" .
            "6275662e416e794a04080110024a040805100f620670726f746f33"
        ));

        static::$is_initialized = true;
    }
}

