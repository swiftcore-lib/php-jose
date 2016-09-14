<?php
namespace Swiftcore\Jose\Key;

use Swiftcore\Jose\Exception\InvalidJwkException;
use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\JWK;
use Swiftcore\Utility\Base64Url;

/**
 * Class RSAKey
 * @package Swiftcore\Jose\Key
 */
final class HMACKey extends JWK
{
    public $k;

    public function __construct($key = null)
    {
        $this->k = Base64Url::decode($key);
    }
}
