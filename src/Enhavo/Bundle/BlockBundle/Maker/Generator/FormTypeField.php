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

class FormTypeField
{
    public function __construct(
        private string $name,
        private array $config,
    ) {
    }

    public function getClass(): string
    {
        return $this->config['class'] ?? 'string';
    }

    public function getOptions(): array
    {
        return $this->config['options'] ?? [];
    }

    public function getOptionsString(): string
    {
        return $this->arrayToString($this->getOptions());
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function arrayToString(array $array, int $indentation = 16): string
    {
        $lines = [];
        $lines[] = '[';

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $subArray = $this->arrayToString($item, $indentation + 4);
                $lines[] = sprintf("%s'%s' => %s,", str_repeat(' ', $indentation), $key, $subArray);
            } else {
                $lines[] = sprintf("%s'%s' => %s,", str_repeat(' ', $indentation), $key, $item);
            }
        }

        $lines[] = sprintf('%s]', str_repeat(' ', $indentation - 4));

        return implode(count($lines) > 2 ? "\n" : '', $lines);
    }
}
