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
     * @param string|null $key
     * @throws TypeCreateException
     * @return object
     */
    public function create(array $options, $key = null)
    {
        if(!isset($options['type'])) {
            throw TypeCreateException::missionOption($this->class, $options);
        }

        $type = $this->registry->getType($options['type']);
        $parents = $this->getParents($type);
        unset($options['type']);
        $class = new $this->class($type, $parents, $options, $key, $this->getExtensions($type, $parents));
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

    private function getExtensions(TypeInterface $type, array $parents): array
    {
        $extensions = [];

        $checkTypes = [$type];
        foreach ($parents as $parent) {
            $checkTypes[] = $parent;
        }

        foreach ($checkTypes as $checkType) {
            foreach ($this->registry->getExtensions($checkType) as $foundExtension) {
                $exists = false;
                foreach ($extensions as $extension) {
                    if ($extension === $foundExtension) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $extensions[] = $foundExtension;
                }
            }
        }

        return $extensions;
    }
}
