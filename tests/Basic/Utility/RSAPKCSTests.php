<?php
namespace Swiftcore\Jose\Tests\Basic\Utility;

use Swiftcore\Jose\Tests\TestCase;
use Swiftcore\Utility\Base64Url;
use Swiftcore\Utility\RSAPKCS;

class RSAPKCSTests extends TestCase
{
    public function testRawPublicKeyToPKCS()
    {
        $rawKey = require BASE_PATH . '/keys/rsa_public1.php';
        $rawKey = array_map(function ($value) {
            return base64_decode($value);
        }, $rawKey);
        $publicKey = new RSAPKCS($rawKey) . PHP_EOL;
        $this->assertStringEqualsFile(BASE_PATH . '/keys/rsa_public1.pem', strval($publicKey));
    }

    public function testRawPrivateKeyToPKCS()
    {
        $rawKey = require BASE_PATH . '/keys/rsa_unencrypted_private1.php';
        $rawKey = array_map(function ($value) {
            return base64_decode($value);
        }, $rawKey);
        $privateKey = new RSAPKCS($rawKey) . PHP_EOL;
        $this->assertStringEqualsFile(BASE_PATH . '/keys/rsa_unencrypted_private1.pem', strval($privateKey));
    }
}
