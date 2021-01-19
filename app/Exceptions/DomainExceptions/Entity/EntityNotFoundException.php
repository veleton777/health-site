<?php


namespace App\Exceptions\DomainExceptions\Entity;


use App\Exceptions\DomainExceptions\ImproveDomainException;
use Throwable;

class EntityNotFoundException extends ImproveDomainException
{
    public function __construct(
        $message = 'Entity not found',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous, $errors, $additionalParams);
    }
}
