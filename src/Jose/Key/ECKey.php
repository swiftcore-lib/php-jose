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

        $this->res = $res;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublicKey()
    {
        return !$this->isPrivateKey();
    }

    /**
     * @return bool
     */
    public function isPrivateKey()
    {
        return !empty($this->d);
    }
}
