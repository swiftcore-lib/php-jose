<?php
namespace Swiftcore\Jose\Algorithm;

class ES256 extends ES
{
    protected $length = 64;

    public function __construct()
    {
        $this->method('sha256');
    }
}
