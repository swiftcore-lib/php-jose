<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Base64Url;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;

class SimpleTests extends TestCase
{
    public function testJwsRs256SignatureBasic()
    {
        $start = microtime(true);
        $jwk = new \Swiftcore\Jose\RsaKey(['kty' => 'RSA'], ['file' => 'keys/rsa_private1.pem', 'pwd' => '123123']);
        $jws = new \Swiftcore\Jose\JWS(new Headers(['alg' => 'rs256']), new Payload([]), $jwk);
        echo $jws->signature;
        $stop = microtime(true);

        echo PHP_EOL;
        echo PHP_EOL;
        echo ($stop - $start ) * 1000 . ' ms';
        echo PHP_EOL;
    }
}