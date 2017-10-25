<?php

namespace IgnisLabs\HotJot\Exception\Validation;

class TokenExpiredException extends ValidationException {
    public function __construct($message = "The token is expired", $code = 0, \Throwable $previous = null) {
        parent::__construct('exp', $message, $code, $previous);
    }
}

