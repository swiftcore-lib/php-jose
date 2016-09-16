<?php
namespace Swiftcore\Utility;

use FG\ASN1\ExplicitlyTaggedObject;
use FG\ASN1\Universal\BitString;
use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\ObjectIdentifier;
use FG\ASN1\Universal\OctetString;
use FG\ASN1\Universal\Sequence;

/*
https://tools.ietf.org/html/rfc5915
openssl ecparam -name secp521r1 -genkey -noout -out  ec_secp521r1_private1.pem
*/

final class ECPKCS extends PEM
{
    const TYPE_STRING_PRIVATE = 'EC PRIVATE';
    const TYPE_STRING_PUBLIC = 'PUBLIC';

    public $d;
    public $x;
    public $y;
    public $crv;

    public function __construct(array $data)
    {
        $properties = get_object_vars($this);
        foreach ($properties as $property => $value) {
            if (array_key_exists($property, $data)) {
                $this->$property = $this->isEncoded($property) ? Base64Url::decode($data[$property]) : $data[$property];
            }
        }

        /*
         * ECPrivateKey ::= SEQUENCE {
         * version        INTEGER { ecPrivkeyVer1(1) } (ecPrivkeyVer1),
         * privateKey     OCTET STRING,
         * parameters [0] ECParameters {{ NamedCurve }} OPTIONAL,
         * publicKey  [1] BIT STRING OPTIONAL
         * }
         */

        if (!empty($this->d)) {
            $version = new Integer(1);
            $d       = new OctetString(bin2hex($this->d));
            $octet   = new ExplicitlyTaggedObject(0, new ObjectIdentifier($this->oid()));
            $bits    = new ExplicitlyTaggedObject(1, new BitString('04' . bin2hex($this->x) . bin2hex($this->y)));

            $this->sequence = new Sequence($version, $d, $octet, $bits);
            $this->type = self::TYPE_STRING_PRIVATE;
        } else {
            $oid1     = new ObjectIdentifier('1.2.840.10045.2.1');
            $oid2     = new ObjectIdentifier($this->oid());
            $sequence = new Sequence($oid1, $oid2);
            $bits     = new BitString('04' . bin2hex($this->x) . bin2hex($this->y));

            $this->sequence = new Sequence($sequence, $bits);
            $this->type = self::TYPE_STRING_PUBLIC;
        }
    }

    private function oid($curve = null)
    {
        if (empty($curve)) {
            $curve = $this->crv;
        }
        $oids = [
            'P-256' => '1.2.840.10045.3.1.7',
            'P-384' => '1.3.132.0.34',
            'P-521' => '1.3.132.0.35',
        ];
        return array_key_exists($curve, $oids) ? $oids[$curve] : null;
    }

    private function isEncoded($property)
    {
        return in_array($property, ['d', 'x', 'y']);
    }
}
