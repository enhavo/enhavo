<?php

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class SearchEngine implements SearchEngineInterface
{
    protected $container;

    protected $em;

    protected $util;

    public function __construct(Container $container, EntityManager $em, SearchUtil $util)
    {
        $this->container = $container;
        $this->em = $em;
        $this->util = $util;
    }

    /**
     * @param $query
     * @return array
     */
    public function search($query)
    {
        $request = new SearchRequest($this->container, $this->em, $this->util);

        $request->parseSearchExpression($query);

        if (!$request->hasToManyExpressions()) {

            $results = $request->search();
            if(empty($results)) {
                return [];
            }
            return $results;
        } else {
            throw new SearchEngineException('There are to many AND/OR expressions');
        }

    }
}