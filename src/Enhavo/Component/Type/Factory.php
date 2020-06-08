<?php
/**
 * TypeFactory.php
 *
 * @since 12/02/18
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Enhavo\Component\Type\Exception\TypeCreateException;

class Factory implements FactoryInterface
{
    /** @var RegistryInterface */
    private $registry;

    /** @var string */
    private $class;

    public function __construct($class, RegistryInterface $registry)
    {
        $this->class = $class;
        $this->registry = $registry;
    }

    /**
     * @param array $options
     * @throws TypeCreateException
     * @return object
     */
    public function create(array $options)
    {
        if(!isset($options['type'])) {
            throw TypeCreateException::missionOption($this->class, $options);
        }

        $type = $this->registry->getType($options['type']);
        $parents = $this->getParents($type);
        unset($options['type']);
        $class = new $this->class($type, $parents, $options);
        return $class;
    }

    private function getParents(TypeInterface $type)
    {
        $parents = [];

        $parent = $type::getParentType();
        while ($parent !== null) {
            $parentType = $this->registry->getType($parent);
            $parents[] = $parentType;
            $parent = $parentType::getParentType($parentType);
        }

        return array_reverse($parents);
    }
}
