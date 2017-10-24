<?php

namespace spec\IgnisLabs\HotJot\Signer\HMAC;

use IgnisLabs\HotJot\Signer\HMAC\HS256;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HS256Spec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith('key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HS256::class);
    }

    function it_shoulg_get_the_algorithm_name()
    {
        $this->getAlgorithm()->shouldBe('sha256');
    }
}
