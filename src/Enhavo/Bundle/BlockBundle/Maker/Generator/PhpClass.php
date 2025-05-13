<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpClass
{
    /** @var array|PhpFunction[] */
    private array $functions;

    public function __construct(
        private string $namespace,
        private string $name,
        private ?string $implements,
        private array $use,
        private array $traits,
        private array $properties,
    ) {
        $this->functions = [];
    }

    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->properties as $key => $config) {
            if (isset($this->properties[$key]['type'])) {
                $properties[] = $this->getProperty($key);
            }
        }

        return $properties;
    }

    public function getProperty($key): PhpClassProperty
    {
        return new PhpClassProperty($key, 'private', $this->properties[$key]);
    }

    /**
     * @return array|PhpFunction[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    public function generateGettersSetters(): void
    {
        foreach ($this->properties as $key => $config) {
            $getter = $this->generateGetter($key);
            if ($getter) {
                $this->functions[] = $getter;
            }
            $setter = $this->generateSetter($key);
            if ($setter) {
                $this->functions[] = $setter;
            }
        }
    }

    public function generateAddersRemovers(): void
    {
        foreach ($this->properties as $key => $config) {
            $classProperty = $this->getProperty($key);
            if ($classProperty->getEntryClass() && $classProperty->getSingular()) {
                $this->functions[] = $this->generateAdder($key);
                $this->functions[] = $this->generateRemover($key);
            }
        }
    }

    public function generateConstructor(): void
    {
        $body = [];
        foreach ($this->properties as $key => $config) {
            $classProperty = $this->getProperty($key);
            if ($classProperty->getConstructor()) {
                $body[] = sprintf('$this->%s = new %s();', $classProperty->getName(), $classProperty->getConstructor());
            }
        }

        if (count($body)) {
            array_unshift($this->functions, new PhpFunction('__construct', 'public', [], $body, null));
        }
    }

    public function generateGetter($key): ?PhpFunction
    {
        $classProperty = $this->getProperty($key);
        if ($classProperty->getAllowGetter()) {
            $nullable = $classProperty->getNullable();
            $name = sprintf('get%s', ucfirst($key));
            $returns = sprintf('%s%s', $nullable, $classProperty->getType());
            $body = [sprintf('return $this->%s;', $key)];

            return new PhpFunction($name, 'public', [], $body, $returns);
        }

        return null;
    }

    public function generateSetter($key): ?PhpFunction
    {
        $classProperty = $this->getProperty($key);
        if ($classProperty->getAllowSetter()) {
            $nullable = $classProperty->getNullable();
            $name = sprintf('set%s', ucfirst($classProperty->getName()));
            $args = [
                sprintf('%s%s', $nullable, $classProperty->getType()) => $classProperty->getName(),
            ];
            $body = [sprintf('$this->%s = $%s;', $classProperty->getName(), $classProperty->getName())];

            if ($classProperty->getTypeOption('setter')) {
                $setterOptions = $classProperty->getTypeOption('setter');
                $calls = $setterOptions['calls'] ?? null;
                if ($calls) {
                    $body[] = sprintf('if ($%s) {', $classProperty->getName());
                    foreach ($calls as $call) {
                        $body[] = sprintf('%s$%s->%s(%s);', str_repeat(' ', 4), $classProperty->getName(), array_shift($call), implode(', ', $call));
                    }
                    $body[] = '}';
                }
            }

            return new PhpFunction($name, 'public', $args, $body, 'void');
        }

        return null;
    }

    public function generateAdder($key): PhpFunction
    {
        $classProperty = $this->getProperty($key);
        $nullable = $classProperty->getNullable();
        $adder = $classProperty->getAdder();
        $parentProperty = $classProperty->getMappedBy();
        $name = sprintf('add%s', ucfirst($classProperty->getSingular()));
        $args = [
            sprintf('%s%s', $nullable, $classProperty->getEntryClass()) => 'item',
        ];

        $body = [sprintf('$this->%s->%s($%s);', $classProperty->getName(), $adder, 'item')];
        if ($parentProperty) {
            $body[] = sprintf('$%s->set%s($this);', 'item', ucfirst($parentProperty));
        }

        return new PhpFunction($name, 'public', $args, $body, null);
    }

    public function generateRemover($key): PhpFunction
    {
        $classProperty = $this->getProperty($key);
        $nullable = $classProperty->getNullable();
        $remover = $classProperty->getRemover();
        $parentProperty = $classProperty->getMappedBy();
        $name = sprintf('remove%s', ucfirst($classProperty->getSingular()));
        $args = [
            sprintf('%s%s', $nullable, $classProperty->getEntryClass()) => 'item',
        ];

        $body = [sprintf('$this->%s->%s($%s);', $classProperty->getName(), $remover, 'item')];
        if ($parentProperty) {
            $body[] = sprintf('$%s->set%s(null);', 'item', ucfirst($parentProperty));
        }

        return new PhpFunction($name, 'public', $args, $body, null);
    }

    public function getUse(): array
    {
        return $this->use;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImplements(): ?string
    {
        return $this->implements;
    }

    public function getTraits(): array
    {
        return $this->traits;
    }
}
