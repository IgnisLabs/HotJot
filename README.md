HotJot
======

[![Build Status](https://img.shields.io/travis/IgnisLabs/HotJot.svg?style=flat-square)](https://travis-ci.org/IgnisLabs/HotJot)

No-frills JWT & JWS library.

Installation
------------

Install with composer:

```shell
$ composer require ignislabs/hotjot
```

### Requirements

+ PHP >= 7.1
+ OpenSSL extension
+ JSON extension

Usage
-----

Creating, verifying and validating tokens is really simple, let's take
a quick look at these operations before we dive to each component in
more detail.

**Create a token:**
```php
$token = $factory->create($claims, $headers);
```

**Verify a token:**
```php
$signer->verify($token);
```

**Validate a token:**
```php
$validator->validate($token);
```

Let's take a look at the signers first, as these are the most important
part of the library. You need a signer to create signed tokens and
verify them.

### Signers

You can choose between `HMAC`, `RSA` or `None` signers.

#### `HMAC` Signers

HMAC are the simplest ones. It's a _symmetric_ algorithm, which means
you only have a single private encryption key. You should try to make
this as cryptographically secure and random as possible.

You have 3 different options: `HS256`, `HS384`, and `HS512`. All three
require only an encryption key as a constructor parameter.

```php
$signer = new \IgnisLabs\HotJot\Signer\HMAC\HS512('encryption key');
```

#### `RSA` Signers

RSA is _asymmetric_, which means you'll need to create a key pair:

```shell
# create a strong, password protected private key
$ openssl genpkey -algorithm RSA -pkeyopt rsa_keygen_bits:4096 -outform PEM -out private.pem -pass stdin

# get public key
$ openssl rsa -pubout -in private.pem -out public.pem
```

If you don't want to generate a password protected key, just omit
`-pass stdin`.

> You can, if you need/want to, make your public key publicly available
> so anyone can use it to verify if the token is really signed by you
> (that's one of the purposes behind public-key cryptography).

Again, you have 3 different options: `RS256`, `RS384`, and `RS512`. All
three require both private and public keys, and the passphrase if your
private key is protected.

```php
$privateKey = file_get_contents('/path/to/private.pem');
$publicKey = file_get_contents('/path/to/public.pem');

$signer = new \IgnisLabs\HotJot\Signer\RSA\RS512($privateKey, $publicKey, 'key passphrase (if any)');
```

The *private key* is used for signing and the *public key* for
verification.

#### `None` Signer

Using the `None` signer will result in an unsecured token with no
signature, and verification with this signer will always fail, as
unsecured tokens are not signed.

> **Warning!** Even though you technically can create unsecured tokens,
> you should be really careful and know very well what you're doing.

This signer doesn't require any parameters, as it can't sign or verify.
It will always return an empty string as a signature, and verification
will always fail.

```php
$signer = new \IgnisLabs\HotJot\Signer\None;
```

### Token Creation

Now that you know about signers, let's see how can you create tokens.

To create tokens you'll need the `Factory` and a `Signer`, and you'll
get a `Token` object with a few handy methods.

#### Creating Secured Tokens

To crete secured tokens, use any signer except `None`.

```php
$signer = new \IgnisLabs\HotJot\Signer\HMAC\HS512('encryption key');
$factory = new \IgnisLabs\HotJot\Factory($signer);

$token = $factory->create([
    'iss' => 'http://api.example.com',
    'aud' => 'http://www.example.com',
    'jti' => bin2hex(random_bytes(16)),
    'exp' => (new DateTime('+10 days'))->getTimestamp(),
    // etc...
]);

$token->getHeader('alg'); // -> 'HS512'
$token->getClaim('iss'); // -> 'http://api.example.com'
$token->getClaim('exp'); // -> DateTime object
```

As you can see, `exp` returns a `DateTime` object, and so will `iat` and `nbf`.

#### Creating Unsecured Tokens

To create unsecured tokens you need to use the `None` signer.

> **Warning!** Even though you technically can create unsecured tokens,
> you should be really careful and know very well what you're doing.
> (Yes I know I'm repeating this :P)

```php
$signer = new \IgnisLabs\HotJot\Signer\None;
$factory = new \IgnisLabs\HotJot\Factory($signer);

$token = $factory->create([
    'iss' => 'http://api.example.com',
    'aud' => 'http://www.example.com',
    'jti' => bin2hex(random_bytes(16)),
    'exp' => (new DateTime('+10 days'))->getTimestamp(),
    // etc...
]);

$token->getClaim('alg'); // -> 'none'
$token->getSignature(); // -> null
```

### Parsing

You can parse encoded token strings with the parser. How you obtain the
encoded token is out of the scope of the library (authorization header,
query parameter, etc).

When you parse an encoded token, you'll get back a `Token` object, same
one as with the `Factory`.

```php
$parser = new \IgnisLabs\HotJot\Parser;
$token = $parser->parse($encodedTokenString);
```

The parser **does not verify or validate the token**, as long as it can
parse it and it's rfc-compliant, the parser will succeed and return the
token object. You'll need to use a Signer and the Validator to verify
and validate the token.

If the parser does fail it will throw an `InvalidTokenException` with
the appropriate message.

### Signature Verification

This is a critical step when receiving tokens from the outside world.

This library does not automatically set any algorithm based on the
`alg` header, and you shouldn't do this either. By following this
simple rule you will avoid [known vulnerabilities][1].

This library makes it easy not to fall for this exploits by simply
requiring you to instantiate the desired signer yourself, and making a
hard association between the keys and the signers by passing keys on
instantiation rather than on verification, leaving less room for error.

All signers will first check the token's `alg` header and check if it
matches the signer's algorithm. If the algorithms don't match it will
throw a `SignatureVerificationException` exception.

```php
$signer = new \IgnisLabs\HotJot\Signer\RSA\RS512($privateKey, $publicKey, 'passphrase');
$signer->verify($token); // -> boolean — $token most likely obtained through the parser
```

### Validation

Once you have a verified token, you can start to validate it using the
`Validator`.

The `Validator` is a really simple class that takes a bunch of token
validators and uses them to validate a token. The validators don't
return eny values, but throw exceptions on failure.

This library already comes with some useful ones, but you can add as
many as you need.

```php
use IgnisLabs\HotJot\Validators as 🕵;

$validator = new \IgnisLabs\HotJot\Validator(
    new 🕵\IssuedAtValidator, // fails if token used before `iat`
    new 🕵\NotBeforeValidator, // fails if token used before `nbf`
    new 🕵\ExpiresAtValidator // fails if token is used after `exp`
);

$validator->validate($token);
```

If you want to make any of these validators be required, you can
instantiate them like this:

```php
use IgnisLabs\HotJot\Validators as 🕵;

$validator = new \IgnisLabs\HotJot\Validator(
    new 🕵\IssuedAtValidator(true),
    new 🕵\NotBeforeValidator(true),
    new 🕵\ExpiresAtValidator(true)
);

$validator->validate($token);
```

You can create your own validators, you just need them to implement the
`IgnisLabs\HotJot\Contracts\TokenValidator` contract. You also have the
`\IgnisLabs\HotJot\Validators\ClaimRequiredTrait` to save you some time
when creating required validators.

[1]: https://auth0.com/blog/critical-vulnerabilities-in-json-web-token-libraries/
