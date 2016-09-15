<?php
namespace Swiftcore\Jose\Key;

use Swiftcore\Jose\Exception\InvalidECKeyArgumentException;
use Swiftcore\Jose\Exception\InvalidJwkException;
use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\JWK;

/**
 * Class RSAKey
 * @package Swiftcore\Jose\Key
 */
final class ECKey extends JWK
{
    /**
     * @var string
     */
    private $n;
    /**
     * @var string
     */
    private $e;
    /**
     * @var string
     */
    private $d;
    /**
     * @var string
     */
    private $p;
    /**
     * @var string
     */
    private $q;
    /**
     * @var string
     */
    private $dp;
    /**
     * @var string
     */
    private $dq;
    /**
     * @var string
     */
    private $qi;
    /**
     * @var string
     */
    private $oth;

    public $res;

    public static $RSA_OPENSSL_JOSE_MAPPING = [
        'n' => 'n',
        'd' => 'd',
        'e' => 'e',
        'p' => 'p',
        'q' => 'q',
        'dmp1' => 'dp',
        'dmq1' => 'dq',
        'iqmp' => 'qi',
    ];

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
                throw new InvalidRSAKeyArgumentException(["Parameter type: {$type} is not supported."]);
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
