<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata;

use Doctrine\ORM\Proxy\Proxy;
use Enhavo\Component\Metadata\Exception\InvalidMetadataException;

class MetadataRepository
{
    /** @var MetadataFactory */
    private $factory;

    /** @var array */
    private $metadata = [];

    /** @var boolean */
    private $allowExtend;

    /**
     * MetadataRepository constructor.
     *
     * @param MetadataFactory $factory
     * @param boolean $allowExtend
     */
    public function __construct(MetadataFactory $factory, bool $allowExtend = true)
    {
        $this->factory = $factory;
        $this->allowExtend = $allowExtend;
    }

    /**
     * @return Metadata[]
     */
    public function getAllMetadata()
    {
        $classes = $this->factory->getAllClasses();
        foreach($classes as $class) {
            $this->getMetadata($class);
        }
        return $this->metadata;
    }

    /**
     * @param string|object $class
     * @return Metadata
     */
    public function getMetadata($class)
    {
        $className = $this->getClassName($class);

        if(array_key_exists($className, $this->metadata)) {
            return $this->metadata[$className];
        }

        $loaded = [];
        $metadata = $this->factory->createMetadata($className, $this->allowExtend);

        if ($this->allowExtend) {
            $parents = [];
            $this->getParents($className, $parents);
            $parents = array_reverse($parents);

            foreach ($parents as $parent) {
                $loaded[] = $this->factory->loadMetadata($parent, $metadata);
            }
        } elseif($metadata === null) {
            return null;
        }

        $loaded[] = $this->factory->loadMetadata($className, $metadata);

        if ($this->allowExtend && !in_array(true, $loaded)) {
            return null;
        }

        $this->metadata[$className] = $metadata;
        return $metadata;
    }

    private function getParents($className, array &$parents)
    {
        $parentClass = get_parent_class($className);
        if($parentClass !== false) {
            $parents[] = $parentClass;
            $this->getParents($parentClass, $parents);
        }
    }

    /**
     * @param $class
     * @return bool
     */
    public function hasMetadata($class): bool
    {
        $metadata = $this->getMetadata($class);
        return $metadata !== null;
    }

    private function getClassName($class)
    {
        if(is_string($class)) {
            $className = $class;
        } else if(is_object($class)) {
            if($class instanceof Proxy) {
                $className = get_parent_class($class);
            } else {
                $className = get_class($class);
            }
        } else {
            throw InvalidMetadataException::invalidType($class);
        }

        return $className;
    }
}
