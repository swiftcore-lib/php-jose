<?php
namespace Swiftcore\Jose;

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
    public $res;

    public static function create($kty, $key = null)
    {
        $class = __NAMESPACE__ . '\Key\\' . strtoupper($kty).'Key';
        return new $class($key);
    }
}
