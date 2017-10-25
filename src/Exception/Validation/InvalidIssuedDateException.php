<?php

namespace IgnisLabs\HotJot\Exception\Validation;

class InvalidIssuedDateException extends ValidationException {
    public function __construct($message = "[iat] date is not valid", $code = 0, \Throwable $previous = null) {
        parent::__construct('iat', $message, $code, $previous);
    }
}

