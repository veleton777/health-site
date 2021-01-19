<?php


namespace App\Services\Sms;


class TestSender implements SmsSenderInterface
{
    public function send(string $phone, string $text): void
    {

    }
}
