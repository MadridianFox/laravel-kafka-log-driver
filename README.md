# Kafka log driver for laravel

Kafka Monolog handler and service provider for register log driver in laravel application.

## Installation

```
composer require ensi/kafka-log-driver
```

## Usage

Configure your channels with new log driver

```
# config/logging.php
'channels' => [
    'default' => [
        'name' => 'default',
        'driver' => 'kafka',
        'formatter' => \Monolog\Formatter\JsonFormatter::class,
        'kafka' => [
            'flush_timeout_ms' => 5,
            'topic' => null, // by default topic name is channel name
            'options' => [
                'metadata.broker.list' => env('LOG_KAFKA_BROKER_LIST'),
                'sasl.mechanisms' => env('LOG_KAFKA_SASL_MECHANISM', 'PLAIN'),
                'security.protocol' => env('LOG_KAFKA_SECURITY_PROTOCOL', 'sasl_plaintext'),
                'sasl.username' => env('LOG_KAFKA_USERNAME'),
                'sasl.password' => env('LOG_KAFKA_PASSWORD'),
                // any other rdkafka options
            ],
        ],
    ],
]
```