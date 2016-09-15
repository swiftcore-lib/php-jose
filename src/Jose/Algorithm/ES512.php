<?php
namespace Swiftcore\Jose\Algorithm;

class ES512 extends ES
{
    protected $length = 132;

    public function __construct()
    {
        $this->method('sha512');
    }
}
