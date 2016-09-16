<?php
namespace Swiftcore\Jose\Algorithm;

use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\Sequence;
use Swiftcore\Jose\JWA;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Utility\Base64Url;

abstract class RS extends JWA
{
    public function sign(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        openssl_sign($input, $signature, $jwk->res, $this->method);

        return $signature;
    }

    public function verify(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $signature = $jws->signature->raw();

        $verified = openssl_verify($input, $signature, $jwk->res, $this->method);

        return 1 === $verified;
    }
}
