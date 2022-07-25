<?php

namespace Enhavo\Bundle\ShopBundle\Controller;

class DocumentConfiguration
{
    public function __construct(
        private string $name,
        private array $options = [],
        private bool $mutable = false,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function isMutable(): bool
    {
        return $this->mutable;
    }
}
