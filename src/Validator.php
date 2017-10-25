<?php

namespace IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\TokenValidator;

class Validator {

    /**
     * @var TokenValidator[]
     */
    private $validators;

    /**
     * Validator constructor.
     * @param TokenValidator[] ...$validators
     */
    public function __construct(TokenValidator ...$validators) {
        $this->validators = $validators;
    }

    /**
     * @param TokenValidator[] $validators
     * @return Validator
     */
    public function addValidators(TokenValidator ...$validators) : Validator {
        return new static(...$this->validators, ...$validators);
    }

    /**
     * @param TokenValidator[] ...$validators
     * @return Validator
     */
    public function replaceValidators(TokenValidator ...$validators) : Validator {
        return new static(...$validators);
    }

    /**
     * Validate token
     * Validators should throw exceptions with descriptive messages
     * @param Token $token
     * @param string[] ...$excludeValidators Token validators class names
     */
    public function validate(Token $token, ...$excludeValidators) : void {
        foreach ($this->validators as $validator) {
            if (!in_array(get_class($validator), $excludeValidators)) {
                $validator->validate($token);
            }
        }
    }
}

