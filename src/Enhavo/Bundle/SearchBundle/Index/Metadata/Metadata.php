<?php

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;


use Enhavo\Component\Metadata\Extension\Config;

class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /** @var Config[] array */
    private array $index = [];
    /** @var Config[] array */
    private array $filters = [];
    private array $class = [];

    public function getIndex(): array
    {
        return $this->index;
    }

    public function setIndex(array $index): void
    {
        $this->index = $index;
    }

    public function removeIndex(Config $index): void
    {
        if (false !== $key = array_search($index, $this->index, true)) {
            array_splice($this->index, $key, 1);
        }
    }

    public function getFilter(): array
    {
        return $this->filters;
    }

    public function addFilter(Config $filter): void
    {
        $this->filters[] = $filter;
    }

    public function removeFilter(Config $filter): void
    {
        if (false !== $key = array_search($filter, $this->filters, true)) {
            array_splice($this->filters, $key, 1);
        }
    }
    public function getClass(): array
    {
        return $this->class;
    }

    public function setClass(array $class): void
    {
        $this->class = $class;
    }
}
