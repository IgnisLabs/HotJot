<?php

namespace IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Support\Encoder as EncoderContract;
use IgnisLabs\HotJot\Exception\InvalidTokenException;
use IgnisLabs\HotJot\Support\Encoder;

class Parser {

    /**
     * @var EncoderContract
     */
    private $encoder;

    public function __construct(EncoderContract $encoder =  null) {
        $this->encoder = $encoder ?? new Encoder;
    }

    public function parse(string $jwt) : Token {
        [$encodedHeader, $encodedClaims, $encodedSignature] = $this->getSegments($jwt);

        $header = $this->decodeHeader($encodedHeader);
        $claims = $this->decodeClaims($encodedClaims);
        $signature = $this->decodeSignature($encodedSignature, $header['alg']);

        return new Token($jwt, $claims, $header, $signature);
    }

    /**
     * Get token segments
     * @param string $jwt
     * @return array
     */
    private function getSegments(string $jwt) : array {
        $segments = explode('.', $jwt);
        if (count($segments) < 3) {
            throw new InvalidTokenException('Wrong number of segments');
        }

        return $segments;
    }

    /**
     * Decode token header
     * @param string $encodedHeader
     * @return array
     */
    private function decodeHeader(string $encodedHeader) : array {
        $header = $this->encoder->jsonDecode($this->encoder->base64Decode($encodedHeader));
        if (!$header) {
            throw new InvalidTokenException('Invalid header encoding');
        }
        if (empty($header['alg'])) {
            throw new InvalidTokenException('Claim [alg] missing or empty');
        }

        return $header;
    }

    /**
     * Decode token claims
     * @param string $encodedClaims
     * @return array
     */
    private function decodeClaims(string $encodedClaims) : array {
        $claims = $this->encoder->jsonDecode($this->encoder->base64Decode($encodedClaims));
        if (!$claims) {
            throw new InvalidTokenException('Invalid claims encoding');
        }

        return $claims;
    }

    /**
     * Decode token signature
     * @param string $encodedSignature
     * @param string $alg
     * @return string
     */
    private function decodeSignature(string $encodedSignature, string $alg) : string {
        $signature = $this->encoder->base64Decode($encodedSignature);

        if ($alg !== 'none' && !$signature) {
            throw new InvalidTokenException('Signature missing');
        }

        return $signature;
    }
}
