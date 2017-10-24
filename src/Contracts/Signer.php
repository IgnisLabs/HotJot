<?php

namespace IgnisLabs\HotJot\Contracts;

use IgnisLabs\HotJot\Token;

interface Signer {

    /**
     * Sign a token payload
     * @param string $payload
     * @return string
     */
    public function sign(string $payload) : string;

    /**
     * Verify a token
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool;

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string;
}
