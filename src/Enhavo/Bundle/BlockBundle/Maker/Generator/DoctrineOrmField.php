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

    public function getName(): string
    {
        return $this->name;
    }

}
