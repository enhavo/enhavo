<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 17:54
 */

namespace Enhavo\Bundle\SearchBundle\Search;


class SearchResult {

    private $words;

    private $resources;

    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param mixed $words
     */
    public function setWords($words)
    {
        $this->words = $words;
    }

    /**
     * @return mixed
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param mixed $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }
}