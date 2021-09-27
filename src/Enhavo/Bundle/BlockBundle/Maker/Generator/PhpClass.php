<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpClass
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    /** @var string */
    private $extends;

    /** @var array */
    private $use;

    /** @var array */
    private $properties;

    /** @var array|PhpFunction[] */
    private $functions;

    /**
     * @param string $namespace
     * @param string $name
     * @param string $extends
     * @param array $use
     * @param array $properties
     */
    public function __construct(string $namespace, string $name, string $extends, array $use, array $properties)
    {
        $this->namespace = $namespace;
        $this->name = $name;
        $this->extends = $extends;
        $this->use = $use;
        $this->properties = $properties;
        $this->functions = [];
    }

    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->properties as $key => $config) {
            $properties[] = $this->getProperty($key);
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

    public function generateGettersSetters()
    {
        foreach ($this->properties as $key => $config) {
            $this->functions[] = $this->generateGetter($key);
            $this->functions[] = $this->generateSetter($key);
        }
    }

    public function generateAddersRemovers()
    {
        foreach ($this->properties as $key => $config) {
            $classProperty = $this->getProperty($key);
            if ($classProperty->getEntryClass()) {
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
            array_unshift($this->functions, new PhpFunction('__constructor', 'public', [], $body, null));
        }

    }

    public function generateGetter($key): PhpFunction
    {
        $classProperty = $this->getProperty($key);
        $nullable = $classProperty->getNullable();
        $name = sprintf('get%s', ucfirst($key));
        $returns = sprintf('%s%s', $nullable, $classProperty->getType());
        $body = [sprintf('return $this->%s;', $key)];
        return new PhpFunction($name, 'public', [], $body, $returns);
    }

    public function generateSetter($key): PhpFunction
    {
        $classProperty = $this->getProperty($key);
        $nullable = $classProperty->getNullable();
        $name = sprintf('set%s', ucfirst($classProperty->getName()));
        $args = [
            sprintf('%s%s', $nullable, $classProperty->getType()) => $classProperty->getName(),
        ];
        $body = [sprintf('$this->%s = $%s;', $classProperty->getName(), $classProperty->getName())];
        return new PhpFunction($name, 'public', $args, $body, 'void');
    }


    public function generateAdder($key): PhpFunction
    {
        $classProperty = $this->getProperty($key);
        $nullable = $classProperty->getNullable();
        $adder = $classProperty->getAdder();
        $parentProperty = $classProperty->getMappedBy();
        $name = sprintf('add%s', ucfirst($key));
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
        $name = sprintf('remove%s', ucfirst($key));
        $args = [
            sprintf('%s%s', $nullable, $classProperty->getEntryClass()) => 'item',
        ];

        $body = [sprintf('$this->%s->%s($%s);', $classProperty->getName(), $remover, 'item')];
        if ($parentProperty) {
            $body[] = sprintf('$%s->set%s(null);', 'item', ucfirst($parentProperty));
        }

        return new PhpFunction($name, 'public', $args, $body, null);
    }

    /**
     * @return array
     */
    public function getUse(): array
    {
        return $this->use;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExtends(): string
    {
        return $this->extends;
    }
}
