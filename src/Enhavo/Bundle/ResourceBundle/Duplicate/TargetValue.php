<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

class TargetValue
{
    private mixed $value = null;

    public function __construct(
        private mixed $originalValue = null,
        private ?object $parent = null,
        private ?string $propertyName = null,
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getOriginalValue(): mixed
    {
        return $this->originalValue;
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
