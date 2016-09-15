<?php
namespace Swiftcore\Jose;

use Swiftcore\Jose\Algorithm\RS;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\Element\Signature;

/**
 * Class of JWS implementation
 *
 * Refer to https://tools.ietf.org/html/rfc7515 for more information.
 *
 * JSON Web Signature (JWS) represents content secured with digital
 * signatures or Message Authentication Codes (MACs) using JSON-based
 * data structures.  Cryptographic algorithms and identifiers for use
 * with this specification are described in the separate JSON Web
 * Algorithms (JWA) specification and an IANA registry defined by that
 * specification.  Related encryption capabilities are described in the
 * separate JSON Web Encryption (JWE) specification.
 *
 * @package Swiftcore\Jose
 * @example "index.php" 5 2 What?
 */
class JWS
{
    /**
     * JWS Signer
     *
     * It is responsible for signature signing and/or verifying.
     *
     * @see \Swiftcore\Jose\Algorithm\RS256    RS256
     * @see \Swiftcore\Jose\Algorithm\RS384    RS384
     * @see \Swiftcore\Jose\Algorithm\RS512    RS512
     *
     * @var RS
     */
    protected $signer;

    /**
     * JWS protected headers
     *
     * JSON object that contains the Header Parameters that are integrity
     * protected by the JWS Signature digital signature or MAC operation.
     * For the JWS Compact Serialization, this comprises the entire JOSE
     * Header.  For the JWS JSON Serialization, this is one component of
     * the JOSE Header.
     *
     * @var Headers
     */
    public $protected;

    /**
     * JWS Unprotected Header
     *
     * JSON object that contains the Header Parameters that are not
     * integrity protected.  This can only be present when using the JWS
     * JSON Serialization.
     *
     * @var Headers
     */
    public $headers;

    /**
     * JWS Payload
     *
     * The sequence of octets to be secured -- a.k.a. the message.  The
     * payload can contain an arbitrary sequence of octets.
     *
     * @var Payload
     */
    public $payload;

    /**
     *
     *
     * @var Signature
     */
    public $signature;

    /**
     * JWS verification indicator
     *
     * @var bool
     */
    public $verified = false;

    /**
     * JWK Instance
     *
     * This holds the instance of JWK for signature signing and/or verifying
     *
     * @var JWK
     */
    public $jwk;

    /**
     * JWS constructor
     *
     * @param JWK|null $jwk
     * @param Payload|null $payload
     * @param Headers|null $protected
     * @param Signature|null $signature
     */
    public function __construct(
        JWK $jwk = null,
        Payload $payload = null,
        Headers $protected = null,
        Signature $signature = null
    ) {
//        $protected['b64'] = true;
//        $protected['crit'] = ['b64', 'alg'];

        $this->protected = $protected;
        $this->payload = $payload;
        $this->jwk = $jwk;
        $this->signature = $signature;
    }

    /**
     * @return $this
     */
    public function sign()
    {
        $signer = $this->signer();
        $signer = new $signer;
        /** @noinspection PhpUndefinedMethodInspection */
        $this->signature = new Signature($signer->sign($this->jwk, $this));
        $this->verified = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function verify()
    {
        $verifier = $this->signer();
        $verifier = new $verifier;
        /** @noinspection PhpUndefinedMethodInspection */
        $this->verified = $verifier->verify($this->jwk, $this);

        return $this;
    }

    /**
     * @return mixed
     */
    protected function signer()
    {
        $algorithm = __NAMESPACE__ . '\Algorithm\\' . strtoupper($this->protected['alg']);
        $this->signer = new $algorithm;

        return $this->signer;
    }
}
