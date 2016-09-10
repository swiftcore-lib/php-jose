<?php
namespace Swiftcore\Jose\Element;

use Swiftcore\Base64Url;

abstract class Arrayable extends \ArrayObject
{
    public function json()
    {
        return json_encode($this);
    }

    public function __toString()
    {
        $data = json_encode($this);
        return Base64Url::encode($data);
    }
}
