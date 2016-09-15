<?php
namespace Swiftcore\Jose\Algorithm;

class ES384 extends ES
{
    public function __construct()
    {
        $this->method('sha384');
    }
}
