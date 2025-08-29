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
    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_PROPERTY = 'property';
    const ATTRIBUTE_METHOD = 'method';

    public function __construct(
        private string $attributeType,
        private ?string $typeName,
        private ?string $propertyName = null,
        private mixed $propertyValue = null,
        private array|string|null $groups = null,
    )
    {
    }

    public function isClassAttribute(): bool
    {
        return $this->attributeType === self::ATTRIBUTE_CLASS;
    }

    public function isPropertyAttribute(): bool
    {
        return $this->attributeType === self::ATTRIBUTE_PROPERTY;
    }

    public function isMethodAttribute(): bool
    {
        return $this->attributeType === self::ATTRIBUTE_METHOD;
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

    public function getGroups(): array|string|null
    {
        return $this->groups;
    }

    public function setGroups(array|string|null $groups): void
    {
        $this->groups = $groups;
    }
}
