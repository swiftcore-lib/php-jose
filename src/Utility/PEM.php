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

abstract class PEM
{
    protected $sequence;
    protected $type;

    public function __toString()
    {
        $key = chunk_split(base64_encode($this->sequence->getBinary()), 64, PHP_EOL);
        $key = "-----BEGIN ".$this->type." KEY-----" . PHP_EOL . $key . "-----END ".$this->type." KEY-----";

        return $key;
    }
}
