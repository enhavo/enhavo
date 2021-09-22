<?php
/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Definition;

class ClassProperty
{
    /** @var string */
    private $name;

    /** @var array */
    private $config;

    /**
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function getInitializer()
    {
        return $this->config['initializer'] ?? null;
    }

    public function getNullable()
    {
        return $this->config['nullable'] ? '?' : '';
    }

    public function getDefault()
    {
        return $this->config['default'] ?? null;
    }

    public function getType()
    {
        return $this->config['type'] ?? null;
    }

    public function __toString()
    {
        $string = <<<TXT
    /** @var %s%s */
    public $% = %s;

TXT;
        return sprintf($string, $this->getNullable(), $this->getType(), $this->name, $this->getDefault());
    }

}
