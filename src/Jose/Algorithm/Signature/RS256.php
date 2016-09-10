<?php
namespace Swiftcore\Jose\Algorithm\Signature;

use Swiftcore\Base64Url;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;

class RS256
{
    public static function sign(JWK $jwk, JWS $jws)
    {
        $payload = strval($jws->payload).PHP_EOL;
        $protected = strval($jws->protected);

        $input = sprintf('%s.%s', $protected, $payload);

        openssl_sign($input, $signature, $jwk->key, 'sha256');
        return $signature;
    }
}