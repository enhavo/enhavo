<?php


namespace Enhavo\Bundle\SearchBundle\Metadata;

use Enhavo\Component\Metadata\Extension\Config;

/**
 * Metadata.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class Metadata extends \Enhavo\Component\Metadata\Metadata
{
    /** @var Config[] */
    private array $index = [];

    /** @var Config[] array */
    private array $filters = [];

    public function getEntityName(): string
    {
        $splitClassName = preg_split('/\\\\|\//', $this->getClassName());
        return array_pop($splitClassName);
    }

    public function addIndex(Config $index)
    {
        $this->index[] = $index;
    }

    /** @return Config[] */
    public function getIndex(): array
    {
        return $this->index;
    }

    /**
     * @param Config $index
     */
    public function removeIndex(Config $index)
    {
        if (false !== $key = array_search($index, $this->index, true)) {
            array_splice($this->index, $key, 1);
        }
    }

    public function addFilter(Config $filter)
    {
        $this->filters[] = $filter;
    }

    /** @return Config[] */
    public function getFilter(): array
    {
        return $this->filters;
    }

    /**
     * @param Config $filter
     */
    public function removeFilter(Config $filter)
    {
        if (false !== $key = array_search($filter, $this->filters, true)) {
            array_splice($this->filters, $key, 1);
        }
    }
}
