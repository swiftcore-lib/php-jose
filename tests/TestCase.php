<?php
namespace Swiftcore\Jose\Tests;

define('BASTH_PATH', __DIR__);

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function getkeys()
    {
        $rsaPrivate1 = file_get_contents(BASTH_PATH . '/keys/rsa_private.pem');
    }
}
