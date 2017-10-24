<?php

namespace IgnisLabs\HotJot\Signer\RSA;

use IgnisLabs\HotJot\Signer\RSA;

class RS512 extends RSA {

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'sha512';
    }
}
