<?php

/**
 * AbstractCollector.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypeCollector implements CollectorInterface
{
    /**
     * @var TypeInterface[]
     */
    private $collection;

    private $container;

    /**
     * @var string
     */
    private $typeName;

    public function __construct(ContainerInterface $container, $typeName = 'Type')
    {
        $this->collection = array();
        $this->container = $container;
        $this->typeName = $typeName;
    }

    public function add($alias, $id)
    {
        $this->collection[$alias] = $id;
    }

    public function getType($alias)
    {
        if(isset($this->collection[$alias])) {
            $serviceId = $this->collection[$alias];
            return $this->container->get($serviceId);
        }

        throw new TypeNotFoundException(sprintf(
            '%s type "%s" not found. Did you mean one of them "%s".',
            $this->typeName,
            $alias,
            implode(', ', $this->getNames())
        ));
    }

    protected function getNames()
    {
        return array_keys($this->collection);
    }
}