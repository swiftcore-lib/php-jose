<?php
namespace Swiftcore\Jose\Algorithm;

class RS512 extends RS
{
    public function __construct()
    {
        $this->method('SHA512');
    }
}
