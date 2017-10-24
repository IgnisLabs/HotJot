<?php

namespace IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Exception\SignatureVerificationException;
use IgnisLabs\HotJot\Exception\UnsignedTokenException;
use IgnisLabs\HotJot\Token;

abstract class RSA implements Signer {

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $passphrase;

    public function __construct(string $privateKey, string $publicKey, string $passphrase = '') {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->passphrase = $passphrase;
    }

    /**
     * Sign a token payload
     * @param string $payload
     * @return string
     */
    public function sign(string $payload) : string {
        $privateKey = openssl_pkey_get_private($this->privateKey, $this->passphrase);
        openssl_sign($payload, $signature, $privateKey, $this->getAlgorithm());
        return $signature;
    }

    /**
     * Verify a token
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool {
        $verifiablePayload = implode('.', array_slice(explode('.', $token->getPayload()), 0, 2));
        $signature = $token->getSignature();

        if (!$signature) {
            throw new UnsignedTokenException;
        }

        $success = openssl_verify($verifiablePayload, $token->getSignature(), $this->publicKey, $this->getAlgorithm());
        if ($success === -1) {
            throw new SignatureVerificationException('OpenSSL verification error: '.openssl_error_string());
        }
        return (bool) $success;
    }
}
