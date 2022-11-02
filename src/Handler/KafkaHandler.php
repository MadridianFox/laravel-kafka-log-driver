<?php

namespace Ensi\KafkaLogDriver\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use RdKafka\Producer;

class KafkaHandler extends AbstractProcessingHandler
{
    private Producer $producer;
    private KafkaHandlerConfig $config;
    private array $topics = [];

    public function __construct(array $config, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->config = new KafkaHandlerConfig($config);
        $this->createKafkaProducer();
    }

    protected function write(array $record): void
    {
        $topic = $this->getTopicForRecord($record);
        /** @noinspection PhpUndefinedConstantInspection */
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $record['formatted']);
    }

    private function createKafkaProducer(): void
    {
        $this->producer = new Producer($this->config->getNativeKafkaConfig());
    }

    public function close(): void
    {
        $this->producer->flush($this->config->flushTimeoutMs());
    }

    private function getTopicForRecord(array $record)
    {
        if (!array_key_exists($record['channel'], $this->topics)) {
            $this->topics[$record['channel']] = $this->producer->newTopic($record['channel']);
        }

        return $this->topics[$record['channel']];
    }
}