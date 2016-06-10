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

        $fields = $this->getFields();

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

    protected function getFields()
    {
        $fields = array();
        $searchYamls = $this->util->getSearchYamls();
        foreach($searchYamls as $searchYaml){
            $bundleName = $this->getBundleName($searchYaml);
            $yamlContent = $this->getContentOfSearchYaml($searchYaml);
            foreach ($yamlContent as $entityPath => $val) {
                $splittedEntityPath = explode('\\', $entityPath);
                $entityName = array_pop($splittedEntityPath);
                foreach ($this->getFieldsWithWeights($yamlContent, $entityPath) as $field => $weight) {
                    $fieldName = $bundleName.'_'.strtolower($entityName).'_'.$field;
                    if($weight != null){
                        $fieldName = $fieldName.'^'.$weight;
                    }
                    $fields[] = $fieldName;
                }
            }
        }
        return $fields;
    }

    public function getBundleName($resource)
    {
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

    public function getFieldsWithWeights($searchYaml, $resourceClass)
    {
        $fields = array();
        if (key_exists($resourceClass, $searchYaml)) {
            $properties = $searchYaml[$resourceClass]['properties'];
            foreach ($properties as $field => $value) {
                if($value[0] =='Model'){
                    $fields[$field] = null;
                }else if(key($value[0]) == 'Plain' ){
                    if(key_exists('weight', $value[0]['Plain'])){
                        $fields[$field] = $value[0]['Plain']["weight"];
                    }else{
                        $fields[$field] = null;
                    }
                } else if(key($value[0]) == "Html"){
                    if(key_exists('weight', $value[0]['Html'])) {
                        $fields[$field] = $value[0]['Html']["weight"];
                    }else{
                        $fields[$field] = null;
                    }
                }else {
                    $fields[$field] = null;
                }
            }
        }
        return $fields;
    }
}