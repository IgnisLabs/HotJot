<?php

namespace spec\IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Exception\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Exception\Validation\TokenExpiredException;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validators\ExpiresAtValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpiresAtValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ExpiresAtValidator::class);
    }

    function it_passes_validation_if_token_is_not_expired(Token $token)
    {
        $exp = new \DateTime('+1 day');
        $token->getClaim('exp')->willReturn($exp);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_optionally_requires_expiraton_date(Token $token)
    {
        $this->beConstructedWith(true);
        $token->getClaim('exp')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_expired(Token $token)
    {
        $exp = new \DateTime('-1 day');
        $token->getClaim('exp')->willReturn($exp);
        $this->shouldThrow(TokenExpiredException::class)->duringValidate($token);
    }
}
