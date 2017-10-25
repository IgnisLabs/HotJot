<?php

namespace spec\IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Exception\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Exception\Validation\TokenUsedTooSoonException;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validators\NotBeforeValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotBeforeValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NotBeforeValidator::class);
    }

    function it_passes_validation_if_token_is_used_after_not_before_date(Token $token)
    {
        $dt = new \DateTime('-1 day');
        $token->getClaim('nbf')->willReturn($dt);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_optionally_requires_not_before_date(Token $token)
    {
        $this->beConstructedWith(true);
        $token->getClaim('nbf')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_used_before_not_before_date(Token $token)
    {
        $dt = new \DateTime('+1 day');
        $token->getClaim('nbf')->willReturn($dt);
        $this->shouldThrow(TokenUsedTooSoonException::class)->duringValidate($token);
    }
}
