<?php
namespace Swiftcore\Jose\Tests\JWS;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Tests\TestCase;
use Swiftcore\Utility\Base64Url;

class SimpleESTests extends TestCase
{
    public function setUp()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped(
                'HHVM does NOT support for ECDSA signing and verifying.'
            );
        }
        parent::setUp();
    }

    public function testJwsES256SigningAndVerifyingWithEncryptedKeys()
    {
        $jwk = JWK::create('ec', [
            'file' => BASE_PATH . '/keys/ec_encrypted_secp384r1_private2.pem',
            '123123'
        ]);
        $headers = new Headers([
            'alg' => 'ES256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJFUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));

        $jwsCompact = sprintf('%s.%s.%s', $jws->protected, $jws->payload, $jws->signature);
        $expectedHeaders = json_encode([
            'alg' => 'ES256',
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
        $jwk = JWK::create('ec', ['file' => BASE_PATH . '/keys/ec_secp384r1_public2.pem']);
        $jws = new JWS(
            $jwk,
            new Payload($payload),
            new Headers(json_decode($headers, true)),
            new Signature($signature)
        );
        $jws = $jws->verify();
        $this->assertTrue($jws->verified);
    }

    public function testJwsES256SigningAndVerifyingWithUnencryptedKeys()
    {
        $jwk = JWK::create('ec', [
            'file' => BASE_PATH . '/keys/ec_secp256k1_private1.pem',
        ]);
        $headers = new Headers([
            'alg' => 'ES256',
            'b64' => true,
            'crit' => ['b64', 'alg']
        ]);
        $payload = new Payload(json_encode(["sub" => "1234567890", "name" => "John Doe", "admin" => true]));

        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();

        $expectedHeaders = 'eyJhbGciOiJFUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0';
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));

        $jwsCompact = sprintf('%s.%s.%s', $jws->protected, $jws->payload, $jws->signature);
        $expectedHeaders = json_encode([
            'alg' => 'ES256',
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
        $jwk = JWK::create('ec', ['file' => BASE_PATH . '/keys/ec_secp256k1_public1.pem']);
        $jws = new JWS(
            $jwk,
            new Payload($payload),
            new Headers(json_decode($headers, true)),
            new Signature($signature)
        );
        $jws = $jws->verify();
        $this->assertTrue($jws->verified);
    }

    public function testAllJwsESMethods()
    {
        $methods = [
            'ES256' => 'ES256',
            'ES384' => 'ES384',
            'ES512' => 'ES512',
        ];
        $expectedHeaders = [
            'ES256' => 'eyJhbGciOiJFUzI1NiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'ES384' => 'eyJhbGciOiJFUzM4NCIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
            'ES512' => 'eyJhbGciOiJFUzUxMiIsImI2NCI6dHJ1ZSwiY3JpdCI6WyJiNjQiLCJhbGciXX0',
        ];
        $expectedPayload = 'eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9';

        foreach ($methods as $method) {
            $privateKeyContent = file_get_contents(BASE_PATH . '/keys/ec_secp521r1_private1.pem');
            $jwk = JWK::create('ec', [$privateKeyContent]);
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

            $jwsCompact = sprintf('%s.%s.%s', $jws->protected, $jws->payload, $jws->signature);

            $parts =  explode('.', $jwsCompact);
            $this->assertCount(3, $parts);
            foreach ($parts as &$part) {
                $part = Base64Url::decode($part);
                $this->assertNotFalse($part);
            }

            list($headers, $payload, $signature) = $parts;
            $this->assertJson($headers);
            $this->assertJson($payload);

            // verify signature
            $jwk = JWK::create('ec', ['file' => BASE_PATH . '/keys/ec_secp521r1_public1.pem']);
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
}
