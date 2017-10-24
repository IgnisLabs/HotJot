<?php

namespace IgnisLabs\HotJot\Signer\RSA;

use IgnisLabs\HotJot\Signer\RSA;

class RS384 extends RSA {

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'sha384';
    }
}
