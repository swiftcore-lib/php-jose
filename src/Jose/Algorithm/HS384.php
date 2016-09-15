<?php
namespace Swiftcore\Jose\Algorithm;

class HS384 extends HS
{
    public function __construct()
    {
        $this->method('sha384');
    }
}
