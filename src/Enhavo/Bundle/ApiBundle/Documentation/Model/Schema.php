<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\IntegerType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\ObjectType;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Type\StringType;

class Schema
{
    public function __construct(
        private array &$schema,
        private $parent,
    )
    {
    }

    public function ref($ref): self
    {
        $this->reset();
        $this->schema['$ref'] = $ref;
        return $this;
    }

    public function object(): ObjectType
    {
        if (isset($this->schema['type']) && $this->schema['type'] === 'object') {
            return new ObjectType($this->schema, $this);
        }

        $this->reset();
        return new ObjectType($this->schema, $this);
    }

    public function string(): StringType
    {
        $this->reset();
        return new StringType($this->schema, $this);
    }

    public function integer(): IntegerType
    {
        $this->reset();
        return new IntegerType($this->schema, $this);
    }

    private function reset()
    {
        foreach ($this->schema as $key => $value) {
            unset($this->schema[$key]);
        }
    }

    /** @return Content */
    public function end(): mixed
    {
        return $this->parent;
    }

    public function getDocumentation(): Documentation
    {
        return $this->parent->getDocumentation();
    }
}
