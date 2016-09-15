<?php
namespace Swiftcore\Jose\Algorithm;

class ES512 extends ES
{
    public function __construct()
    {
        $this->method('sha512');
    }
}
