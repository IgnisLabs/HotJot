<?php

namespace spec\IgnisLabs\HotJot;

use IgnisLabs\HotJot\Factory;
use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Contracts\Support\Encoder;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let(Signer $signer, Encoder $encoder)
    {
        $signer->getAlgorithm()->willReturn('an-alg');
        $this->beConstructedWith($signer, $encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_create_signed_token(Signer $signer, Encoder $encoder)
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];

        $encoder->jsonEncode(array_merge($headers, ['typ' => 'JWT', 'alg' => 'an-alg']))->willReturn('headers');
        $encoder->jsonEncode($claims)->willReturn('claims');
        $encoder->base64Encode('headers')->willReturn('headers');
        $encoder->base64Encode('claims')->willReturn('claims');
        $encoder->base64Encode('signature')->willReturn('signature');

        $signer->sign('headers.claims.')->shouldBeCalled()->willReturn('signature');

        /** @var Token $token */
        $token = $this->create($claims, $headers);
        $token->shouldBeAnInstanceOf(Token::class);
        $token->getHeader('alg')->shouldBe('an-alg');
        $token->getPayload()->shouldBe('headers.claims.signature');
    }

    function it_should_create_unsigned_token(Signer $signer, Encoder $encoder)
    {
        $this->beConstructedWith(null, $encoder);

        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];

        $encoder->jsonEncode(array_merge($headers, ['typ' => 'JWT', 'alg' => 'none']))->willReturn('headers');
        $encoder->jsonEncode($claims)->willReturn('claims');
        $encoder->base64Encode('headers')->willReturn('headers');
        $encoder->base64Encode('claims')->willReturn('claims');

        $signer->sign('headers.claims.')->shouldNotBeCalled();

        /** @var Token $token */
        $token = $this->create($claims, $headers, false);
        $token->shouldBeAnInstanceOf(Token::class);
        $token->getHeader('alg')->shouldBe('none');
        $token->getPayload()->shouldBe('headers.claims.');
    }
}
