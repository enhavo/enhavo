<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

class SourceValue
{
    public function __construct(
        private mixed $value = null,
        private ?object $parent = null,
        private ?string $propertyName = null,
    )
    {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getParent(): ?object
    {
        return $this->parent;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }
}
