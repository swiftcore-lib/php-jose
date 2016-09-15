<?php
namespace Swiftcore\Jose\Algorithm;

class HS512 extends HS
{
    public function __construct()
    {
        $this->method('sha512');
    }
}
