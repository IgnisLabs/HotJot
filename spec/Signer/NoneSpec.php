<?php

namespace spec\IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Signer\None;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoneSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(None::class);
    }

    function it_should_always_return_empty_sting_for_signature()
    {
        $this->sign('whatever')->shouldBe('');
    }

    function it_should_never_pass_validation(Token $token)
    {
        $this->verify($token)->shouldBe(false);
    }

    function it_return_none_as_algorithm()
    {
        $this->getAlgorithm()->shouldBe('none');
    }
}
