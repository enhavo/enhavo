<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.06.16
 * Time: 16:53
 */

namespace Enhavo\Bundle\SearchBundle\Search;

use Elasticsearch;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class ElasticSearchEngine implements SearchEngineInterface
{
    protected $em;

    protected $util;

    public function __construct(EntityManager $em, SearchUtil $util)
    {
        $this->em = $em;
        $this->util = $util;
    }

    public function search($query, $filters = []){
        $client = Elasticsearch\ClientBuilder::create()->build();

        $params = [
            'body' => [
                "query" => [
                    "query_string" => [
                        "query" => $query
                    ]
                ],
                "highlight" => [
                    "fields" => [
                        "teaser" => []
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        $resultResources = [];
        if(!empty($results['hits']['hits'])){
            foreach($results['hits']['hits'] as $hit){
                $resId   = $hit['_id'];
                $resBundle = explode('_', $hit['_index']);
                foreach($resBundle as &$element){
                    $element = ucfirst($element);
                }
                $resBundle = implode('', $resBundle);
                $resEntity = $hit['_type'];
                $resource = $this->em->getRepository($resBundle.':'.ucfirst($resEntity))->find($resId);
                $resultResources[] = $resource;
            }
        } else {
            return$resultResources;
        }

        $words = preg_replace('(AND|OR)', '', $query);
        $words = $this->util->searchSimplify($words);
        $words = explode(' ', $words);
        $searchResult = new SearchResult();
        $searchResult->setWords($words);
        $searchResult->setResources($resultResources);
        return $searchResult;
    }
}