<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\RsaKey;

class SimpleTests extends TestCase
{
    public function testJwsRs256SignatureBasic()
    {
        $start = microtime(true);
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), ['file' => BATH_PATH . '/keys/rsa_private1.pem', 'pwd' => '123123']);

        $headers = new Headers(['alg' => 'rs256']);
        $payload = new Payload('dd - '.rand(0, PHP_INT_MAX));
        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();
        $stop = microtime(true);

        echo PHP_EOL;
        echo PHP_EOL;
        echo ($stop - $start ) * 1000 . ' ms';
        echo PHP_EOL;
    }
}