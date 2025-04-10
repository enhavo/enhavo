<?php

namespace Enhavo\Bundle\SearchBundle\Index\Metadata;


class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    private array $index = [];
    private array $class = [];

    public function getIndex(): array
    {
        return $this->index;
    }

    public function setIndex(array $index): void
    {
        $this->index = $index;
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
