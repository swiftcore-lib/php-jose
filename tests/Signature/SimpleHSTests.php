<?php
namespace Swiftcore\Jose\Tests\Signature;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Tests\TestCase;

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
        $expectedSignature = 'z0T35NxshKcYCU1jBY_inp8pI4Lzr08UUuP0fQs9TlI';

        $this->assertEquals($expectedHeaders, strval($jws->protected));
        $this->assertEquals($expectedPayload, strval($jws->payload));
        $this->assertEquals($expectedSignature, strval($jws->signature));
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
            'HS256' => 'z0T35NxshKcYCU1jBY_inp8pI4Lzr08UUuP0fQs9TlI',
            'HS384' => 'DlIEB_AO13juJyJThI9hFzbJ6tJ3N_GpLNRQ20kui8DgEKQ9D6NnOqfE0vVvUcf0',
            'HS512' => '0dyNt9d7vcnHiWU174hQ9M6jaIXX90pHjoGAi8YhYbflIkHeWRm7hAOL9Wr8uGBp3dvzRAxX3E-MIZ0l0ljVUQ',
        ];
        foreach ($methods as $method) {
            $privateKeyContent = file_get_contents(BASE_PATH . '/keys/rsa_unencrypted_private1.pem');
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
