<?php

namespace Ensi\KafkaLogDriver\Handler;

use RdKafka\Conf;

class KafkaHandlerConfig
{
    /**
     * @see https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md
     * @param array $rawConfig
     */
    public function __construct(private array $rawConfig)
    {
    }

    public function getNativeKafkaConfig(): Conf
    {
        $config = new Conf();
        $cleanKafkaOptions = $this->cleanupConfigValues($this->rawConfig['options'] ?? []);
        foreach ($cleanKafkaOptions as $key => $value) {
            $config->set($key, $value);
        }

        return $config;
    }

    public function flushTimeoutMs(): int
    {
        return $this->rawConfig['flush_timeout_ms'] ?? 500;
    }

    private function cleanupConfigValues(array $rawOptions): array
    {
        foreach ($rawOptions as $key => $value) {
            if ($value === null) {
                unset($rawOptions[$key]);
            }
        }

        $booleanToStrings = [
            'enable.auto.commit',
        ];
        foreach ($booleanToStrings as $key) {
            if (isset($rawOptions[$key])) {
                $rawOptions[$key] = $this->stringifyBoolean($rawOptions[$key]);
            }
        }

        return $rawOptions;
    }

    private function stringifyBoolean(mixed $value): mixed
    {
        if ($value === true) {
            return "true";
        }

        if ($value === false) {
            return "false";
        }

        return $value;
    }
}