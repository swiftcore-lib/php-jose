<?php
namespace Swiftcore\Jose\Algorithm;

class RS384 extends RS
{
    public function __construct()
    {
        $this->method('sha384');
    }
}
