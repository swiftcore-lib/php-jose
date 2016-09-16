<?php
namespace Swiftcore\Jose\Exception;

use Swiftcore\Utility\ErrorBag;

class InvalidECKeyArgumentException extends \InvalidArgumentException implements SwiftcoreJoseException, ErrorBag
{
    use ExceptionErrorsTrait;

    public function __construct($errors = [], $message = '', $code = 0, \Exception $previous = null)
    {
        if ($errors) {
            $this->addErrors($errors);
        }
        parent::__construct($message ? $message : 'Invalid EC Key Argument', $code, $previous);
    }
}
