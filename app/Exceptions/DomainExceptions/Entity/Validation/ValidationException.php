<?php


namespace App\Exceptions\DomainExceptions\Entity\Validation;


use App\Exceptions\DomainExceptions\ImproveDomainException;
use Throwable;

class ValidationException extends ImproveDomainException
{
    public function __construct(
        $message = 'Entity validation exception',
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, 0, $previous, $errors, $additionalParams);
    }

    public static function fromErrors(array $errors = []): self
    {
        return new self('Entity validation exception', null, $errors);
    }
}
