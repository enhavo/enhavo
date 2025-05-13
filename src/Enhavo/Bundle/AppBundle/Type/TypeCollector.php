<?php

namespace Enhavo\Bundle\AppBundle\Type;

use Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException;
use Enhavo\Bundle\AppBundle\Exception\TypeNotValidException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author gseidel
 */
class TypeCollector implements CollectorInterface
{
    /**
     * @var TypeInterface[]
     */
    private $collection;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $typeName;

    /**
     * @var TypeInterface[]
     */
    private $types;

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
            $type = $this->container->get($serviceId);
            if($this->isTypeValid($type, $alias)){
                return $type;
            }
        }

        throw new TypeNotFoundException(sprintf(
            '%s type "%s" not found. Did you mean one of them "%s".',
            $this->typeName,
            $alias,
            implode(', ', $this->getNames())
        ));
    }

    public function getTypes()
    {
        if($this->types === null) {
            $this->types = [];
            $names = $this->getNames();
            foreach($names as $name) {
                $this->types[] = $this->getType($name);
            }
        }
        return $this->types;
    }

    protected function isTypeValid($type, $alias)
    {
        if($type instanceof TypeInterface) {
            if($type->getType() == $alias) {
                return true;
            } else {
                throw new TypeNotValidException(sprintf('%s does not match alias %s', $type->getType(), $alias));
            }
        } else {
            throw new TypeNotValidException(sprintf('%s does not implement TypeInterface', get_class($type)));
        }
    }

    protected function getNames()
    {
        return array_keys($this->collection);
    }
}
