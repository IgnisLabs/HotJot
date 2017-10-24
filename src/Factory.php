<?php

namespace IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Contracts\Support\Encoder as EncoderContract;
use IgnisLabs\HotJot\Support\Encoder;

class Factory {

    /**
     * @var Signer
     */
    private $signer;

    /**
     * @var EncoderContract
     */
    private $encoder;

    /**
     * Factory constructor.
     * @param Signer|null          $signer
     * @param EncoderContract|null $encoder
     */
    public function __construct(Signer $signer = null, EncoderContract $encoder = null) {
        $this->signer = $signer;
        $this->encoder = $encoder ?? new Encoder;
    }

    /**
     * Create a signed JWT
     * @param array $claims
     * @param array $headers
     * @return Token
     */
    public function create(array $claims, array $headers = []) : Token {
        $headers['typ'] = 'JWT';
        $headers['alg'] = $this->signer ? $this->signer->getAlgorithm() : 'none';

        $segments = $this->encodeSegments($claims, $headers);
        $signature = '';

        if ($this->signer) {
            $signature = $this->signer->sign(implode('.', $segments));
        }

        $segments[] = $this->encoder->base64Encode($signature);

        return new Token(implode('.', $segments), $claims, $headers, $signature);
    }

    private function encodeSegments(array $claims, array $headers) : array {
        return [
            $this->encoder->base64Encode($this->encoder->jsonEncode($headers)),
            $this->encoder->base64Encode($this->encoder->jsonEncode($claims)),
        ];
    }
}
