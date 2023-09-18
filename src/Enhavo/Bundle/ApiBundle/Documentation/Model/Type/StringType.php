<?php

namespace Enhavo\Bundle\ApiBundle\Documentation\Model\Type;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;

class StringType
{
    public function __construct(
        private array &$data,
        private $parent,
    )
    {
        $this->data['type'] = 'string';
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
