<?php


namespace App\Exceptions\DomainExceptions\Security;


use App\Exceptions\DomainExceptions\ImproveDomainException;
use Throwable;

class SecurityException extends ImproveDomainException
{
    public function __construct(
        $message = 'Security Exception',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous, $errors, $additionalParams);
    }
}
