<?php
namespace Swiftcore\Jose\Exception;

trait ExceptionErrorsTrait
{
    private $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return count($this->getErrors());
    }

    public function addErrors(array $errors)
    {
        $this->errors = array_merge($this->errors, $errors);

        return $this;
    }
}