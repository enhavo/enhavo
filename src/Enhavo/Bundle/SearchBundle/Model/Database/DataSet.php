<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Model\Database;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * DataSet
 */
class DataSet
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $contentClass;

    /**
     * @var object
     */
    private $content;

    /**
     * @var int
     */
    private $contentId;

    /**
     * @var ArrayCollection
     */
    private $indexes;

    /**
     * @var ArrayCollection
     */
    private $filters;

    public function __construct()
    {
        $this->indexes = new ArrayCollection();
        $this->filters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContentClass()
    {
        return $this->contentClass;
    }

    /**
     * @param string $contentClass
     */
    public function setContentClass($contentClass)
    {
        $this->contentClass = $contentClass;
    }

    /**
     * @return object
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param object $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    public function addIndex($index)
    {
        $index->setDataset($this);
        $this->indexes->add($index);
    }

    /**
     * @param mixed $index
     */
    public function removeIndex(Index $index)
    {
        $index->setDataset(null);
        $this->indexes->removeElement($index);
    }

    /**
     * @return ArrayCollection
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    public function resetIndex()
    {
        /** @var Index $index */
        foreach ($this->indexes as $index) {
            $index->setDataSet(null);
        }
        $this->indexes->clear();
    }

    public function addFilter(Filter $filter)
    {
        $filter->setDataSet($this);
        $this->filters->add($filter);
    }

    /**
     * @param mixed $filter
     */
    public function removeFilter(Filter $filter)
    {
        $filter->setDataSet(null);
        $this->filters->removeElement($filter);
    }

    /**
     * @return ArrayCollection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    public function resetFilter()
    {
        /** @var Filter $filter */
        foreach ($this->filters as $filter) {
            $filter->setDataSet(null);
        }
        $this->filters->clear();
    }
}
