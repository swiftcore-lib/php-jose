<?php
namespace Swiftcore\Jose;

use Swiftcore\Exception\InvalidJwkException;

abstract class JWK
{
    public $key;
    public $headers;
    public $signature;
}
