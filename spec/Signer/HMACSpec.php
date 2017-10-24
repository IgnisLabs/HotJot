<?php

namespace spec\IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Exception\UnsignedTokenException;
use IgnisLabs\HotJot\Signer\HMAC;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;

class HMACSpec extends ObjectBehavior {

    function let() {
        $this->beAnInstanceOf(HMACDouble::class);
        $this->beConstructedWith('key');
    }

    function it_is_initializable() {
        $this->shouldHaveType(HMAC::class);
    }

    function it_should_sign_a_payload() {
        $hash = hash_hmac('sha256', 'some.payload', 'key', true);
        $this->sign('some.payload')->shouldBe($hash);
    }

    function it_should_verify_a_token(Token $token) {
        $hash = hash_hmac('sha256', 'foo.bar', 'key', true);
        $token->getPayload()->willReturn('foo.bar.baz');
        $token->getSignature()->willReturn($hash);
        $this->verify($token)->shouldBe(true);
    }

    function it_should_fail_verification_on_bad_signature(Token $token) {
        $token->getPayload()->willReturn('foo.bar.baz');
        $token->getSignature()->willReturn('bad signature');
        $this->verify($token)->shouldBe(false);
    }

    function it_should_fail_verification_if_token_has_no_signature(Token $token) {
        $token->getPayload()->willReturn('foo.bar.');
        $token->getSignature()->willReturn();
        $this->shouldThrow(UnsignedTokenException::class)->duringVerify($token);
    }
}

class HMACDouble extends HMAC {

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'sha256';
    }
}
