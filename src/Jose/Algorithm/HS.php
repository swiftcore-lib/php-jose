<?php
namespace Swiftcore\Jose\Algorithm;

use Swiftcore\Jose\JWA;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Key\RSAKey;

abstract class HS extends JWA
{
    public function sign(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $signature = hash_hmac($this->method, $input, $jwk->k, true);

        return $signature;
    }

    public function verify(JWK $jwk, JWS $jws)
    {
        $signature = $jws->signature->raw();
        $expected = $this->sign($jwk, $jws);
        $verified = hash_equals($expected, $signature);

        return $verified;
    }
}
