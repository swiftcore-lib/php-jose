<?php
namespace Swiftcore\Jose\Tests\Signature;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Tests\TestCase;
use Swiftcore\Utility\Base64Url;

class SimpleRSATests extends TestCase
{
    public function testJwsHS256Signing()
    {
        $jwk = JWK::create('hmac', 'something');
        $headers = new Headers([
            'alg' => 'HS256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJIUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = 'P3oiYpuzwudE0rhCSoSF_BjN4rKQmN993NVMpVR5aL4';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
    }

    public function testJwsHS256Verifying()
    {
        $jwsCompact = 'eyJhbGciOiJIUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.P3oiYpuzwudE0rhCSoSF_BjN4rKQmN993NVMpVR5aL4';
        $expectedHeaders = json_encode([
            'alg' => 'HS256',
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
        $jwk = JWK::create('hmac', 'something');
        $jws = new JWS(
            $jwk,
            new Payload($payload),
            new Headers(json_decode($headers, true)),
            new Signature($signature)
        );
        $jws = $jws->verify();
        $this->assertTrue($jws->verified);
    }

    public function testAllJwsRSMethods()
    {
        $methods = [
            'HS256' => 'HS256',
            'HS384' => 'HS384',
            'HS512' => 'HS512',
        ];
        $expectedHeaders = [
            'HS256' => 'eyJhbGciOiJIUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'HS384' => 'eyJhbGciOiJIUzM4NCIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'HS512' => 'eyJhbGciOiJIUzUxMiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
        ];
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';
        $expectedSignature = [
            'HS256' => 'P3oiYpuzwudE0rhCSoSF_BjN4rKQmN993NVMpVR5aL4',
            'HS384' => 'CfF-c5IDC8rKn-2im0Gzd-GZzPsL6RK0G30trHBvHVLShsizdkQepK5doPa6P7XG',
            'HS512' => 'pe5XPYmN49583vNoB9O9NwKbaLhiZMmFV-2Te8299tOO6bpHNvwIdY5wlR_TZVe9G3RbRXuxg3QvE_dfDhm4Uw',
        ];
        foreach ($methods as $method) {
            $jwk = JWK::create('hmac', 'something');
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
