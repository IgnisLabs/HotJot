<?php

namespace IgnisLabs\HotJot\Contracts\Support;

interface Encoder {

    /**
     * JSON encode an array or object
     * @param array|object $input
     * @return string
     */
    public function jsonEncode($input) : string;

    /**
     * Decode a JSON string into an associative array
     * @param string $json
     * @return array
     */
    public function jsonDecode(string $json) : array;

    /**
     * URL-safe base64 encoding
     * @param $input
     * @return string
     */
    public function base64Encode($input) : string;

    /**
     * URL-safe base64 decoding
     * @param string $input
     * @return string
     */
    public function base64Decode(string $input) : string;
}
