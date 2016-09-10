<?php
namespace Swiftcore\Jose\Tests;
define('BASE_PATH', __DIR__);

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
        $rsaPrivate1 = file_get_contents(BASE_PATH . '/keys/rsa_private.pem');
    }
}
