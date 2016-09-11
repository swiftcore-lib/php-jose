<?php
namespace Swiftcore\Jose\Algorithm\Signature;

use Swiftcore\Jose\Algorithm\RSASHA;

class RS256 extends RSASHA
{
    public function __construct()
    {
        $this->method('SHA256');
    }
}
