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

    public function getAdder(): string
    {
        return $this->config['type_options']['adder'] ?? 'add';
    }

    public function getRemover(): string
    {
        return $this->config['type_options']['adder'] ?? 'add';
    }

    public function getEntryClass(): ?string
    {
        return $this->config['type_options']['entry_class'] ?? null;
    }

    public function getConstructor(): ?string
    {
        return $this->config['type_options']['constructor'] ?? null;
    }

    public function getMappedBy(): ?string
    {
        return $this->config['relation']['mapped_by'] ?? null;
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
