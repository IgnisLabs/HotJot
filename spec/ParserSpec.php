<?php

namespace spec\IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Support\Encoder;
use IgnisLabs\HotJot\Exception\InvalidTokenException;
use IgnisLabs\HotJot\Parser;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function let(Encoder $encoder)
    {
        $encoder->base64Decode('header')->willReturn('header');
        $encoder->base64Decode('claims')->willReturn('claims');

        $this->beConstructedWith($encoder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Parser::class);
    }

    function it_should_decode_signed_tokens(Encoder $encoder)
    {
        $encoder->base64Decode('signature')->willReturn('raw signature');
        $encoder->jsonDecode('header')->willReturn(['alg' => 'foo']);
        $encoder->jsonDecode('claims')->willReturn(['foo' => 'bar']);

        /** @var Token $token */
        $token = $this->parse('header.claims.signature');
        $token->shouldBeAnInstanceOf(Token::class);
        $token->getHeaders()->shouldBeLike(['alg' => 'foo']);
        $token->getClaims()->shouldBeLike(['foo' => 'bar']);
        $token->getPayload()->shouldBe('header.claims.signature');
        $token->getSignature()->shouldBe('raw signature');
    }

    function it_should_decode_unsecure_tokens(Encoder $encoder)
    {
        $encoder->base64Decode('')->willReturn('');
        $encoder->jsonDecode('header')->willReturn(['alg' => 'none']);
        $encoder->jsonDecode('claims')->willReturn(['foo' => 'bar']);

        /** @var Token $token */
        $token = $this->parse('header.claims.');
        $token->shouldBeAnInstanceOf(Token::class);
        $token->getHeaders()->shouldBeLike(['alg' => 'none']);
        $token->getClaims()->shouldBeLike(['foo' => 'bar']);
        $token->getPayload()->shouldBe('header.claims.');
        $token->getSignature()->shouldBe('');
    }

    function it_should_fail_if_no_signature_and_alg_is_not_none(Encoder $encoder)
    {
        $encoder->base64Decode('')->willReturn('');
        $encoder->jsonDecode('header')->willReturn(['alg' => 'foo']);
        $encoder->jsonDecode('claims')->willReturn(['foo' => 'bar']);

        $this->shouldThrow(new InvalidTokenException('Signature missing'))->duringParse('header.claims.');
    }

    function it_should_fail_to_decode_if_token_has_no_header(Encoder $encoder)
    {
        $encoder->jsonDecode('header')->willReturn();

        $this->shouldThrow(new InvalidTokenException('Invalid header encoding'))->duringParse('header.claims.signature');
    }

    function it_should_fail_to_decode_if_header_has_no_alg(Encoder $encoder)
    {
        $encoder->jsonDecode('header')->willReturn(['foo' => 'bar']);

        $this->shouldThrow(new InvalidTokenException('Claim [alg] missing or empty'))->duringParse('header.claims.signature');
    }

    function it_should_fail_to_decode_if_token_has_no_claims(Encoder $encoder)
    {
        $encoder->jsonDecode('header')->willReturn(['alg' => 'foo']);
        $encoder->jsonDecode('claims')->willReturn();

        $this->shouldThrow(new InvalidTokenException('Invalid claims encoding'))->duringParse('header.claims.signature');
    }
}
