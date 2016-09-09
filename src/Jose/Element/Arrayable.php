<?php
namespace Swiftcore\Jose\Element;

use Swiftcore\Base64Url;

abstract class Arrayable extends \ArrayObject
{
    public function __toString()
    {
        print_r($this);
        $data = is_array($this->data) ? json_encode($this->data) : $this->data;
        return Base64Url::encode($data);
    }
}
