<?php

namespace IgnisLabs\HotJot\Exception;

use Throwable;

class UnsignedTokenException extends \DomainException {
    public function __construct($message = "Token is not signed", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
