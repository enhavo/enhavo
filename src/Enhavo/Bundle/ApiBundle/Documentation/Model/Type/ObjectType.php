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

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;

class ObjectType
{
    public function __construct(
        private array &$data,
        private $parent,
    ) {
        $this->data['type'] = 'object';
    }

    public function ref($ref): self
    {
        $this->reset();
        $this->data['$ref'] = $ref;

        return $this;
    }

    /** @return ObjectType|IntegerType|StringType */
    public function property($name, $type)
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
        };
    }

    private function reset()
    {
        foreach ($this->data as $key => $value) {
            unset($this->data[$key]);
        }
    }

    /** @return ObjectType|Schema */
    public function end()
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}
