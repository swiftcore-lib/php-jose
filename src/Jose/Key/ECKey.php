<?php
namespace Swiftcore\Jose\Key;

use Swiftcore\Jose\Exception\InvalidECKeyArgumentException;
use Swiftcore\Jose\Exception\InvalidJwkException;
use Swiftcore\Jose\JWK;

/**
 * Class ECKey
 * @package Swiftcore\Jose\Key
 */
final class ECKey extends JWK
{
    /**
     * @var string
     */
    private $x;
    /**
     * @var string
     */
    private $y;
    /**
     * @var string
     */
    private $d;

    public $res;
    public $length;

    /**
     * ECKey constructor.
     *
     * @param array|string|null $key
     */
    public function __construct($key = null)
    {
        $type = gettype($key);
        switch ($type) {
            case 'NULL':
                return $this;
            case 'array':
                $key = array_values($key);
                break;
            case 'string':
                $key = func_get_args();
                break;
            default:
                throw new InvalidECKeyArgumentException(["Parameter type: {$type} is not supported."]);
                break;
        }

        $count = count($key);
        $pem = $key[0];
        if (file_exists($pem)) {
            $pem = file_get_contents($pem);
        }
        $passphrase = null;
        if ($count > 1) {
            $passphrase = $key[1];
        }
        $this->loadPEM($pem, $passphrase);

        return $this;
    }

    /**
     * @param $pem
     * @param $passphrase
     * @return $this
     */
    public function loadPEM($pem, $passphrase)
    {
        $res = openssl_pkey_get_private($pem, $passphrase);
        if (false === $res) {
            $res = openssl_pkey_get_public($pem);
        }

        if (false === $res) {
            throw new InvalidECKeyArgumentException(["EC key seems to be invalid."], 'Failed to load EC Key');
        }

        /*
         * @see https://tools.ietf.org/html/rfc7518#section-3.4
         *
         * Note that the Integer-to-OctetString Conversion
         * defined in Section 2.3.7 of SEC1 [SEC1] used to represent R and S as
         * octet sequences adds zero-valued high-order padding bits when needed
         * to round the size up to a multiple of 8 bits; thus, each 521-bit
         * integer is represented using 528 bits in 66 octets.)
         */
        $this->length = ceil(openssl_pkey_get_details($res)['bits'] / 8) * 2;
        $this->res = $res;

        return $this;
    }

    /**
     * @return bool
     */
    // TODO: extract from PEM to Object and return
    /*public function isPublicKey()
    {

         return !$this->isPrivateKey();
    }*/

    /**
     * @return bool
     */
    // TODO: extract from PEM to Object and return

    /*public function isPrivateKey()
    {
        return !empty($this->d);
    }*/
}
