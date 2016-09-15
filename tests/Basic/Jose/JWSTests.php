<?php
namespace Swiftcore\Jose\Tests\Basic\Jose;

use Swiftcore\Jose\Key\ECKey;
use Swiftcore\Jose\Key\RSAKey;
use Swiftcore\Jose\Tests\TestCase;
use Swiftcore\Utility\Base64Url;

class JWSTests extends TestCase
{
    public function testRSAAllMethods()
    {
        $key = new RSAKey();
        $this->assertEquals(RSAKey::class, get_class($key));

        $privateKey = new RSAKey(BASE_PATH . '/keys/rsa_private1.pem', '123123');
        $isPrivateKey = $privateKey->isPrivateKey();
        $this->assertTrue($isPrivateKey);

        $publicKey = new RSAKey(BASE_PATH . '/keys/rsa_public1.pem', '123123');
        $isPublicKey = $publicKey->isPublicKey();
        $this->assertTrue($isPublicKey);
    }

    public function testECAllMethods()
    {
        $key = new ECKey();
        $this->assertEquals(ECKey::class, get_class($key));

        $privateKey = new ECKey(BASE_PATH . '/keys/ec_secp521r1_private1.pem');

    }
}
