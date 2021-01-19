<?php


namespace App\Exceptions\DomainExceptions\Security;


use Throwable;

class UnauthorizedException extends SecurityException
{
    public function __construct(
        $message = 'Unauthorized',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous, $errors, $additionalParams);
    }

    public static function invalidCredentials(string $message = 'Неверный логин и/или пароль'): self
    {
        return new self($message);
    }

    public static function fromThrowable(
        Throwable $throwable
    ): self
    {
        return new self('Ошибка аутентификации', 0, $throwable);
    }
}
