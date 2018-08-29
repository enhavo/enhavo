<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 25.08.18
 * Time: 14:22
 */

namespace Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch;


class SearchFilter
{
    /**
     * @var string[]
     */
    private $words;

    /**
     * @var string
     */
    private $contentClass = null;

    /**
     * @var array
     */
    private $queries = [];

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $orderDirection;

    /**
     * @return string[]
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param string[] $words
     */
    public function setWords($words)
    {
        $this->words = $words;
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
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param array $queries
     */
    public function setQueries($queries)
    {
        $this->queries = $queries;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param string $orderDirection
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }
}