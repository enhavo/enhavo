<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Component\Metadata;

use Doctrine\Persistence\Proxy;
use Enhavo\Component\Metadata\Exception\InvalidMetadataException;

class MetadataRepository
{
    private array $metadata = [];

    public function __construct(
        private readonly MetadataFactory $factory,
        private readonly bool $includeExtend = true,
        private readonly bool $onlyExists = true,
    )
    {
    }

    public function getAllMetadata(): array
    {
        $classes = $this->factory->getAllClasses();
        foreach ($classes as $class) {
            $this->getMetadata($class);
        }
        return $this->metadata;
    }

    public function getMetadata(string|object $class): ?Metadata
    {
        $className = $this->getClassName($class);

        if (array_key_exists($className, $this->metadata)) {
            return $this->metadata[$className];
        }

        $loaded = [];
        $metadata = $this->factory->createMetadata($className, $this->includeExtend || !$this->onlyExists);

        if ($this->includeExtend) {
            $parents = [];
            $this->getParents($className, $parents);
            $parents = array_reverse($parents);

            foreach ($parents as $parent) {
                $loaded[] = $this->factory->loadMetadata($parent, $metadata);
            }
        } elseif ($metadata === null) {
            return null;
        }

        $loaded[] = $this->factory->loadMetadata($className, $metadata);

        if ($this->onlyExists && $this->includeExtend && !in_array(true, $loaded)) {
            return null;
        }

        $this->metadata[$className] = $metadata;
        return $metadata;
    }

    private function getParents($className, array &$parents): void
    {
        if (!class_exists($className)) {
            throw InvalidMetadataException::classNotExists($className);
        }

        $parentClass = get_parent_class($className);
        if ($parentClass !== false) {
            $parents[] = $parentClass;
            $this->getParents($parentClass, $parents);
        }
    }

    public function hasMetadata($class): bool
    {
        $metadata = $this->getMetadata($class);
        return $metadata !== null;
    }

    private function getClassName($class): false|string
    {
        if (is_string($class)) {
            $className = $class;
        } else if (is_object($class)) {
            if ($class instanceof Proxy) {
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
