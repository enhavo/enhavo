<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;

class IntegerType
{
    public function __construct(
        private array &$data,
        private $parent,
    )
    {
        $this->data['type'] = 'integer';
    }

    public function format(string $value): self
    {
        $this->data['format'] = $value;
        return $this;
    }

    public function minimum(int $value): self
    {
        $this->data['minimum'] = $value;
        return $this;
    }

    public function maximum(int $value): self
    {
        $this->data['maximum'] = $value;
        return $this;
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
