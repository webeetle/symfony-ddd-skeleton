<?php

namespace App\Application\Exception;

class UnexpectedTypeException extends \RuntimeException
{
    private const CODE = 500;

    public function __construct(string $expectedType, $actualType)
    {
        $actualType = is_object($actualType) ? get_class($actualType) : gettype($actualType);
        $message = sprintf('Expected argument of type "%s", "%s" given', $expectedType, $actualType);
        parent::__construct($message, self::CODE);
    }

}
