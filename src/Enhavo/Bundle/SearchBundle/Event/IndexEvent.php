<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 14.05.18
 * Time: 00:01
 */

namespace Enhavo\Bundle\SearchBundle\Event;


use Enhavo\Bundle\SearchBundle\Indexer\Filter;
use Enhavo\Bundle\SearchBundle\Indexer\IndexData;
use Symfony\Component\EventDispatcher\Event;

class IndexEvent extends Event
{
    /**
     * @var object
     */
    private $subject;

    /**
     * @var IndexData[]
     */
    private $indexes = [];

    /**
     * @var Filter[]
     */
    private $filters = [];

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return IndexData[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param IndexData[] $indexes
     */
    public function setIndexes($indexes)
    {
        $this->indexes = $indexes;
    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Filter[] $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return object
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
