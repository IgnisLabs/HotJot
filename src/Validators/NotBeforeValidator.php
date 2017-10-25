<?php

namespace IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Contracts\TokenValidator;
use IgnisLabs\HotJot\Exception\Validation\InvalidIssuedDateException;
use IgnisLabs\HotJot\Exception\Validation\TokenExpiredException;
use IgnisLabs\HotJot\Exception\Validation\TokenUsedTooSoonException;
use IgnisLabs\HotJot\Token;

class NotBeforeValidator implements TokenValidator {

    use ClaimRequiredTrait;

    public function __construct($isRequired = false) {
        $this->isRequired = $isRequired;
    }

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $this->validateRequiredClaim($token, 'nbf');

        if (time() < $token->getClaim('nbf')->getTimestamp()) {
            throw new TokenUsedTooSoonException;
        }
    }
}

