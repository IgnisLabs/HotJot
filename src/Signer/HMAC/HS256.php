<?php

namespace IgnisLabs\HotJot\Signer\HMAC;

use IgnisLabs\HotJot\Signer\HMAC;

class HS256 extends HMAC {

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'sha256';
    }
}
