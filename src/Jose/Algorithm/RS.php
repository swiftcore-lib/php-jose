<?php
namespace Swiftcore\Jose\Algorithm;

use Swiftcore\Jose\JWA;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\Key\RSAKey;

abstract class RS extends JWA
{
    protected $method;

    protected function method($method = null)
    {
        if (!empty($method)) {
            $this->method = $method;
        }

        return $this->method;
    }

    public function sign(RSAKey $jwk, JWS $jws)
    {
        $payload = strval($jws->payload);
        $protected = strval($jws->protected);

        $input = sprintf('%s.%s', $protected, $payload);
        openssl_sign($input, $signature, $jwk->res, $this->method);
        return $signature;
    }

    public function verify(RSAKey $jwk, JWS $jws)
    {
        $signature = $jws->signature->raw();
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $verified = openssl_verify($input, $signature, $jwk->res, $this->method);

        return 1 === $verified;
    }
}
