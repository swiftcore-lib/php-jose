<?php
namespace Swiftcore\Jose\Tests\RFC7520;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Utility\Base64Url;
use Swiftcore\Utility\RSAPKCS;

/* 4.1.  RSA v1.5 Signature */
class RS256SignatureTests extends RFC7520
{
    public function testSigning()
    {
        $rawKey = $this->rfc7520RSAPrivateKey;
        $rawKey = array_map(function ($value) {
            return Base64Url::decode($value);
        }, $rawKey);
        $privateKeyContent = strval(new RSAPKCS($rawKey) . PHP_EOL);

        $jwk = JWK::create('rsa', $privateKeyContent);

        $headers = new Headers([
            'alg' => 'RS256',
            'kid' => 'bilbo.baggins@hobbiton.example',
        ]);
        $payload = new Payload('It’s a dangerous business, Frodo, going out your door. You step onto the road, and if you don\'t keep your feet, there’s no knowing where you might be swept off to.');

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImJpbGJvLmJhZ2dpbnNAaG9iYml0b24uZXhhbXBsZSJ9';
        $expectedPayload = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IHlvdXIgZG9vc'.
                           'i4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBkb24ndCBrZWVwIHlvdXIgZm'.
                           'VldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcmUgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB'.
                           '0by4';
        $expectedSignature = 'MRjdkly7_-oTPTS3AXP41iQIGKa80A0ZmTuV5MEaHoxnW2e5CZ5NlKtainoFmKZopdHM1O2U'.
                             '4mwzJdQx996ivp83xuglII7PNDi84wnB-BDkoBwA78185hX-Es4JIwmDLJK3lfWRa-XtL0Rn'.
                             'ltuYv746iYTh_qHRD68BNt1uSNCrUCTJDt5aAE6x8wW1Kt9eRo4QPocSadnHXFxnt8Is9Uzp'.
                             'ERV0ePPQdLuW3IS_de3xyIrDaLGdjluPxUAhb6L2aXic1U12podGU0KLUQSE_oI-ZnmKJ3F4'.
                             'uOZDnd6QZWJushZ41Axf_fcIe8u9ipH84ogoree7vjbU5y18kDquDg';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testVerifying()
    {
        $rawKey = $this->rfc7520RSAPublicKey;
        $rawKey = array_map(function ($value) {
            return Base64Url::decode($value);
        }, $rawKey);
        $publicKeyContent = strval(new RSAPKCS($rawKey) . PHP_EOL);

        $jwsCompact = 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImJpbGJvLmJhZ2dpbnNAaG9iYml0b24uZXhhbXBsZSJ9.SXTigJl'.
                      'zIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IHlvdXIgZG9vci4gWW91IHN0ZXA'.
                      'gb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBkb24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5'.
                      'vIGtub3dpbmcgd2hlcmUgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4.MRjdkly7_-oTPTS3AXP41iQ'.
                      'IGKa80A0ZmTuV5MEaHoxnW2e5CZ5NlKtainoFmKZopdHM1O2U4mwzJdQx996ivp83xuglII7PNDi84wn'.
                      'B-BDkoBwA78185hX-Es4JIwmDLJK3lfWRa-XtL0RnltuYv746iYTh_qHRD68BNt1uSNCrUCTJDt5aAE6'.
                      'x8wW1Kt9eRo4QPocSadnHXFxnt8Is9UzpERV0ePPQdLuW3IS_de3xyIrDaLGdjluPxUAhb6L2aXic1U1'.
                      '2podGU0KLUQSE_oI-ZnmKJ3F4uOZDnd6QZWJushZ41Axf_fcIe8u9ipH84ogoree7vjbU5y18kDquDg';
        $expectedHeaders = json_encode([
            'alg' => 'RS256',
            'kid' => 'bilbo.baggins@hobbiton.example',
        ]);
        $expectedPayload = ('It’s a dangerous business, Frodo, going out your door. You step onto the road,'.
                            ' and if you don\'t keep your feet, there’s no knowing where you might be swept'.
                            ' off to.');
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
        $jwk = JWK::create('rsa', $publicKeyContent);
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
