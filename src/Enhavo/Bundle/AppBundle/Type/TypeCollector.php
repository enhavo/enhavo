<?php

/**
 * AbstractCollector.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException;

class TypeCollector implements CollectorInterface
{
    /**
     * @var TypeInterface[]
     */
    private $collection;

    /**
     * @var string
     */
    private $typeName;

    public function __construct($typeName)
    {
        $this->collection = array();
        $this->typeName = $typeName;
    }

    public function add(TypeInterface $type)
    {
        $this->collection[$type->getType()] = $type;
    }

    /**
     * @return TypeInterface[]
     */
    public function getCollection()
    {
        $collection = [];
        foreach($this->collection as $type) {
            $collection[] = $type;
        }
        return $collection;
    }

    public function getType($name)
    {
        if(isset($this->collection[$name])) {
            return $this->collection[$name];
        }

        throw new TypeNotFoundException(sprintf(
            '%s type "%s" not found. Did you mean one of them "%s".',
            $this->typeName,
            $name,
            implode(', ', $this->getNames())
        ));
    }

    protected function getNames()
    {
        return array_keys($this->collection);
    }
}