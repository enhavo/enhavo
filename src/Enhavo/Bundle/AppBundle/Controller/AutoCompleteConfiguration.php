<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 05.09.18
 * Time: 19:12
 */

namespace Enhavo\Bundle\AppBundle\Controller;


class AutoCompleteConfiguration
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $repositoryMethod;

    /**
     * @var array
     */
    private $repositoryArguments;

    /**
     * @var boolean
     */
    private $paginated = false;

    /**
     * @var integer
     */
    private $limit = 10;

    /**
     * @var string
     */
    private $choiceLabel;

    /**
     * @var string
     */
    private $searchTerm = '';

    /**
     * @var integer
     */
    private $page = 1;

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
    public function getRepositoryMethod()
    {
        return $this->repositoryMethod;
    }

    /**
     * @param string $repositoryMethod
     */
    public function setRepositoryMethod($repositoryMethod)
    {
        $this->repositoryMethod = $repositoryMethod;
    }

    /**
     * @return array
     */
    public function getRepositoryArguments()
    {
        return $this->repositoryArguments;
    }

    /**
     * @param array $repositoryArguments
     */
    public function setRepositoryArguments($repositoryArguments)
    {
        $this->repositoryArguments = $repositoryArguments;
    }

    /**
     * @return bool
     */
    public function isPaginated()
    {
        return $this->paginated;
    }

    /**
     * @param bool $paginated
     */
    public function setPaginated($paginated)
    {
        $this->paginated = $paginated;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return string
     */
    public function getChoiceLabel()
    {
        return $this->choiceLabel;
    }

    /**
     * @param string $choiceLabel
     */
    public function setChoiceLabel($choiceLabel)
    {
        $this->choiceLabel = $choiceLabel;
    }

    /**
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }
}