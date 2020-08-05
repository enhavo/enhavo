<?php

/**
 * AbstractCollector.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Enhavo\Component\Type\Exception\TypeNotFoundException;
use Enhavo\Component\Type\Exception\TypeNotValidException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Registry implements RegistryInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var RegistryEntry[]
     */
    private $entries = [];

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function register(string $class, string $id)
    {
        $this->checkInterface($class);
        /** @var string|TypeInterface $class */
        $this->checkClassAlreadyExists($class);
        $this->checkNameExists($class::getName());
        $this->checkParentType($class);

        $this->entries[] = new RegistryEntry($id, $class, $class::getName());
    }

    /**
     * @param $class
     * @throws TypeNotValidException
     */
    private function checkInterface($class)
    {
        if (!in_array(TypeInterface::class, class_implements($class))) {
            throw TypeNotValidException::invalidInterface($class);
        }
    }

    /**
     * @param $name
     * @throws TypeNotValidException
     */
    private function checkNameExists($name)
    {
        $entry = $this->getEntry($name);
        if ($name !== null && $entry !== null) {
            throw TypeNotValidException::nameExists($name, $this->namespace, $entry->getClass());
        }
    }

    /**
     * @param string $class
     * @throws TypeNotValidException
     */
    private function checkClassAlreadyExists($class)
    {
        $entry = $this->getEntry($class);
        if ($entry !== null) {
            throw TypeNotValidException::classExists($class, $this->namespace);
        }
    }

    /**
     * @param string|TypeInterface $class
     * @throws TypeNotValidException
     */
    private function checkParentType($class)
    {
        /** @var string|TypeInterface $parentClass */
        $parentClass = $class::getParentType();
        $classes = [$class];
        while ($parentClass !== null) {
            if (in_array($parentClass, $classes)) {
                $classes[] = $parentClass;
                throw TypeNotValidException::circleReferences($classes);
            }

            if ($parentClass !== null && !class_exists($parentClass)) {
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
        foreach($this->entries as $entry) {
            if ($entry->getName() !== null && $entry->getName() === $name) {
                return $entry;
            } elseif($entry->getClass() === $name)  {
                return $entry;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @throws TypeNotFoundException
     * @return TypeInterface
     */
    public function getType(string $name): TypeInterface
    {
        $entry = $this->getEntry($name);

        if ($entry === null) {
            throw TypeNotFoundException::notFound($name, $this->namespace, $this->getPossibleNames());
        }

        if ($entry->getService() === null) {
            $entry->setService($this->createService($entry));
        }

        return $entry->getService();
    }

    private function createService(RegistryEntry $entry): TypeInterface
    {
        /** @var TypeInterface $service */
        $service = $this->container->get($entry->getId());

        $parent = $service::getParentType();
        if($parent !== null) {
            $service->setParent($this->getType($parent));
        }

        return $service;
    }

    private function getPossibleNames()
    {
        $names = [];
        foreach($this->entries as $entry) {
            if($entry->getName() !== null) {
                $names[] = $entry->getName();
            }
            $names[] = $entry->getClass();
        }
        return $names;
    }
}
