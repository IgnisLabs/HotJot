<?php

namespace spec\IgnisLabs\HotJot\Support;

use IgnisLabs\HotJot\Exception\EncodingException;
use IgnisLabs\HotJot\Support\Encoder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EncoderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Encoder::class);
    }

    function it_should_encode_array_to_json()
    {
        $this->jsonEncode(['foo' => 'bar'])->shouldBe('{"foo":"bar"}');
    }

    function it_should_throw_excepton_if_encoding_marformed_characters()
    {
        $this->shouldThrow(EncodingException::class)->duringJsonEncode(['foo' => "\xB1\x31"]);
    }

    function it_should_decode_json_to_assoc_array()
    {
        $this->jsonDecode('{"foo":"bar"}')->shouldBeLike(['foo' => 'bar']);
    }

    function it_should_throw_exception_if_decoding_malformed_json()
    {
        $this->shouldThrow(EncodingException::class)->duringJsonDecode("{'foo':'bar'}");
    }

    function it_should_encode_url_safe_base64()
    {
        $str = "foobar\n";
        $this->base64Encode($str)->shouldBe('Zm9vYmFyCg');
    }

    function it_should_decode_url_safe_base64()
    {
        $this->base64Decode('Zm9vYmFyCg')->shouldBe("foobar\n");
    }
}
