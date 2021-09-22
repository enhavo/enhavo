<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Definition;

use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;
use Enhavo\Bundle\BlockBundle\Maker\Generator\PhpClass;

class BlockDefinition
{
    /** @var array */
    private $config;

    /** @var string  */
    private $name;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {
            $this->name = $key;
            $this->config = $value;
            break;
        }
    }

    public function createPhpClass(): PhpClass
    {
        $properties = $this->config['properties'];
        $extends = AbstractBlock::class;
        $namespace = sprintf('%s\Entity', $this->getNamespace());
        $class = new PhpClass($namespace, $this->name, $extends, $this->getUse(), $properties);
        $class->generateGetterSetters();

        return $class;
    }

    public function getNamespace()
    {
        return $this->config['namespace'] ?? 'App';
    }

    private function getUse()
    {
        return $this->config['use'] ?? [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}
