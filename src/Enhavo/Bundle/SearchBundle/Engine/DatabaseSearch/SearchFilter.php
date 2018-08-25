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
    private $filter = [];

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
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
}