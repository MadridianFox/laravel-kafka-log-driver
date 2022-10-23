<?php

namespace Ensi\KafkaLogDriver;

use Ensi\KafkaLogDriver\Handler\KafkaHandler;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger as Monolog;

class KafkaLogDriverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /** @var LogManager $logManager */
        $logManager = $this->app['log'];
        $logManager->extend('kafka', function ($app, array $config) {
            /** @var LogManager $this */
            return new Monolog($this->parseChannel($config), [
                $this->prepareHandler(
                    new KafkaHandler(
                        $config['kafka'],
                        $this->level($config),
                        $config['bubble'] ?? true,
                    ),
                    $config
                ),
            ]);
        });
    }
}