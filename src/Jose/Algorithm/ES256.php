<?php
namespace Swiftcore\Jose\Algorithm;

class ES256 extends ES
{
    public function __construct()
    {
        $this->method('sha256');
    }
}
