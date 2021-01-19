<?php


namespace App\Exceptions\DomainExceptions\Security;


use Throwable;

class AccessDeniedException extends SecurityException
{
    public function __construct(
        $message = 'Access denied',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous, $errors, $additionalParams);
    }
}
