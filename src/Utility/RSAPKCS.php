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
         *          INTEGER (2048 bit): 0282010100EB506399F5C612F5A67A09C1192B92FAB53DB28520D859CE0EF6B7D83D40AA1C1DCE2C0720D15A0F531595CAD81BA5D129F91CC6769719F1435872C4BCD0521150A0263B470066489B918BFCA03CE8A0E9FC2C0314C4B096EA30717C03C28CA29E678E63D78ACA1E9A63BDB1261EE7A0B041AB53746D68B57B68BEF37B71382838C95DA8557841A3CA58109F0B4F77A5E929B1A25DC2D6814C55DC0F81CD2F4E5DB95EE70C706FC02C4FCA358EA9A82D8043A47611195580F89458E3DAB5592DEFE06CDE1E516A6C61ED78C13977AE9660A9192CA75CD72967FD3AFAFA1F1A2FF6325A5064D847028F1E6B2329E8572F36E708A549DDA355FC74A32FDD8DBA65
         *          INTEGER (12 bit): 010001
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
        }elseif (!empty($this->e)) {
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
