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
    public function __construct(
        private string $name,
        private string $visibility,
        private array  $config,
    )
    {
    }

    public function getInitializer(): ?string
    {
        return $this->config['initializer'] ?? null;
    }

    public function hasSerializationGroups(): bool
    {
        $groups = $this->getSerializationGroups();

         return count($groups) > 0;
    }

    public function getSerializationGroups(): array
    {
        return $this->config['serialization_groups'] ?? ['endpoint.block'];
    }

    public function getSerializationGroupsString(): string
    {
        return sprintf("'%s'", implode("', '", $this->getSerializationGroups()));
    }

    public function getDuplicateRules(): array
    {
        $default = ($this->isTypeScalar() || $this->isTypeArray()) ? [[
            'type' => 'property',
            'options' => "['groups' => ['duplicate', 'revision', 'restore']]",
        ]] : [];

        return $this->config['duplicate_rules'] ?? $default;
    }

    public function getNullable(): string
    {
        return (isset($this->config['nullable']) && $this->config['nullable'])
            ? '?' : '';
    }

    public function getAllowGetter(): bool
    {
        return !isset($this->config['allow_getter']) || $this->config['allow_getter'];
    }

    public function getAllowSetter(): bool
    {
        return !isset($this->config['allow_setter']) || $this->config['allow_setter'];
    }

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
        return $this->config['type_options']['remover'] ?? 'removeElement';
    }

    public function getSingular(): ?string
    {
        return $this->config['type_options']['singular'] ?? null;
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

    public function getTypeOption(string $key): ?array
    {
        return $this->config['type_options'][$key] ?? null;
    }

    public function isTypeScalar(): bool
    {
        return 1 === preg_match('/(string|int|float|bool)/', $this->getType());
    }

    public function isTypeArray(): bool
    {
        return 'array' === $this->getType();
    }

    public function getName(): string
    {
        return $this->name;
    }

}
