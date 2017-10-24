<?php

namespace spec\IgnisLabs\HotJot;

use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    function let()
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];
        $this->beConstructedWith('headers.claims.signature', $claims, $headers, 'raw signature');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Token::class);
    }

    function it_can_get_claims_by_name()
    {
        $this->getClaim('foo')->shouldBe('bar');
    }

    function it_can_get_all_claims()
    {
        $this->getClaims()->shouldBeLike(['foo' => 'bar']);
    }

    function it_can_get_headers_by_name()
    {
        $this->getHeader('baz')->shouldBe('qux');
    }

    function it_can_get_all_headers()
    {
        $this->getHeaders()->shouldBeLike(['baz' => 'qux']);
    }

    function it_should_return_datetime_for_date_claims()
    {
        $time = time();
        $this->beConstructedWith('payload', [
            'iat' => $time + 10,
            'nbf' => $time + 20,
            'exp' => $time + 30,
        ]);
        $iat = $this->getClaim('iat');
        $nbf = $this->getClaim('nbf');
        $exp = $this->getClaim('exp');

        $iat->shouldBeAnInstanceOf(\DateTime::class);
        $nbf->shouldBeAnInstanceOf(\DateTime::class);
        $exp->shouldBeAnInstanceOf(\DateTime::class);

        $iat->getTimestamp()->shouldBe($time + 10);
        $nbf->getTimestamp()->shouldBe($time + 20);
        $exp->getTimestamp()->shouldBe($time + 30);
    }

    function it_should_get_token_payload()
    {
        $this->getPayload()->shouldBe('headers.claims.signature');
    }

    function it_should_get_signature_from_payload()
    {
        $this->getSignature()->shouldBe('raw signature');
    }
}
