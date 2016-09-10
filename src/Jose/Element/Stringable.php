<?php
namespace Swiftcore\Jose\Element;

use Swiftcore\Base64Url;

abstract class Stringable
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function raw()
    {
        return $this->data;
    }

    public function __toString()
    {
        return Base64Url::encode($this->data);
    }
}
