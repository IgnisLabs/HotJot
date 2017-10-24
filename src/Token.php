<?php

namespace IgnisLabs\HotJot;

class Token {

    /**
     * @var string
     */
    private $payload;

    /**
     * @var array
     */
    private $claims;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $signature;

    public function __construct(string $payload, array $claims, array $headers = [], string $signature = null) {
        $this->payload = $payload;
        $this->claims = $claims;
        $this->headers = $headers;
        $this->signature = $signature;
    }

    /**
     * Get claim by name
     * If it's a date claim (iat, nbf, or exp), a DateTime object is returned
     * @param string $name
     * @return mixed|null|\DateTime
     */
    public function getClaim(string $name) {
        $value = $this->claims[$name] ?? null;
        if ($value && preg_match('/^(iat|nbf|exp)$/', $name)) {
            $value = (new \DateTime())->setTimestamp($value);
        }
        return $value;
    }

    /**
     * Get all claims
     * @return array
     */
    public function getClaims() : array {
        return $this->claims;
    }

    /**
     * Get header by name
     * @param string $name
     * @return mixed|null
     */
    public function getHeader(string $name) {
        return $this->headers[$name] ?? null;
    }

    /**
     * Get all claims
     * @return array
     */
    public function getHeaders() : array {
        return $this->headers;
    }

    /**
     * Get token payload
     * @return string
     */
    public function getPayload() : string {
        return $this->payload;
    }

    /**
     * Get token signature (if signed)
     * @return string|null
     */
    public function getSignature() : ?string {
        return $this->signature;
    }
}
