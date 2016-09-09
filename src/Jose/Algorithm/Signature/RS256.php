<?php
namespace Swiftcore\Jose\Algorithm\Signature;

use Swiftcore\Base64Url;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;

class RS256
{
    public static function sign(JWK $jwk, JWS $jws)
    {
        $payload = $jws->payload;
        $payload = Base64Url::encode(is_string($payload) ? $payload : json_encode($payload));

        $headers = $jws->headers;
        $headers = Base64Url::encode(json_encode($headers));

        $input = sprintf('%s.%s', $headers, $payload);

        openssl_sign($input, $signature, $jwk->key, 'sha256');
        return $signature;
    }
}