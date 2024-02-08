<?php

namespace Enhavo\Bundle\SearchBundle\Engine\Result;

class ResultSummary
{
    public function __construct(
        private Iterable $entries,
        private int $total
    )
    {
    }

    public function getEntries(): Iterable
    {
        return $this->entries;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
