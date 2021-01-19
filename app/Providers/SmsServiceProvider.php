<?php

namespace App\Providers;

use App\Services\Sms\SmsSenderInterface;
use App\Services\Sms\TestSender;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class SmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SmsSenderInterface::class, function (Application $app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'test':
                    return new TestSender();
                default:
                    throw new InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }
        });
    }
}
