<?php

namespace spec\IgnisLabs\HotJot\Signer\HMAC;

use IgnisLabs\HotJot\Signer\HMAC\HS384;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HS384Spec extends ObjectBehavior
{
    function let() {
        $this->beConstructedWith('key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HS384::class);
    }

    function it_shoulg_get_the_algorithm_name()
    {
        $this->getAlgorithm()->shouldBe('sha384');
    }
}
