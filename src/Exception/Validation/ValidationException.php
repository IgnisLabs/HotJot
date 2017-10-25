<?php

namespace IgnisLabs\HotJot\Exception\Validation;

class ValidationException extends \UnexpectedValueException {

    /**
     * @var string
     */
    private $claim;

    public function __construct($claim, $message = "", $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->claim = $claim;
    }

    /**
     * @return string
     */
    public function getClaim() : string {
        return $this->claim;
    }
}

