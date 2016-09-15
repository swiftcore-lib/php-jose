<?php
namespace Swiftcore\Jose\Tests\RFC7520;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Utility\Base64Url;
use Swiftcore\Utility\ECPKCS;
use Swiftcore\Utility\RSAPKCS;

/* 4.3. ECDSA Signature */
class ES512SignatureTests extends RFC7520
{
    public function testSigning()
    {
        $rawKey = $this->rfc7520ECPrivateKey;
        $privateKeyContent = strval(new ECPKCS($rawKey) . PHP_EOL);

        $jwk = JWK::create('ec', $privateKeyContent);

        $headers = new Headers([
            'alg' => 'ES512',
            'kid' => 'bilbo.baggins@hobbiton.example',
        ]);
        $payload = 'It’s a dangerous business, Frodo, going out your door. '.
                   'You step onto the road, and if you don\'t keep your feet, '.
                   'there’s no knowing where you might be swept off to.';
        $payload = new Payload($payload);

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJFUzUxMiIsImtpZCI6ImJpbGJvLmJhZ2dpbnNAaG9iYml0b24uZXhhbXBsZSJ9';
        $expectedPayload = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'.
                           'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'.
                           'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'.
                           'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
//        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testVerifying()
    {
        $rawKey = $this->rfc7520ECPublicKey;
        $publicKeyContent = strval(new ECPKCS($rawKey) . PHP_EOL);

        $jwsCompact = 'eyJhbGciOiJFUzUxMiIsImtpZCI6ImJpbGJvLmJhZ2dpbnNAaG9iYml0b24uZX
                       hhbXBsZSJ9
                       .
                       SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH
                       lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk
                       b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm
                       UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4
                       .
                       AeMWVF1Y27eCAXhXTq4gRumivz9JPvkyc0wvp3Whg9AwiVN3zMXGYoLFTDA0vF
                       Zh7dtNn5nIc-zWwsPhbhikrsMfAQCgkl2rACGoNwf_C6fQqNwdqXbHY_EOAwHd
                       t0mfCYVat3G-JZXSW6bhtc353LkN2QdfccjmufggdVVyXKqVQcn_';
        $expectedHeaders = json_encode([
            'alg' => 'ES512',
            'kid' => 'bilbo.baggins@hobbiton.example',
        ]);
        $expectedPayload = 'It’s a dangerous business, Frodo, going out your door. '.
                           'You step onto the road, and if you don\'t keep your feet, '.
                           'there’s no knowing where you might be swept off to.';
        $parts =  explode('.', $jwsCompact);
        $this->assertCount(3, $parts);
        foreach ($parts as &$part) {
            $part = Base64Url::decode($part);
            $this->assertNotFalse($part);
        }
        list($headers, $payload, $signature) = $parts;
        $this->assertJson($headers);
        $this->assertJsonStringEqualsJsonString($expectedHeaders, $headers);

        // verify signature
        $jwk = JWK::create('ec', $publicKeyContent);
        $jws = new JWS(
            $jwk,
            new Payload($payload),
            new Headers(json_decode($headers, true)),
            new Signature($signature)
        );
        $jws = $jws->verify();
        $this->assertTrue($jws->verified);
    }
}
