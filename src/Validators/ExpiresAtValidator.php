<?php

namespace IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Contracts\TokenValidator;
use IgnisLabs\HotJot\Exception\Validation\TokenExpiredException;
use IgnisLabs\HotJot\Token;

class ExpiresAtValidator implements TokenValidator {

    use ClaimRequiredTrait;

    public function __construct($isRequired = false) {
        $this->isRequired = $isRequired;
    }

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $this->validateRequiredClaim($token, 'exp');

        if (time() >= $token->getClaim('exp')->getTimestamp()) {
            throw new TokenExpiredException;
        }
    }
}

