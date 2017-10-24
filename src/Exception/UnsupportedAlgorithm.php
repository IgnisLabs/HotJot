<?php

namespace IgnisLabs\HotJot\Exception;

use Throwable;

class UnsupportedAlgorithm extends \UnexpectedValueException {
    public function __construct($message = "Algorithm not supported", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    static public function fromAlg(string $alg) : UnsupportedAlgorithm {
        return new static("Algorithm [$alg] not supported");
    }
}
