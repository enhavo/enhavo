<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 11:21
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
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    public function getExtendedTypes(): array
    {
        return $this->extendedTypes;
    }

    /**
     * @return TypeExtensionInterface|null
     */
    public function getService(): ?TypeExtensionInterface
    {
        return $this->service;
    }

    /**
     * @param TypeExtensionInterface|null $service
     */
    public function setService(?TypeExtensionInterface $service): void
    {
        $this->service = $service;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
