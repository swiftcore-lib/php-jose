<?php
namespace Swiftcore\Utility;

interface ErrorBag
{
    public function getErrors();

    public function hasErrors();

    public function addErrors(array $errors);
}