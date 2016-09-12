<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Utility\Base64Url;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Key\RSAKey;

class SimpleTests extends TestCase
{
    public function testExperiment()
    {
        try {
            $rsaKey = new RSAKey();
        } catch (InvalidRSAKeyArgumentException $e) {
            var_dump($e->getErrors());
        }
    }

    public function testJwsRs256SigningWithEncryptedPrivateKey()
    {
        $jwk = JWK::create('rsa', ['file' => BASE_PATH . '/keys/rsa_private1.pem',
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
        $jwk = JWK::create('rsa', ['file' => BASE_PATH . '/keys/rsa_public1.pem',
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
        $jwk = JWK::create('rsa', [BASE_PATH . '/keys/rsa_unencrypted_private1.pem']);
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
        $jwk = JWK::create('rsa', [$privateKeyContent]);
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

    public function testAllJwsRSSHAMethods()
    {
        $methods = [
            'RS256' => 'RS256',
            'RS384' => 'RS384',
            'RS512' => 'RS512',
        ];
        $expectedHeaders = [
            'RS256' => 'eyJhbGciOiJSUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'RS384' => 'eyJhbGciOiJSUzM4NCIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'RS512' => 'eyJhbGciOiJSUzUxMiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
        ];
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = [
            'RS256' => 'P2rxkB1tbnABHAbhKafL3MgkXCVszkfTrZrtEzAEqY9azVKI90v3wz0Peh-WIt7BC_GTaoQx91bz2tfEYtZm2sAxlbSzEc1JK4FlgpQw8UdLLkbw2pi73ABVo675Z2OPjQuD_hlDhLp0jisE0Z65epusCc45ol9HQSRCwZNUZLf5RK10OtsvCmSwxEcrd0INOJbb_MibTg40d49iJ74KZHv5taCBPv9ilqXmjwlQ1eGpfsg7XZtn4sPmIzkFvFTMNEXCIRn5AX20uRtjNqMKClFOPkQdNE_-YHmacrfL03EJDsJDcGATCWrMtbs09u1lfMSvnncw0HLcYze74FTfaA',
            'RS384' => 'P-4dDr4dterZnq5KwprUK3eb1zSmIwF8ZNkNsV_QFyFELE6mBSbmyEwOanODjjpgoNnQwdT9ZJuNCQlT4pdYcqyrKxamUPSVOciNIbDYzFeDz5DFX71hn_J3kIRQiEIYGMufEXhgO8kzvMysZ0GJJnVWR2FHpHd5ihlIXowda5919WEHKpPbtNm_i1Guw06W9O9JyUJtFmxoDrK1RkaI-JhEHCfJiN0oKy0mYFS7LzfrqOTFt6l16T7AVjeovXH_j-_AQbn9ZmTy9PqlsdXHpKFipMCM59gpJqK9bzNDa92GUmMeadv5vtu2wZf9n818GVT8Xs7ECG7Dv1bWdpEyYg',
            'RS512' => 'ZuuSCKZ-xPWCeT1Fd3MPBdFDi2XG3sNd6uWK7A2EkBID2bWg1qy0vJpd6UkwS5lFbA2AgwH5aIvm7gwLwj1UxIroeWqJEJ5bhgakjfasG3X7DNhr3l6GLzRS97jLsy6OtEqkspiHgxhYLCZkJ2SKJ0dThyjBT2E1vyaaqhRRrMBITfFX_tYyqPuBb1hvvstXPN_MfAa6yvGa_4E627lsqLTbEtdTXPa6wxWV3cmGlwE3_tDInBU5d3HEKUMwlv7GLXHMwL-6TBhurqepSRzkD_DM6zyuzzH8fFBo0QmnwivHXkggsT-lTFBO_zhgi4NtCvpqsw-A2jV3huBViCLxzA',
        ];
        foreach ($methods as $method) {
            $privateKeyContent = file_get_contents(BASE_PATH . '/keys/rsa_unencrypted_private1.pem');
            $jwk = JWK::create('rsa', [$privateKeyContent]);
            $headers = new Headers([
                'alg' => $method,
                'b64' => true,
                'crit' => ['b64', 'alg']
            ]);
            $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

            $jws = new JWS($jwk, $payload, $headers);
            $jws->sign();

            $this->assertEquals($expectedHeaders[$method], strval($jws->protected));
            $this->assertEquals($expectedPayload, strval($jws->payload));
            $this->assertEquals($expectedSignature[$method], strval($jws->signature));
        }
    }
}
