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
use Symfony\Component\Yaml\Parser;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
/*
 * This class does the search process of elasticsearch
 */
class ElasticSearchEngine implements SearchEngineInterface
{
    protected $em;

    protected $util;

    protected $metadataFactory;

    public function __construct(EntityManager $em, SearchUtil $util, MetadataFactory $metadataFactory)
    {
        $this->em = $em;
        $this->util = $util;
        $this->metadataFactory = $metadataFactory;
    }

    public function search($query, $filters = [], $entities = null, $fields= null){
        $client = Elasticsearch\ClientBuilder::create()->build();

        //get fields in which we want to search
        $fields = $this->getFields($entities, $fields);

        //add fields an the query to params body
        $params = [
            'body' => [
                "query" => [
                    "query_string" => [
                        "fields" => $fields,
                        'query' => $query
                    ]
                ]
            ]
        ];

        //the client does the searching
        $results = $client->search($params);
        $resultResources = [];

        //if there are results extract bundle, entity an resourceId to get the real resource we can display
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

        //return results and search words
        $words = preg_replace('(AND|OR)', '', $query);
        $words = $this->util->searchSimplify($words);
        $words = explode(' ', $words);
        array_unshift($words, $query);
        $searchResult = new SearchResult();
        $searchResult->setWords($words);
        $searchResult->setResources($resultResources);
        return $searchResult;
    }

    protected function getFields($entities, $searchFields)
    {
        //prepare all fields in the format bundlename_field^weight
        $fields = array();
        $searchYamls = $this->util->getSearchYamls();
        foreach($searchYamls as $searchYaml){
            $bundleName = $this->getBundleName($searchYaml);
            $yamlContent = $this->getContentOfSearchYaml($searchYaml);
            foreach ($yamlContent as $entityPath => $val) {
                $splittedEntityPath = explode('\\', $entityPath);
                $entityName = array_pop($splittedEntityPath);
                if($entities == null || in_array(lcfirst($entityName), $entities)){
                    foreach ($this->getFieldsWithWeights($entityPath, $searchFields) as $field => $weight) {
                        $fieldName = $bundleName.'_'.strtolower($entityName).'_'.$field;
                        if($weight != null){
                            $fieldName = $fieldName.'^'.$weight;
                        }
                        $fields[] = $fieldName;
                    }
                }
            }
        }
        return $fields;
    }

    public function getBundleName($resource)
    {
        //get the bundlename of a resource in format "..._..._..."
        $entityPath = $resource;

        $splittedBundlePath = explode('/', $entityPath);
        while(strpos(end($splittedBundlePath), 'Bundle') != true){
            array_pop($splittedBundlePath);
        }
        $lowercaseArray = [];
        if($splittedBundlePath[count($splittedBundlePath)-3] == 'Enhavo'){
            $lowercaseArray[] = strtolower($splittedBundlePath[count($splittedBundlePath)-3]);
        }
        $pieces = preg_split('/(?=[A-Z])/',end($splittedBundlePath));
        foreach(array_filter($pieces) as $piece){
            $lowercaseArray[] = strtolower($piece);
        }
        return implode('_', $lowercaseArray);
    }

    protected function getContentOfSearchYaml($searchYamlPath)
    {
        if (file_exists($searchYamlPath)) {
            $yaml = new Parser();
            return $yaml->parse(file_get_contents($searchYamlPath));
        } else {
            return null;
        }
    }

    public function getFieldsWithWeights($resourceClass, $searchFields)
    {
        //get fields with weights
        $fields = array();
        $metadata = $this->metadataFactory->create($resourceClass);

        foreach ($metadata->getProperties() as $propertyNode) {
            if($searchFields == null || ($propertyNode->getOptions() != null && array_key_exists('type', $propertyNode->getOptions())&& in_array($propertyNode->getOptions()['type'], $searchFields))) {
                if ($propertyNode->getOptions() != null && array_key_exists('weight', $propertyNode->getOptions())) {
                    $fields[$propertyNode->getProperty()] = $propertyNode->getOptions()['weight'];
                } else {
                    $fields[$propertyNode->getProperty()] = null;
                }
            }
        }
        return $fields;
    }
}