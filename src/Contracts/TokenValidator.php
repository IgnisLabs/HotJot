<?php

namespace IgnisLabs\HotJot\Contracts;

use IgnisLabs\HotJot\Token;

interface TokenValidator {

    /**
     * Validate token
     * @param Token $token
     */
    public function validate(Token $token) : void;
}
