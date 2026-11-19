<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tab;

/**
 * Parses an arrangement of form children: one row per line, columns separated by "|", "{n}" is the relative size of a
 * column, e.g. "name | title{2}".
 */
class ArrangementParser
{
    /**
     * @param string|string[]|null $arrangement
     *
     * @return array<int, array<int, array{key: string, size: int}>>
     */
    public function parse(string|array|null $arrangement): array
    {
        if (null === $arrangement) {
            return [];
        }

        $lines = is_array($arrangement) ? $arrangement : explode("\n", $arrangement);

        $rows = [];
        foreach ($lines as $line) {
            $rows[] = $this->parseRow($line);
        }

        return $rows;
    }

    private function parseRow(string $line): array
    {
        $columns = explode('|', $line);
        $data = [];
        foreach ($columns as $column) {
            preg_match('/([A-Za-z.-_]+)/', trim($column), $matches);
            $key = $matches[1] ?? null;

            preg_match('/\{([0-9]+)\}/', trim($column), $matches);
            $size = intval($matches[1] ?? 1);

            if (null !== $key && $size > 0) {
                $data[] = [
                    'key' => $key,
                    'size' => $size,
                ];
            }
        }

        return $data;
    }
}
