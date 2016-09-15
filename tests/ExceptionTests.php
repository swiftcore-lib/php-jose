<?php
namespace Swiftcore\Jose\Tests;

use Swiftcore\Jose\Exception\InvalidECKeyArgumentException;
use Swiftcore\Jose\Exception\InvalidRSAKeyArgumentException;
use Swiftcore\Jose\Key\ECKey;
use Swiftcore\Jose\Key\RSAKey;

class ExceptionTests extends TestCase
{
    public function testInvalidRSAKeyArgumentException1()
    {
        $this->expectException(InvalidRSAKeyArgumentException::class);
        try {
            new RSAKey(111);
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
        new RSAKey('');
    }

    public function testInvalidECKeyArgumentException1()
    {
        $this->expectException(InvalidECKeyArgumentException::class);
        try {
            new ECKey(111);
        } catch (InvalidECKeyArgumentException $e) {
            $hasErrors = $e->hasErrors();
            $this->assertEquals(1, $hasErrors);
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            throw $e;
        }
    }

    public function testInvalidECKeyArgumentException2()
    {
        $this->expectException(InvalidECKeyArgumentException::class);
        new ECKey('');
    }
}
