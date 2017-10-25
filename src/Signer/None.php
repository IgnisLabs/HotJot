<?php

namespace IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Token;

class None implements Signer {

    /**
     * Signature is an empty string
     * @param string $payload
     * @return string
     */
    public function sign(string $payload) : string {
        return '';
    }

    /**
     * Verify a token
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool {
        return false;
    }

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'none';
    }
}
