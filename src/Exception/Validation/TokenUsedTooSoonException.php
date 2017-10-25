<?php

namespace IgnisLabs\HotJot\Exception\Validation;

class TokenUsedTooSoonException extends ValidationException {
    public function __construct($message = "The token used before [nbf]", $code = 0, \Throwable $previous = null) {
        parent::__construct('nbf', $message, $code, $previous);
    }
}

