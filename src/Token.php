<?php

namespace IgnisLabs\HotJot;

class Token {

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
    private $payload;

    public function __construct(array $claims, array $headers = [], string $payload = null) {
        $this->claims = $claims;
        $this->headers = $headers;
        $this->payload = $payload;
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
     * @return string|null
     */
    public function getPayload() : ?string {
        return $this->payload;
    }

    /**
     * Get token signature (if signed)
     * @return string|null
     */
    public function getSignature() : ?string {
        $segments = explode('.', $this->payload);
        return $segments[2] ?? null;
    }
}
