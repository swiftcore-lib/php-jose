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
    protected $data;
    protected $registries = ['kty', 'use', 'key_ops', 'alg', 'kid', 'x5u', 'x5c', 'x5t', 'x5t#S256'];

    public static function create(Headers $headers, array $additions = [])
    {
        $keyType = __NAMESPACE__ . '\\' . ucfirst(strtolower($headers['kty'])).'Key';
        return new $keyType($headers, $additions);
    }
}
