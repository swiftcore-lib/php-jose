<?php
namespace Swiftcore\Jose;

use Swiftcore\Jose\Algorithm\Signature\RS256;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;

class JWS
{
    public $protected;
    public $headers;
    public $payload;
    public $signature;
    public $verified = false;
    public $jwk;

    public function __construct(JWK $jwk = null, Payload $payload = null, Headers $protected = null)
    {
        $protected['b64'] = true;
        $protected['crit'] = ['b64', $protected['alg']];

        $this->protected = $protected;
        $this->payload = $payload;
        $this->jwk = $jwk;
    }

    public function sign()
    {
        $algorithm = __NAMESPACE__ . '\Algorithm\Signature\\' . strtoupper($this->protected['alg']);

        $signer = new $algorithm;
        $this->signature = new Signature($signer::sign($this->jwk, $this));
        $this->verified = true;

        return $this;
    }
}