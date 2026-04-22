<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Documentation\Model\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Node;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;

/**
 * @method ObjectType|Schema end()
 */
class ObjectType extends Node
{
    public function __construct(
        array &$data,
        $parent,
    ) {
        parent::__construct($data, $parent);
        $this->data['type'] = 'object';
    }

    public function ref($ref): self
    {
        $this->reset();
        $this->data['$ref'] = $ref;

        return $this;
    }

    /** @return ObjectType|IntegerType|StringType|NumberType|BooleanType|ArrayType */
    public function property(string $name, $type)
    {
        if (!array_key_exists('properties', $this->data)) {
            $this->data['properties'] = [];
        }

        if (!array_key_exists($name, $this->data['properties'])) {
            $this->data['properties'][$name] = [];
        }

        return match ($type) {
            'object' => new ObjectType($this->data['properties'][$name], $this),
            'string' => new StringType($this->data['properties'][$name], $this),
            'integer' => new IntegerType($this->data['properties'][$name], $this),
            'number' => new NumberType($this->data['properties'][$name], $this),
            'boolean' => new BooleanType($this->data['properties'][$name], $this),
            'array' => new ArrayType($this->data['properties'][$name], $this),
            'schema' => new Schema($this->data['properties'][$name], $this),
        };
    }

    public function oneOf(): Schema
    {
        if (!array_key_exists('oneOf', $this->data)) {
            $this->data['oneOf'] = [];
        }

        $index = count($this->data['oneOf']);
        $this->data['oneOf'][$index] = [];

        return new Schema($this->data['oneOf'][$index], $this);
    }

    public function discriminator(string $propertyName, array $mapping = []): self
    {
        $this->data['discriminator'] = ['propertyName' => $propertyName];

        if (!empty($mapping)) {
            $this->data['discriminator']['mapping'] = $mapping;
        }

        return $this;
    }

    public function required(array $properties): self
    {
        $this->data['required'] = $properties;

        return $this;
    }

    public function additionalProperties(bool $value): self
    {
        $this->data['additionalProperties'] = $value;

        return $this;
    }

    public function description(string $value): self
    {
        $this->data['description'] = $value;

        return $this;
    }

    public function nullable(bool $value = true): self
    {
        $this->data['nullable'] = $value;

        return $this;
    }

    public function example($value): self
    {
        $this->data['example'] = $value;

        return $this;
    }

    private function reset()
    {
        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
        }
    }
}
