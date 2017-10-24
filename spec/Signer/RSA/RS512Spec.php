<?php

namespace spec\IgnisLabs\HotJot\Signer\RSA;

use IgnisLabs\HotJot\Signer\RSA\RS512;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RS512Spec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', 'bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RS512::class);
    }

    function it_shoulg_get_the_algorithm_name()
    {
        $this->getAlgorithm()->shouldBe('sha512');
    }
}
