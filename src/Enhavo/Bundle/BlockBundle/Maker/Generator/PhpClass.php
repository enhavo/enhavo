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
        return new PhpClassProperty($key, $this->properties[$key]);
    }

    /**
     * @return array|PhpFunction[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    public function generateGetterSetters()
    {
        foreach ($this->properties as $key => $config) {

            $this->functions[] = $this->generateGetter($key);
            $this->functions[] = $this->generateSetter($key);
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

    /**
     * @return array
     */
    public function getUse(): array
    {
        return $this->use;
    }


}
