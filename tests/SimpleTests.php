<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\RsaKey;

class SimpleTests extends TestCase
{
    public function testJwsRs256SignatureBasic()
    {
        $jwk = JWK::create(new Headers(['kty' => 'RSA']), ['file' => BASE_PATH . '/keys/rsa_private1.pem',
            'pwd' => '123123']);

        $headers = new Headers(['alg' => 'rs256']);
        $payload = new Payload('The quick brown fox jumps over the lazy dog.');
        $jws = new JWS($jwk, $payload, $headers);
        $jws->sign();
        $expectedSignature = 'JDTYtvoN4u4m02JAOt7AX4U0STLhrSqcd48W_ktZGI4B5h2aLya-q96RD-'.
            '1jPktFVNcY4zGqAy5TLD9k765i8J_6HLFqHibNTG8WOrPGWYRmF6inT1t66D55MMyIcyyvusrM3_'.
            '5SLq0YumEcH-nebUEvvU0A5YVz3LvAx5ugFzSv2K3HhRQuN3V6hn9om9xJL0O4geUTS2DI52zhAbziWho_'.
            'TNHgqzpI6bScdFgYNFZ_ToP-EnU4f97TvrUGLgnnsoevgrphaAyBGn5kmMnIrxbftxtai3HfUe59HI-'.
            'VetknIXIYY0PoWfFabCLtTdFD42n7sQeI2crKjgT1ko6vAw';

        $this->assertEquals($expectedSignature, $jws->signature);
    }
}
