<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Base64Url;
use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Payload;
use Swiftcore\Jose\JWK;
use Swiftcore\Jose\JWS;
use Swiftcore\Jose\RsaKey;

class Base64UrlTests extends TestCase
{
    public function testBase64UrlEncode()
    {
        $plaintext = file_get_contents(BASE_PATH . '/assets/ipsum_base64url_plaintext.txt');
        $encoded = chunk_split(Base64Url::encode($plaintext), 64, PHP_EOL);

        $this->assertStringEqualsFile(BASE_PATH . '/assets/ipsum_base64url_encoded_splited.txt', $encoded);

        $plaintext = file_get_contents(BASE_PATH . '/assets/chinese_base64url_plaintext.txt');
        $encoded = Base64Url::encode($plaintext);

        $this->assertStringEqualsFile(BASE_PATH . '/assets/chinese_base64url_encoded.txt', $encoded);
    }

    public function testBase64UrlDecode()
    {
        $encoded =  file_get_contents(BASE_PATH . '/assets/ipsum_base64url_encoded_splited.txt');
        $decoded = Base64Url::decode($encoded);
        $this->assertStringEqualsFile(BASE_PATH . '/assets/ipsum_base64url_plaintext.txt', $decoded);

        $encoded =  file_get_contents(BASE_PATH . '/assets/chinese_base64url_encoded.txt');
        $decoded = Base64Url::decode($encoded);
        $this->assertStringEqualsFile(BASE_PATH . '/assets/chinese_base64url_plaintext.txt', $decoded);
    }
}
