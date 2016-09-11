<?php
namespace Swiftcore\Utility;

use FG\ASN1\OID;
use FG\ASN1\Universal\BitString;
use FG\ASN1\Universal\Integer;
use FG\ASN1\Universal\NullObject;
use FG\ASN1\Universal\ObjectIdentifier;
use FG\ASN1\Universal\Sequence;

/*
http://stackoverflow.com/questions/1193529/how-to-store-retrieve-rsa-public-private-key
http://stackoverflow.com/questions/18039401/how-can-i-transform-between-the-two-styles-of-public-key-format-one-begin-rsa
https://www.cryptologie.net/article/262/what-are-x509-certificates-rfc-asn1-der/
*/

final class RSAPKCS
{
    const TYPE_STRING_PRIVATE = 'RSA PRIVATE';
    const TYPE_STRING_PUBLIC = 'PUBLIC';

    private $n;
    private $e;
    private $d;
    private $p;
    private $q;
    private $dmp1;
    private $dmq1;
    private $iqmp;

    private $sequence;
    private $type;

    public function __construct(array $data)
    {
        $properties = get_object_vars($this);
        foreach ($properties as $property => $value) {
            if (array_key_exists($property, $data)) {
                $this->$property = $data[$property];
            }
        }

        /*
         * PublicKeyInfo ::= SEQUENCE {
         *   algorithm       AlgorithmIdentifier,
         *   PublicKey       BIT STRING
         * }
         * AlgorithmIdentifier ::= SEQUENCE {
         *   algorithm       OBJECT IDENTIFIER,
         *   parameters      ANY DEFINED BY algorithm OPTIONAL
         * }
         *
         * RSA PKCS#1: 1.2.840.113549.1.1.1
         * SEQUENCE (2 elements)
         *    SEQUENCE (2 elements)
         *       OBJECT IDENTIFIER 1.2.840.113549.1.1.1
         *       NULL
         *    BIT STRING (1 element)
         *       SEQUENCE (2 elements)
         *          INTEGER (2048 bit)
         *          INTEGER (12 bit)
         *
         * RSAPrivateKey ::= SEQUENCE {
         *   version           Version,
         *   modulus           INTEGER,  -- n
         *   publicExponent    INTEGER,  -- e
         *   privateExponent   INTEGER,  -- d
         *   prime1            INTEGER,  -- p
         *   prime2            INTEGER,  -- q
         *   exponent1         INTEGER,  -- d mod (p-1)
         *   exponent2         INTEGER,  -- d mod (q-1)
         *   coefficient       INTEGER,  -- (inverse of q) mod p
         *   otherPrimeInfos   OtherPrimeInfos OPTIONAL
         * }
         *
         */

        if (!empty($this->d)) {
            // this should be a private key
            $version = new Integer(0);
            $n = new Integer(gmp_strval(gmp_import($this->n), 10));
            $e = new Integer(gmp_strval(gmp_import($this->e), 10));
            $d = new Integer(gmp_strval(gmp_import($this->d), 10));
            $p = new Integer(gmp_strval(gmp_import($this->p), 10));
            $q = new Integer(gmp_strval(gmp_import($this->q), 10));
            $dmp1 = new Integer(gmp_strval(gmp_import($this->dmp1), 10));
            $dmq1 = new Integer(gmp_strval(gmp_import($this->dmq1), 10));
            $iqmp = new Integer(gmp_strval(gmp_import($this->iqmp), 10));

            $this->sequence = new Sequence($version, $n, $e, $d, $p, $q, $dmp1, $dmq1, $iqmp);
            $this->type = self::TYPE_STRING_PRIVATE;
        } elseif (!empty($this->e)) {
            // this should be a public key
            $n = new Integer(gmp_strval(gmp_import($this->n), 10));
            $e = new Integer(gmp_strval(gmp_import($this->e), 10));
            $sequence2 = new Sequence($n, $e);
            $sequence2 = '0x'.gmp_strval(gmp_import($sequence2->getBinary()), 16);
            $bit = new BitString($sequence2);
            $objectIdentifier = new ObjectIdentifier(OID::RSA_ENCRYPTION);
            $sequence1 = new Sequence($objectIdentifier, new NullObject());

            $this->sequence = new Sequence($sequence1, $bit);
            $this->type = self::TYPE_STRING_PUBLIC;
        }
    }

    public function __toString()
    {
        $key = chunk_split(base64_encode($this->sequence->getBinary()), 64, PHP_EOL);
        $key = "-----BEGIN ".$this->type." KEY-----" . PHP_EOL . $key . "-----END ".$this->type." KEY-----";

        return $key;
    }
}
