<?php
namespace Swiftcore\Jose;

use Swiftcore\Jose\Algorithm\Signature\RS256;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;

class JWS
{
    protected $signer;

    public $protected;
    public $headers;
    public $payload;
    public $signature;
    public $verified = false;
    public $jwk;

    public function __construct(
        JWK $jwk = null,
        Payload $payload = null,
        Headers $protected = null,
        Signature $signature = null
    ) {
        $protected['b64'] = true;
        $protected['crit'] = ['b64', 'alg'];

        $this->protected = $protected;
        $this->payload = $payload;
        $this->jwk = $jwk;
        $this->signature = $signature;
    }

    public function sign()
    {
        $signer = $this->signer();
        $this->signature = new Signature($signer::sign($this->jwk, $this));
        $this->verified = true;

        return $this;
    }

    public function verify()
    {
        $verifier = $this->signer();
        $this->verified = $verifier::verify($this->jwk, $this);

        return $this;
    }

    protected function signer()
    {
        $algorithm = __NAMESPACE__ . '\Algorithm\Signature\\' . strtoupper($this->protected['alg']);
        $this->signer = new $algorithm;

        return $this->signer;
    }
}
