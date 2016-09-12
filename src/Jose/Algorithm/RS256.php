<?php
namespace Swiftcore\Jose\Algorithm;

class RS256 extends RS
{
    public function __construct()
    {
        $this->method('SHA256');
    }
}
