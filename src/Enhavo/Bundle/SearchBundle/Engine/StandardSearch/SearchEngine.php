<?php

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/*
 * This class starts the search process for enhavo
 */
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
    public function search($query, $filters = [], $types = null, $fields = null)
    {
        $request = new SearchRequest($this->container, $this->em, $this->util);

        //prepare for searching
        $request->parseSearchExpression($query);

        //check if there ar not to many conditions
        if (!$request->hasToManyExpressions()) {

            //search
            $results = $request->search($types, $fields);

            //get searchwords of request
            $words = $request->getWords();

            //check filters
            $resultResources = array();

            foreach ($results as $result) {
                $resultResources[] = $result;
//                $allFilters = true;
//                foreach ($filters as $filter) {
//                    if(!$filter->isGranted($result)){
//                        $allFilters = false;
//                        break;
//                    }
//                }
//                if($allFilters){
//                    $resultResources[] = $result;
//                }
            }

            //return searchResult
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