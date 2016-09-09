<?php
namespace Swiftcore\Jose;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;

class JWS
{
    public $headers;
    public $payload;
    public $signature;
    public $jwk;

    public function __construct(Headers $headers = null, Payload $payload = null, JWK $jwk = null)
    {
        $this->headers = $headers;
        $this->payload = $payload;
        $this->jwk = $jwk;
        $this->sign();
    }

    protected function sign()
    {
        $algorithm = __NAMESPACE__ . '\Algorithm\Signature\\' . strtoupper($this->headers['alg']);

        $signature = new $algorithm;
        $this->signature = new Signature($signature->sign($this->jwk, $this));

        return $this;
    }
}