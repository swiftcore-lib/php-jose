<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Element\Headers;
use Swiftcore\Jose\Element\Signature;

class ElementToStringTests extends TestCase
{
    public function testHeadersToString()
    {
        $data = [
            'use' => 'sig',
            'crit' => [
                'exp',
                'b64'
            ]
        ];
        $headers = new Headers($data);
        $expectedJson = '{"use":"sig","crit":["exp","b64"]}';
        $expectedEncoded = 'eyJ1c2UiOiJzaWciLCJjcml0IjpbImV4cCIsImI2NCJdfQ';

        $this->assertEquals($expectedJson, $headers->json());
        $this->assertEquals($expectedEncoded, sprintf('%s', $headers));
    }

    public function testSignatureToString()
    {
        $data = 'The quick brown fox jumps over the lazy dog.';
        $signature = new Signature($data);
        $expectedRaw = 'The quick brown fox jumps over the lazy dog.';
        $expectedEncoded = 'VGhlIHF1aWNrIGJyb3duIGZveCBqdW1wcyBvdmVyIHRoZSBsYXp5IGRvZy4';

        $this->assertEquals($expectedRaw, $signature->raw());
        $this->assertEquals($expectedEncoded, sprintf('%s', $signature));
    }
}
