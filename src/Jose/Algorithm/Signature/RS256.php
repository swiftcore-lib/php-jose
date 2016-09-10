<?php
namespace Swiftcore\Jose\Algorithm\Signature;

use Swiftcore\Base64Url;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;

class RS256
{
    public static function sign(JWK $jwk, JWS $jws)
    {
        $payload = strval($jws->payload);
        $protected = strval($jws->protected);

        $input = sprintf('%s.%s', $protected, $payload);

        openssl_sign($input, $signature, $jwk->key, 'SHA256');
        return $signature;
    }

    public static function verify(JWK $jwk, JWS $jws)
    {
        $signature = $jws->signature->raw();
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $verified = openssl_verify($input, $signature, $jwk->key, 'SHA256');

        return 1 === $verified;
    }
}