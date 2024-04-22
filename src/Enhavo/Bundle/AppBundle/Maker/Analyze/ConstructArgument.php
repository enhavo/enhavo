<?php

namespace Enhavo\Bundle\AppBundle\Maker\Analyze;

class ConstructArgument
{
    public function __construct(
        private string $name,
        private ?string $type = null
    )
    {
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
        return $this->type == 'int' || $this->type == 'integer';
    }

    public function isBool(): bool
    {
        return $this->type == 'bool' || $this->type == 'boolean';
    }

    public function isFloat(): bool
    {
        return $this->type == 'float';
    }

    public function isArray(): bool
    {
        return $this->type == 'array';
    }

    public function isString(): bool
    {
        return $this->type == 'string';
    }

    public function isObject(): bool
    {
        return $this->type == 'object';
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
