<?php
namespace Swiftcore\Jose\Algorithm;

use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\Sequence;
use Swiftcore\Jose\JWA;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Utility\Base64Url;

abstract class ES extends JWA
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
        $signature = bin2hex($signature);
        $part_length = 132;
        if (mb_strlen($signature, '8bit') !== 2 * $part_length) {
            return false;
        }
        $R = mb_substr($signature, 0, $part_length, '8bit');
        $S = mb_substr($signature, $part_length, null, '8bit');

        $oid_sequence = new Sequence();
        $oid_sequence->addChildren([
            new Integer(gmp_strval(gmp_init($R, 16), 10)),
            new Integer(gmp_strval(gmp_init($S, 16), 10)),
        ]);

        $verified = openssl_verify($input, $oid_sequence->getBinary(), $jwk->res, $this->method);

        return 1 === $verified;
    }
}
