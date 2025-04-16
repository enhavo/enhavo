<?php

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;


use Enhavo\Component\Metadata\Extension\Config;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /** @var Config[] array */
    private array $index = [];
    /** @var Config[] array */
    private array $filter = [];

    public function getIndex(): array
    {
        return $this->index;
    }

    public function setIndex(array $index): void
    {
        $this->index = $index;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): void
    {
        $this->filter = $filter;
    }
}
