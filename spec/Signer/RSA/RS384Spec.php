<?php

namespace spec\IgnisLabs\HotJot\Signer\RSA;

use IgnisLabs\HotJot\Signer\RSA\RS384;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RS384Spec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo', 'bar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RS384::class);
    }

    function it_shoulg_get_the_algorithm_name()
    {
        $this->getAlgorithm()->shouldBe('sha384');
    }
}
