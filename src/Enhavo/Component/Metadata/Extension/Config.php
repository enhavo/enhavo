<?php

namespace Enhavo\Component\Metadata\Extension;

class Config
{
    public function __construct(
        private string $key,
        private array $config,
    )
    {
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
