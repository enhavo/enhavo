<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

class Context
{
    public function __construct(
        private bool $classAttribute,
        private ?string $typeName,
        private ?string $propertyName = null,
        private mixed $propertyValue = null,
    )
    {
    }

    public function isClassAttribute(): bool
    {
        return $this->classAttribute;
    }

    public function isPropertyAttribute(): bool
    {
        return !$this->classAttribute;
    }

    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }

    public function getPropertyValue(): mixed
    {
        return $this->propertyValue;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
}
