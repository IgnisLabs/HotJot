<?php

namespace IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Exception\UnsignedTokenException;
use IgnisLabs\HotJot\Token;

abstract class HMAC implements Signer {

    /**
     * @var string
     */
    private $encryptionKey;

    public function __construct(string $encryptionKey) {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Sign a token payload
     * @param string $payload
     * @return string
     */
    public function sign(string $payload) : string {
        $payload = trim($payload, '.');
        return hash_hmac($this->getAlgorithm(), $payload, $this->encryptionKey, true);
    }

    /**
     * Verify a token
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool {
        [$header, $claims] = explode('.', $token->getPayload());
        $signature = $token->getSignature();

        if (!$signature) {
            throw new UnsignedTokenException;
        }

        return hash_equals($signature, $this->sign(implode('.', [$header, $claims])));
    }
}
