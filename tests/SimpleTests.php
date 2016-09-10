<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Utility\Base64Url;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\RsaKey;

class SimpleTests extends TestCase
{
    public function testJwsRs256SigningWithEncryptedPrivateKey()
    {
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), ['file' => BASE_PATH . '/keys/rsa_private1.pem',
            'pwd' => '123123']);
        $headers = new Headers([
            'alg' => 'RS256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJSUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = 'P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testJwsRs256Verfying()
    {
        $jwsCompact = 'eyJhbGciOiJSUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA';
        $expectedHeaders = json_encode([
            'alg' => 'RS256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $expectedPayload = json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]);

        $parts =  explode('.', $jwsCompact);
        $this->assertCount(3, $parts);
        foreach ($parts as &$part) {
            $part = Base64Url::decode($part);
            $this->assertNotFalse($part);
        }

        list($headers, $payload, $signature) = $parts;
        $this->assertJson($headers);
        $this->assertJson($payload);
        $this->assertJsonStringEqualsJsonString($expectedHeaders, $headers);
        $this->assertJsonStringEqualsJsonString($expectedPayload, $payload);

        // verify signature
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), ['file' => BASE_PATH . '/keys/rsa_public1.pem',
            'pwd' => '123123']);
        $jws = new JWS(
            $jwk,
            new Payload($payload),
            new Headers(json_decode($headers, true)),
            new Signature($signature)
        );
        $jws = $jws->verify();
        $this->assertTrue($jws->verified);
    }

    public function testJwsRs256SigningWithUnencryptedPrivateKey()
    {
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), [BASE_PATH . '/keys/rsa_unencrypted_private1.pem']);
        $headers = new Headers([
            'alg' => 'RS256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJSUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = 'P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testJwsRs256SigningWithUnencryptedPrivateKeyContent()
    {
        $privateKeyContent = file_get_contents(BASE_PATH . '/keys/rsa_unencrypted_private1.pem');
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), [$privateKeyContent]);
        $headers = new Headers([
            'alg' => 'RS256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJSUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = 'P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }
}
