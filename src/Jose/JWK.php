<?php
namespace Swiftcore\Jose;

use Swiftcore\Exception\InvalidJwkException;
use Swiftcore\Jose\Element\Headers;

class JWK
{
    public $key;
    public $headers;
    public $signature;

    public static function create(Headers $headers, array $additions = [])
    {
        $keyType = __NAMESPACE__ . '\\' . ucfirst(strtolower($headers['kty'])).'Key';
        return new $keyType($headers, $additions);
    }
}