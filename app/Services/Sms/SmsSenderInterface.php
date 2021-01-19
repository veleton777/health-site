<?php


namespace App\Services\Sms;


interface SmsSenderInterface
{
    public function send(string $phone, string $text): void;
}
