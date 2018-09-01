<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 09.05.18
 * Time: 15:56
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class Filter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    /**
     * @var string
     */
    private $term;

    /**
     * @var QueryInterface[]
     */
    private $queries = [];

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $orderDirection;

    /**
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param string $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * @param $key
     * @param $query
     */
    public function addQuery($key, QueryInterface $query)
    {
        $this->queries[$key] = $query;
    }

    /**
     * @param $key
     */
    public function removeQuery($key)
    {
        if(isset($this->queries[$key])) {
            unset($this->queries[$key]);
        }
    }

    /**
     * @return QueryInterface[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
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
     * @param string $orderDirection
     */
    public function setOrderBy($orderBy, $orderDirection = null)
    {
        $this->orderDirection = $orderDirection;
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