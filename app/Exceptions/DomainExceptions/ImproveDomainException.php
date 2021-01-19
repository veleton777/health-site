<?php


namespace App\Exceptions\DomainExceptions;


use App\Exceptions\ImproveException;
use Throwable;

class ImproveDomainException extends ImproveException
{
    public function __construct(
        $message = 'Domain Exception',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous, $errors, $additionalParams);
    }
}
