<?php

namespace spec\IgnisLabs\HotJot\Validators;

use IgnisLabs\HotJot\Exception\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Exception\Validation\InvalidIssuedDateException;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validators\IssuedAtValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IssuedAtValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IssuedAtValidator::class);
    }

    function it_passes_validation_if_token_was_issued_in_the_past(Token $token)
    {
        $dt = new \DateTime('-1 day');
        $token->getClaim('iat')->willReturn($dt);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_optionally_requires_issued_at_date(Token $token)
    {
        $this->beConstructedWith(true);
        $token->getClaim('iat')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_issued_in_the_future(Token $token)
    {
        $dt = new \DateTime('+1 day');
        $token->getClaim('iat')->willReturn($dt);
        $this->shouldThrow(InvalidIssuedDateException::class)->duringValidate($token);
    }
}
