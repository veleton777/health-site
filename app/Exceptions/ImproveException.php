<?php


namespace App\Exceptions;


use Exception;
use Throwable;

abstract class ImproveException extends Exception
{
    /* @var array $errors */
    private $errors;
    /**
     * @var array
     */
    private $additionalParams;

    public function __construct(
        $message = 'Exception',
        $code = 0,
        Throwable $previous = null,
        array $errors = [],
        array $additionalParams = []
    )
    {
        parent::__construct($message, $code, $previous);
        $this->additionalParams = $additionalParams;
        $this->errors = $errors;
    }

    /**
     * Key-value array<string, string[]>
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Key-value array<string, mixed>
     *
     * @return array
     */
    public function getAdditionalParams(): array
    {
        return $this->additionalParams;
    }
}
