<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author blutze-media
 *
 * @since 2021-09-22
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class PhpClassProperty
{
    public function __construct(
        private string $name,
        private string $visibility,
        private array $config,
    ) {
    }

    public function getInitializer(): ?string
    {
        return $this->config['initializer'] ?? null;
    }

    public function hasSerializationGroups(): bool
    {
        return null !== $this->getSerializationGroups();
    }

    public function getSerializationGroups(): ?array
    {
        if (!isset($this->config['serialization_groups'])) {
            return ['endpoint.block'];
        } elseif (is_array($this->config['serialization_groups'])) {
            return $this->config['serialization_groups'];
        }

        return null;
    }

    public function getSerializationGroupsString(): string
    {
        $groups = $this->getSerializationGroups();

        if (count($groups)) {
            return sprintf("['%s']", implode("', '", $groups));
        }

        return '';
    }

    public function getAttributes(): array
    {
        $attributes = [];
        if (isset($this->config['attributes'])) {
            foreach (array_reverse($this->config['attributes']) as $attribute) {
                if (!$this->hasAttribute($attribute, $attributes)) {
                    $attributes[] = $attribute;
                }
            }
        } else {
            if ($this->isTypeScalar() || $this->isTypeArray()) {
                $attributes[] = [
                    'class' => 'Duplicate',
                    'type' => 'property',
                    'options' => "['groups' => ['duplicate', 'revision', 'restore']]",
                ];
            }
        }

        return array_reverse($attributes);
    }

    private function hasAttribute($attribute, $array): bool
    {
        foreach ($array as $item) {
            $rString = str_replace(' ', '', implode(',', $item));
            $aString = str_replace(' ', '', implode(',', $attribute));
            if ($aString === $rString) {
                return true;
            }
        }

        return false;
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
