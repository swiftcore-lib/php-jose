<?php
namespace Swiftcore\Jose\Algorithm\Signature;

use Swiftcore\Jose\Algorithm\RSASHA;

class RS384 extends RSASHA
{
    public function __construct()
    {
        $this->method('SHA384');
    }
}
