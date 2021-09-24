<?php
/**
 * @author blutze-media
 * @since 2021-09-23
 */

/**
 * @author blutze-media
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpClassProperty
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

    public function getInitializer(): ?string
    {
        return $this->config['initializer'] ?? null;
    }

    public function getNullable(): string
    {
        return (isset($this->config['nullable']) && $this->config['nullable'])
            ? '?' : '';
    }

    public function getDefault(): string
    {
        return $this->config['default'] ?? 'null';
    }

    public function getType(): ?string
    {
        return $this->config['type'] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVisibility(): string
    {
        return 'private';
    }

    public function __toString()
    {
        $string = <<<TXT
    /** @var %s%s */
    public $%s = %s;


TXT;
        return sprintf($string, $this->getNullable(), $this->getType(), $this->name, $this->getDefault());
    }

}
