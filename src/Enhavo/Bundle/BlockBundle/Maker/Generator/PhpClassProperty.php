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

use Symfony\Component\String\Inflector\EnglishInflector;

class PhpClassProperty
{
    /** @var string */
    private $name;

    /** @var string */
    private $visibility = 'private';

    /** @var array */
    private $config;

    /**
     * @param string $name
     * @param string $visibility
     * @param array $config
     */
    public function __construct(string $name, string $visibility, array $config)
    {
        $this->name = $name;
        $this->visibility = $visibility;
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

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function getAdder(): string
    {
        return $this->config['type_options']['adder'] ?? 'add';
    }

    public function getRemover(): string
    {
        return $this->config['type_options']['adder'] ?? 'add';
    }

    public function getSingular(): string
    {
        return $this->config['type_options']['singular'] ?? $this->__getSingular();
    }

    private function __getSingular(): string
    {
        $inflector = new EnglishInflector();
        $result = $inflector->singularize($this->getName());
        return array_shift($result);
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

    public function getTypeOptions(): ?array
    {
        return $this->config['type_options'] ?? null;
    }

    public function getTypeOption(string $key): ?array
    {
        return $this->config['type_options'][$key] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
