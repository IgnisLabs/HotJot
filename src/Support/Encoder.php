<?php

namespace IgnisLabs\HotJot\Support;

use IgnisLabs\HotJot\Contracts\Support\Encoder as EncoderContract;
use IgnisLabs\HotJot\Exception\EncodingException;

class Encoder implements EncoderContract {

    /**
     * JSON encode an array or object
     * @param array|object $input
     * @return string
     */
    public function jsonEncode($input) : string {
        $json = json_encode($input);

        if ($errno = json_last_error()) {
            throw EncodingException::fromJsonError($errno);
        }

        return $json;
    }

    /**
     * Decode a JSON string into an associative array
     * @param string $json
     * @return array
     */
    public function jsonDecode(string $json) : array {
        $array = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);

        if ($errno = json_last_error()) {
            throw EncodingException::fromJsonError($errno);
        }

        return $array;
    }

    /**
     * URL-safe base64 encoding
     * @param $input
     * @return string
     */
    public function base64Encode($input) : string {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    /**
     * URL-safe base64 decoding
     * @param string $input
     * @return string
     */
    public function base64Decode(string $input) : string {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
