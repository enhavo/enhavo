<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Maker\Analyze;

class ConstructArgument
{
    public function __construct(
        private string $name,
        private ?string $type = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortName(): ?string
    {
        if ($this->type) {
            $parts = explode('\\', $this->type);

            return array_pop($parts);
        }

        return null;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isInt(): bool
    {
        return 'int' == $this->type || 'integer' == $this->type;
    }

    public function isBool(): bool
    {
        return 'bool' == $this->type || 'boolean' == $this->type;
    }

    public function isFloat(): bool
    {
        return 'float' == $this->type;
    }

    public function isArray(): bool
    {
        return 'array' == $this->type;
    }

    public function isString(): bool
    {
        return 'string' == $this->type;
    }

    public function isObject(): bool
    {
        return 'object' == $this->type;
    }

    public function isInterface(): bool
    {
        if ($this->getType() && !$this->isPrimitive()) {
            return false;
            // $reflection = new \ReflectionClass($this->getFullType());
            // return $reflection->isInterface();
        }

        return false;
    }

    public function isPrimitive()
    {
        return $this->isInt() || $this->isFloat() || $this->isArray() || $this->isString() || $this->isBool() || $this->isObject();
    }

    public function getFullType()
    {
    }
}
