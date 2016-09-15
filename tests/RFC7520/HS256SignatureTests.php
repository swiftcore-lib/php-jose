<?php
namespace Swiftcore\Jose\Tests\RFC7520;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;

/* 4.4. HMAC-SHA2 Integrity Protection */
class HS256SignatureTests extends RFC7520
{
    public function testSigning()
    {
        $jwk = JWK::create('hmac', $this->rfc7520SymmetricKeyMAC['k']);
        $headers = new Headers([
            'alg' => 'HS256',
            'kid' => '018c0ae5-4d9b-471b-bfd6-eef314bc7037',
        ]);
        $payload = 'It’s a dangerous business, Frodo, going out your door. '.
                   'You step onto the road, and if you don\'t keep your feet, '.
                   'there’s no knowing where you might be swept off to.';
        $payload = new Payload($payload);

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJIUzI1NiIsImtpZCI6IjAxOGMwYWU1LTRkOWItNDcxYi1iZmQ2LWVlZjMxNGJjNzAzNyJ9';
        $expectedPayload = 'SXTigJlzIGEgZGFuZ2Vyb3VzIGJ1c2luZXNzLCBGcm9kbywgZ29pbmcgb3V0IH'.
                           'lvdXIgZG9vci4gWW91IHN0ZXAgb250byB0aGUgcm9hZCwgYW5kIGlmIHlvdSBk'.
                           'b24ndCBrZWVwIHlvdXIgZmVldCwgdGhlcmXigJlzIG5vIGtub3dpbmcgd2hlcm'.
                           'UgeW91IG1pZ2h0IGJlIHN3ZXB0IG9mZiB0by4';
        $expectedSignature = 's0h6KThzkfBBBkLspW1h84VsJZFTsPPqMDA7g1Md7p0';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testVerifying()
    {

    }
}
