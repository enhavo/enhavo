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
    public function search($query, $filters = [])
    {
        $request = new SearchRequest($this->container, $this->em, $this->util);

        $request->parseSearchExpression($query);

        if (!$request->hasToManyExpressions()) {
            $results = $request->search();
            $words = $request->getWords();
            $resultResources = array();
            foreach ($results as $result) {
                $allFilters = true;
                foreach ($filters as $filter) {
                    if(!$filter->isGranted($result)){
                        $allFilters = false;
                        break;
                    }
                }
                if($allFilters){
                    $resultResources[] = $result;
                }
            }
            if(empty($resultResources)) {
                return [];
            }
            $searchResult = new SearchResult();
            $searchResult->setWords($words);
            $searchResult->setResources($resultResources);
            return $searchResult;
        } else {
            throw new SearchEngineException('There are to many AND/OR expressions');
        }
    }
}