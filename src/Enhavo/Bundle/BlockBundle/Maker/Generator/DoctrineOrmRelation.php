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

class DoctrineOrmRelation
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

    public function getType(): ?string
    {
        return $this->config['type'] ?? null;
    }

    public function getInversedBy(): ?string
    {
        return $this->config['inversed_by'] ?? null;
    }

    public function getMappedBy(): ?string
    {
        return $this->config['mapped_by'] ?? null;
    }

    public function getOrderBy(): ?array
    {
        return $this->config['order_by'] ?? null;
    }

    public function getTargetEntity(): ?string
    {
        return $this->config['target_entity'] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOrderByString(): string
    {
        return $this->__arrayToString($this->getOrderBy());
    }

    public function __toString()
    {
        if ($this->getType() === 'oneToOne') {
            return $this->__toOneToOneString();

        } else if ($this->getType() === 'oneToMany') {
            return $this->__toOneToManyString();

        } else if ($this->getType() === 'manyToOne') {
            return $this->__toManyToOneString();

        } else if ($this->getType() === 'manyToMany') {
            return $this->__toManyToManyString();
        }

        return '';
    }

    private function __toManyToManyString()
    {
        return '';
    }

    private function __toOneToManyString()
    {
        $string = <<<TXT
        %s:
            targetEntity: %s
            mappedBy: %s
            cascade: [ 'persist', 'refresh', 'remove' ]
            orderBy: %s
            orphanRemoval: true
TXT;
        return sprintf($string, $this->getName(), $this->getTargetEntity(), $this->getMappedBy(), $this->getOrderByString());
    }

    private function __toManyToOneString()
    {
        $string = <<<TXT
        %s:
            targetEntity: %s
            cascade: [ 'persist', 'refresh', 'remove' ]
            inversedBy: %s
            joinColumn:
                onDelete: CASCADE

TXT;
        return sprintf($string, $this->getName(), $this->getTargetEntity(), $this->getInversedBy());
    }

    private function __toOneToOneString()
    {
        $string = <<<TXT
        %s:
            cascade: [ 'persist', 'refresh', 'remove' ]
            targetEntity: %s
TXT;
        return sprintf($string, $this->getName(), $this->getTargetEntity());
    }


    private function __arrayToString(?array $array, int $indentation = 8)
    {
        if (null === $array) {
            return null;
        }

        $lines = [];
        $lines[] = '';

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $lines[] = $this->__arrayToString($item, $indentation+4);
            } else {
                $lines[] = sprintf("%s%s: %s", str_repeat(' ', $indentation), $key, $item);
            }
        }

        return implode("\n", $lines);
    }
}
