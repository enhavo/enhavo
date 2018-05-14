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
    /**
     * @var string
     */
    private $term;

    /**
     * @var array
     */
    private $filter = [];

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
     * @param $value
     */
    public function addFilter($key, $value)
    {
        $this->filter[$key] = $value;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }
}