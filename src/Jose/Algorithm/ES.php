<?php
namespace Swiftcore\Jose\Algorithm;

use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\Sequence;
use Swiftcore\Jose\JWA;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Utility\Base64Url;
use FG\ASN1\Object;

abstract class ES extends JWA
{
    protected $length;

    public function sign(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        openssl_sign($input, $signature, $jwk->res, $this->method);
        $asn = Object::fromBinary($signature);
        $signature = null;
        foreach ($asn->getChildren() as $child) {
            /* @var $child \FG\ASN1\Universal\Integer */
            $signature .= hex2bin(str_pad(gmp_strval(gmp_init(
                $child->getContent()
            ), 16), $this->length(), '0', STR_PAD_LEFT));
        }

        return $signature;
    }

    public function verify(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $signature = $jws->signature->raw();
        $signature = bin2hex($signature);
        $R = mb_substr($signature, 0, $this->length(), '8bit');
        $S = mb_substr($signature, $this->length(), null, '8bit');

        $sequence = new Sequence(
            new Integer(gmp_strval(gmp_init($R, 16), 10)),
            new Integer(gmp_strval(gmp_init($S, 16), 10))
        );

        $verified = openssl_verify($input, $sequence->getBinary(), $jwk->res, $this->method);

        return 1 === $verified;
    }

    protected function length($length = null)
    {
        if (!empty($length)) {
            $this->length = $length;
        }

        return $this->length;
    }
}
