<?php
namespace Swiftcore\Jose\Algorithm;

class ES384 extends ES
{
    protected $length = 96;

    public function __construct()
    {
        $this->method('sha384');
    }
}
