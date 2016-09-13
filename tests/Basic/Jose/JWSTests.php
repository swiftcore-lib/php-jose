<?php
namespace Swiftcore\Jose\Tests\Basic\Jose;

use Swiftcore\Jose\Key\RSAKey;
use Swiftcore\Jose\Tests\TestCase;
use Swiftcore\Utility\Base64Url;

class JWSTests extends TestCase
{
    public function testAllMethods()
    {
        $privateKey = new RSAKey(BASE_PATH . '/keys/rsa_private1.pem', '123123');
        $isPrivateKey = $privateKey->isPrivateKey();
        $this->assertTrue($isPrivateKey);

        $publicKey = new RSAKey(BASE_PATH . '/keys/rsa_public1.pem', '123123');
        $isPublicKey = $publicKey->isPublicKey();
        $this->assertTrue($isPublicKey);

        $key = new RSAKey();
        $this->assertEquals(RSAKey::class, get_class($key));
    }
}
