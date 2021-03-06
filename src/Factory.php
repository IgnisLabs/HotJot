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
     * @param Signer               $signer
     * @param EncoderContract|null $encoder
     */
    public function __construct(Signer $signer, EncoderContract $encoder = null) {
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
        $headers['alg'] = $this->signer->getAlgorithm();

        $segments = $this->encodeSegments($claims, $headers);
        $signature = $this->signer->sign(implode('.', $segments));
        $segments[] = $this->encoder->base64Encode($signature);

        return new Token(implode('.', $segments), $claims, $headers, $signature);
    }

    private function encodeSegments(array $claims, array $headers) : array {
        return [
            $this->encoder->base64Encode($this->encoder->jsonEncode($headers)),
            $this->encoder->base64Encode($this->encoder->jsonEncode($claims)),
        ];
    }

    /**
     * Get the currently used signer
     * @return Signer
     */
    public function getSigner() : Signer {
        return $this->signer;
    }

    /**
     * Set a different signer
     * Factory is immutable, so it returns a new instance
     * @param Signer $signer
     * @return Factory
     */
    public function setSigner(Signer $signer) : Factory {
        return new static($signer, $this->encoder);
    }
}
