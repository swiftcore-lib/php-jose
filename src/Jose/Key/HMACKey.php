<?php
namespace Swiftcore\Jose\Key;

use Swiftcore\Jose\Exception\InvalidJwkException;
use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\JWK;

/**
 * Class RSAKey
 * @package Swiftcore\Jose\Key
 */
final class HMACKey extends JWK
{
    private $k;

    public function __construct($key = null)
    {
        $this->k = $key;
    }
}
