<?php
namespace Swiftcore\Jose\Algorithm;

use Swiftcore\Jose\Algorithm\RS;

class RS512 extends RS
{
    public function __construct()
    {
        $this->method('SHA512');
    }
}
