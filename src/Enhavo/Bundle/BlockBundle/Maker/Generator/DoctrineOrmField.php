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

class DoctrineOrmField
{
    public function __construct(
        private string $name,
        private array $config
    )
    {
    }

    public function getNullable(): bool
    {
        return isset($this->config['nullable']) && $this->config['nullable'];
    }

    public function getNullableString(): string
    {
        return $this->getNullable() ? 'true' : 'false';
    }

    public function getType(): ?string
    {
        return $this->config['orm_type'] ?? $this->config['type'] ?? null;
    }

    public function getGeneratedValueType(): ?string
    {
        return isset($this->config['orm_generated_value_type']) ? strtoupper($this->config['orm_generated_value_type']) : null;
    }

    public function isPrimaryKey(): bool
    {
        return $this->config['orm_primary_key'] ?? false;
    }

    public function getName(): string
    {
        return $this->name;
    }

}
