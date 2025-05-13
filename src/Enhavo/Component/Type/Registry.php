<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

use Enhavo\Component\Type\Exception\TypeNotFoundException;
use Enhavo\Component\Type\Exception\TypeNotValidException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Registry implements RegistryInterface
{
    use ContainerAwareTrait;

    private string $namespace;

    /** @var RegistryEntry[] */
    private array $entries = [];

    /** @var RegistryExtension[] */
    private array $extensions = [];

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function register(string $class, string $id)
    {
        $this->checkInterface($class);
        /* @var string|TypeInterface $class */
        $this->checkClassAlreadyExists($class);
        $this->checkNameExists($class::getName());
        $this->checkParentType($class);

        $this->entries[] = new RegistryEntry($id, $class, $class::getName());
    }

    /**
     * @throws TypeNotValidException
     */
    private function checkInterface($class): void
    {
        if (!in_array(TypeInterface::class, class_implements($class))) {
            throw TypeNotValidException::invalidInterface($class);
        }
    }

    /**
     * @throws TypeNotValidException
     */
    private function checkNameExists($name)
    {
        $entry = $this->getEntry($name);
        if (null !== $name && null !== $entry) {
            throw TypeNotValidException::nameExists($name, $this->namespace, $entry->getClass());
        }
    }

    /**
     * @param string $class
     *
     * @throws TypeNotValidException
     */
    private function checkClassAlreadyExists($class)
    {
        $entry = $this->getEntry($class);
        if (null !== $entry) {
            throw TypeNotValidException::classExists($class, $this->namespace);
        }
    }

    /**
     * @param string|TypeInterface $class
     *
     * @throws TypeNotValidException
     */
    private function checkParentType($class)
    {
        /** @var string|TypeInterface $parentClass */
        $parentClass = $class::getParentType();
        $classes = [$class];
        while (null !== $parentClass) {
            if (in_array($parentClass, $classes)) {
                $classes[] = $parentClass;
                throw TypeNotValidException::circleReferences($classes);
            }

            if (null !== $parentClass && !class_exists($parentClass)) {
                throw TypeNotValidException::parentNotFound($class, $parentClass);
            }

            $reflection = new \ReflectionClass($parentClass);
            if (!$reflection->implementsInterface(TypeInterface::class)) {
                throw TypeNotValidException::parentInvalidInterface($class, $parentClass);
            }

            $classes[] = $parentClass;

            $class = $parentClass;
            $parentClass = $parentClass::getParentType();
        }
    }

    private function getEntry($name): ?RegistryEntry
    {
        foreach ($this->entries as $entry) {
            if (null !== $entry->getName() && $entry->getName() === $name) {
                return $entry;
            } elseif ($entry->getClass() === $name) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * @throws TypeNotFoundException
     */
    public function getType(string $name): TypeInterface
    {
        $entry = $this->getEntry($name);

        if (null === $entry) {
            throw TypeNotFoundException::notFound($name, $this->namespace, $this->getPossibleNames());
        }

        if (null === $entry->getService()) {
            $entry->setService($this->createService($entry));
        }

        return clone $entry->getService();
    }

    private function createService(RegistryEntry $entry): TypeInterface
    {
        /** @var TypeInterface $service */
        $service = $this->container->get($entry->getId());

        $parent = $service::getParentType();
        if (null !== $parent) {
            $service->setParent($this->getType($parent));
        }

        return $service;
    }

    private function getPossibleNames()
    {
        $names = [];
        foreach ($this->entries as $entry) {
            if (null !== $entry->getName()) {
                $names[] = $entry->getName();
            }
            $names[] = $entry->getClass();
        }

        return $names;
    }

    public function registerExtension(string $class, string $id, int $priority = 10)
    {
        $this->checkExtensionInterface($class);
        /* @var string|TypeExtensionInterface $class */
        $this->checkExtendedTypeAvailable($class);

        $this->extensions[] = new RegistryExtension($id, $class, $class::getExtendedTypes(), $priority);

        usort($this->extensions, function (RegistryExtension $a, RegistryExtension $b) {
            return $b->getPriority() - $a->getPriority();
        });
    }

    private function checkExtensionInterface($class): void
    {
        if (!in_array(TypeExtensionInterface::class, class_implements($class))) {
            throw TypeNotValidException::invalidExtensionInterface($class);
        }
    }

    private function checkExtendedTypeAvailable($extensionClass)
    {
        $types = $extensionClass::getExtendedTypes();
        foreach ($this->entries as $entry) {
            foreach ($types as $type) {
                if ($entry->getClass() === $type || ($entry->getName() && $entry->getName() === $type)) {
                    return;
                }
            }
        }

        throw TypeNotValidException::extendedTypeNotExists($extensionClass, $types);
    }

    public function getExtensions(TypeInterface $type): array
    {
        $extensions = [];
        foreach ($this->extensions as $extension) {
            foreach ($extension->getExtendedTypes() as $extendedType) {
                if ($this->checkExtends($type, $extendedType)) {
                    if (null === $extension->getService()) {
                        $service = $this->container->get($extension->getId());
                        $extension->setService($service);
                    }
                    $extensionService = clone $extension->getService();
                    $extensions[] = $extensionService;
                }
            }
        }

        return $extensions;
    }

    private function checkExtends($type, $extendedType)
    {
        if (interface_exists($extendedType) && is_subclass_of($type::class, $extendedType)) {
            return true;
        } elseif ($type::class === $extendedType) {
            return true;
        } elseif ($type::getName() && $type::getName() === $extendedType) {
            return true;
        }

        return false;
    }
}
