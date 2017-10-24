<?php

namespace spec\IgnisLabs\HotJot\Signer;

use IgnisLabs\HotJot\Exception\UnsignedTokenException;
use IgnisLabs\HotJot\Signer\RSA;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RSASpec extends ObjectBehavior
{
    private static $KEYPWD;
    private static $KEYRES;
    private static $PRIVKEY;
    private static $PUBKEY;

    function let()
    {
        // Generate a small password-protected throwaway key pair for these tests
        static::$KEYPWD = random_bytes(8);
        static::$KEYRES = openssl_pkey_new(['private_key_bits' => 512, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export(static::$KEYRES, $privateKey, static::$KEYPWD);
        static::$PRIVKEY = $privateKey;
        static::$PUBKEY = openssl_pkey_get_details(static::$KEYRES)['key'];

        $this->beAnInstanceOf(RSADouble::class);
        $this->beConstructedWith(static::$PRIVKEY, static::$PUBKEY, static::$KEYPWD);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RSA::class);
    }

    function it_should_sign_a_payload()
    {
        openssl_sign('some.payload', $sig, static::$KEYRES, 'sha256');
        $this->sign('some.payload')->shouldBe($sig);
    }

    function it_should_verify_a_token(Token $token)
    {
        openssl_sign('some.payload', $sig, static::$KEYRES, 'sha256');
        $token->getPayload()->willReturn('some.payload.baz');
        $token->getSignature()->willReturn($sig);
        $this->verify($token)->shouldBe(true);
    }

    function it_should_fail_verification_on_bad_signature(Token $token)
    {
        $token->getPayload()->willReturn('some.payload.baz');
        $token->getSignature()->willReturn('bad signature');
        $this->verify($token)->shouldBe(false);
    }

    function it_should_fail_verification_if_token_has_no_signature(Token $token)
    {
        $token->getPayload()->willReturn('foo.bar.');
        $token->getSignature()->willReturn();
        $this->shouldThrow(UnsignedTokenException::class)->duringVerify($token);
    }
}

class RSADouble extends RSA {

    /**
     * Get the currently used algorithm
     * @return string
     */
    public function getAlgorithm() : string {
        return 'sha256';
    }
}
