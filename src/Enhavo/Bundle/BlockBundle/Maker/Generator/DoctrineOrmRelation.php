<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Maker\Generator;

class DoctrineOrmRelation
{
    private string $name;
    private array $config;

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
        return $this->arrayToString($this->getOrderBy(), 8);
    }

    private function arrayToString(?array $array, int $indentation = 0): string
    {
        if (null === $array) {
            return 'null';
        }

        $lines = [];
        $lines[] = '[';

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $lines[] = $this->arrayToString($item, $indentation + 4);
            } else {
                $lines[] = sprintf("%s'%s' => '%s', ", str_repeat(' ', $indentation), $key, $item);
            }
        }
        $lines[] = sprintf('%s%s', str_repeat(' ', $indentation - 4), ']');

        return implode("\n", $lines);
    }
}
