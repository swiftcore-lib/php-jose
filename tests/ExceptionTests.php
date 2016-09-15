<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\Key\RSAKey;

class ExceptionTests extends TestCase
{
    public function testInvalidRSAKeyArgumentException1()
    {
        $this->expectException(InvalidRSAKeyArgumentException::class);
        try {
            $key = new RSAKey(111);
        } catch (InvalidRSAKeyArgumentException $e) {
            $hasErrors = $e->hasErrors();
            $this->assertEquals(1, $hasErrors);
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            throw $e;
        }
    }

    public function testInvalidRSAKeyArgumentException2()
    {
        $this->expectException(InvalidRSAKeyArgumentException::class);
        $key = new RSAKey('');
    }
}
