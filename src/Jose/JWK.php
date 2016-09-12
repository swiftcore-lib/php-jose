<?php
namespace Swiftcore\Jose;

use Swiftcore\Jose\Element\Headers;

/**
 * Class of JWK implementation
 *
 * Refer to https://tools.ietf.org/html/rfc7517 for more information.
 *
 * A JSON Web Key (JWK) is a JavaScript Object Notation (JSON) data
 * structure that represents a cryptographic key.
 *
 * @package Swiftcore\Jose
 */
class JWK
{
    protected $kty;
    protected $x5c;

    public static function create($type = '', $key)
    {
        $class = __NAMESPACE__ . '\Key\\' . strtoupper($type).'Key';
        return new $class($key);
    }
}
