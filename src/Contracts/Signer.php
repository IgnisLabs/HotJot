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
     */
    public function verify(Token $token);

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string;
}
