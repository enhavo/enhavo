<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

class ResultSummary
{
    public function __construct(
        private iterable $entries,
        private int $total,
    ) {
    }

    public function getEntries(): iterable
    {
        return $this->entries;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
