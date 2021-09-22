<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

use Enhavo\Bundle\BlockBundle\Maker\Definition\ClassProperty;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

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
            $properties[] = new ClassProperty($key, $config);
        }

        return $properties;
    }

    public function generateGetterSetters()
    {
        foreach ($this->properties as $key => $config) {

            $this->functions[] = $this->generateGetter($key, $config);
            $this->functions[] = $this->generateSetter($key, $config);
        }
    }

    public function generateGetter($key, $config): PhpFunction
    {
        $nullable = $config['nullable'] ? '?' : '';
        $name = sprintf('get%s', ucfirst($key));
        $returns = sprintf('%s%s', $nullable, $config['type']);
        $body = [sprintf('return $this->%s', $key)];
        return new PhpFunction($name, 'public', [], $body, $returns);
    }

    public function generateSetter($key, $config): PhpFunction
    {
        $name = sprintf('set%s', ucfirst($key));
        $args = [
            $config['type'] => $key,
        ];
        $body = [sprintf('$this->%s = %s;', $key, $config['type'])];
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
