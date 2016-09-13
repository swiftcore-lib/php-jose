<?php
namespace Swiftcore\Jose;

abstract class JWA
{
    protected $method;

    protected function method($method = null)
    {
        if (!empty($method)) {
            $this->method = $method;
        }

        return $this->method;
    }

    abstract public function sign(JWK $jwk, JWS $jws);

    abstract public function verify(JWK $jwk, JWS $jws);
}
