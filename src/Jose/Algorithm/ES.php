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
    public function sign(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        openssl_sign($input, $signature, $jwk->res, $this->method);
        /*
         * @see https://tools.ietf.org/html/rfc7518#section-3.4
         *
         * Note that the Integer-to-OctetString Conversion
         * defined in Section 2.3.7 of SEC1 [SEC1] used to represent R and S as
         * octet sequences adds zero-valued high-order padding bits when needed
         * to round the size up to a multiple of 8 bits; thus, each 521-bit
         * integer is represented using 528 bits in 66 octets.)
         */
        $length = ceil(openssl_pkey_get_details($jwk->res)['bits'] / 8) * 2;
        $asn = Object::fromBinary($signature);
        $signature = null;
        foreach ($asn->getChildren() as $child) {
            /* @var $child \FG\ASN1\Universal\Integer */
            $content = $child->getContent();
            $content = gmp_strval(gmp_init($content), 16);
            $content = str_pad($content, $length, '0', STR_PAD_LEFT);
            $content = hex2bin($content);

            $signature .= $content;
        }

        return $signature;
    }

    public function verify(JWK $jwk, JWS $jws)
    {
        $input = sprintf('%s.%s', $jws->protected, $jws->payload);

        $signature = $jws->signature->raw();
        $signature = bin2hex($signature);
        /*
         * @see https://tools.ietf.org/html/rfc7518#section-3.4
         *
         * Note that the Integer-to-OctetString Conversion
         * defined in Section 2.3.7 of SEC1 [SEC1] used to represent R and S as
         * octet sequences adds zero-valued high-order padding bits when needed
         * to round the size up to a multiple of 8 bits; thus, each 521-bit
         * integer is represented using 528 bits in 66 octets.)
         */
        $length = ceil(openssl_pkey_get_details($jwk->res)['bits'] / 8) * 2;
        $R = mb_substr($signature, 0, $length, '8bit');
        $S = mb_substr($signature, $length, null, '8bit');

        $sequence = new Sequence(
            new Integer(gmp_strval(gmp_init($R, 16), 10)),
            new Integer(gmp_strval(gmp_init($S, 16), 10))
        );

        $verified = openssl_verify($input, $sequence->getBinary(), $jwk->res, $this->method);

        return 1 === $verified;
    }
}
