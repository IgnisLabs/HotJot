<?php

namespace IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Exception\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Token;

trait ClaimRequiredTrait {

    private $isRequired = false;

    private function validateRequiredClaim(Token $token, string $claim) : void {
        if ($this->isRequired && !$token->getClaim($claim)) {
            throw new ClaimRequiredException($claim);
        }
    }
}
