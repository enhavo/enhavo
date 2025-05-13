<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

class RegistryExtension
{
    private ?TypeExtensionInterface $service = null;

    public function __construct(
        private string $id,
        private string $class,
        private array $extendedTypes,
        private int $priority,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getExtendedTypes(): array
    {
        return $this->extendedTypes;
    }

    public function getService(): ?TypeExtensionInterface
    {
        return $this->service;
    }

    public function setService(?TypeExtensionInterface $service): void
    {
        $this->service = $service;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
