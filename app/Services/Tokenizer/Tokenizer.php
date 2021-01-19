<?php


namespace App\Services\Tokenizer;


use Exception;

class Tokenizer
{
    /**
     * @return string
     * @throws Exception
     */
    public static function getRandomCode(): string
    {
        // Todo edit random_int(1111, 9999)
        return (string)random_int(1111, 1111);
    }
}
