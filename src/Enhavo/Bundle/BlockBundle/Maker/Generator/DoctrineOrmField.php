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

    public function getNullable(): string
    {
        return isset($this->config['nullable']) && $this->config['nullable'];
    }

    public function getType(): ?string
    {
        return $this->config['type'] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        $string = <<<TXT
    %s:
        type: %s
        nullable: %s

TXT;
        return sprintf($string, $this->getName(), $this->getType(), $this->getNullable());
    }

}
