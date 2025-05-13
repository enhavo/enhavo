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

class RegistryEntry
{
    /** @var string */
    private $id;

    /** @var string */
    private $class;

    /** @var string|null */
    private $name;

    /** @var TypeInterface|null */
    private $service;

    /**
     * Entry constructor.
     */
    public function __construct(string $id, string $class, ?string $name = null)
    {
        $this->id = $id;
        $this->class = $class;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getService(): ?TypeInterface
    {
        return $this->service;
    }

    public function setService(?TypeInterface $service): void
    {
        $this->service = $service;
    }
}
