<?php
namespace Swiftcore\Jose\Algorithm;

class HS256 extends HS
{
    public function __construct()
    {
        $this->method('sha256');
    }
}
