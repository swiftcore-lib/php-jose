<?php
namespace Swiftcore\Jose\Algorithm;

class HS256 extends RS
{
    public function __construct()
    {
        $this->method('SHA256');
    }
}
