<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.06.16
 * Time: 12:48
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Elasticsearch;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;

class IndexElasticsearchEngine implements IndexEngineInterface
{
    protected $util;

    protected $index;

    protected $type;

    public function __construct(SearchUtil $util)
    {
        $this->util = $util;
    }

    public function index($resource)
    {
        $client = Elasticsearch\ClientBuilder::create()->build();
        //get Entity and Bundle names
        $this->index = $this->util->getBundleName($resource, true);
        $this->type = $this->util->getEntityName($resource);

        $params = [
            'index' => $this->index,
            'type' => $this->type,
            'id' => $resource->getID(),
            'body' => []
        ];

        $properties = $this->util->getProperties($resource);
        //indexing words (go through all the fields that can be indexed according to the search yml)
        foreach($properties as $indexingField => $value) {

            //look if there is a field (indexingField) in the request (currentRequest) that can get indexed
            $accessor = PropertyAccess::createPropertyAccessor();
            if(property_exists($resource, $indexingField) && $value[0] != 'Model' && !array_key_exists('Collection',$value[0])) {
                //Plain and Html
                $indexingValue = $this->getTextContent($resource, $indexingField);
                $params = $this->addToBody($params, $indexingField, $indexingValue);
            } else if(property_exists($resource, $indexingField) && $value[0] == 'Model'){
                //Model
                $model = $accessor->getValue($resource, $indexingField);
                $content = $this->getModelContent($model);
                $params = $this->addToBody($params, $indexingField, $content);
            } else if(property_exists($resource, $indexingField) && array_key_exists('Collection',$value[0])) {
                //Collection
                $collection = $accessor->getValue($resource, $indexingField);
                if($collection != null){
                    $content = $this->getCollectionContent($collection, $this->util->getSearchYaml($resource), $value[0]);
                    $params = $this->addToBody($params, $indexingField, $content);
                }
            }
        }
        $client->index($params);
    }

    public function unindex($resource)
    {
        $client = Elasticsearch\ClientBuilder::create()->build();
        //get Entity and Bundle names
        $index = $this->util->getBundleName($resource, true);
        $type = $this->util->getEntityName($resource);
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $resource->getID()
        ];

        // Delete doc at /my_index/my_type/my_id
        $client->delete($params);
    }

    public function reindex(){}

    protected function addToBody($params, $field, $value)
    {
        if($value){
            $params['body'][$this->index.'_'.$this->type.'_'.$field] = $value;
        }
        return $params;
    }

    protected function getModelContent($model){
        $content = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        $modelSearchYml = $this->util->getSearchYaml($model);
        $class = null;
        if ($model instanceof \Doctrine\Common\Persistence\Proxy) {
            $class = get_parent_class($model);
        } else {
            $class = get_class($model);
        }
        if(array_key_exists($class, $modelSearchYml)){
            $modelProperties = $modelSearchYml[$class]['properties'];
            foreach($modelProperties as $key => $value){
                if(property_exists($model, $key) && $value[0] != 'Model' && !array_key_exists('Collection',$value[0])) {
                    //Plain and Html
                    $indexingValue = $this->getTextContent($model, $key);
                    if($indexingValue){
                        $fieldKey = $this->getBundleName($class).'_'.$this->getEntityName($class);
                        $content[$fieldKey.'_'.$key] = $indexingValue;
                    }
                } else if(property_exists($model, $key)  && $value[0] == 'Model'){
                    //Model
                    $model = $accessor->getValue($model, $key);
                    $modelContent = $this->getModelContent($model);
                    if($modelContent) {
                        $fieldKey = $this->getBundleName($class).'_'.$this->getEntityName($class);
                        $content[$fieldKey.'_'.$key] = $modelContent;
                    }
                } else if(property_exists($model, $key)  && key($value[0]) == 'Collection') {
                    //Collection
                    $collection = $accessor->getValue($model, $key);
                    if($collection != null){
                        $indexingValue = $this->getCollectionContent($collection, $modelSearchYml, $value[0]);
                        if($indexingValue) {
                            $fieldKey = $this->getBundleName($class).'_'.$this->getEntityName($class);
                            $content[$fieldKey.'_'.$key] = $indexingValue;
                        }
                    }
                }
            }
        }
        return $content;
    }

    protected function getCollectionContent($collection, $searchYml, $colType){
        $content = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        if(array_key_exists('entity',$colType['Collection'])) {
            $properties = $searchYml[$colType['Collection']['entity']]['properties'];
            foreach ($collection as $collectionElement) {
                foreach ($properties as $key => $value) {
                    if (property_exists($collectionElement, $key) && $value[0] != 'Model' && !array_key_exists('Collection', $value[0])) {
                        //Plain and Html
                        $indexingValue = $this->getTextContent($collectionElement, $key);
                        if($indexingValue) {
                            $content[][$key] = $indexingValue;
                        }
                    } else if (property_exists($collectionElement, $key) && $value[0] == 'Model') {
                        //Model
                        $model = $accessor->getValue($collectionElement, $key);
                        $modelContent = $this->getModelContent($model);
                        if($modelContent) {
                            $content[][$key] = $modelContent;
                        }
                    } else if (property_exists($collectionElement, $key) && key($value[0]) == 'Collection') {
                        //Collection
                        $collection = $accessor->getValue($collectionElement, $key);
                        if($collection != null){
                            $indexingValue = $this->getCollectionContent($collection, $searchYml, $value[0]);
                            if($indexingValue) {
                                $content[][$key] = $indexingValue;
                            }
                        }
                    }
                }
            }
        } else if(array_key_exists(0,$colType['Collection'])){
            foreach($collection as $indexingValue){
                $content[] = $indexingValue;
            }
        }
        return $content;
    }

    protected function getTextContent($resource, $field){
        $accessor = PropertyAccess::createPropertyAccessor();
        $content = $accessor->getValue($resource, $field);
        return $content;
    }

    public function getBundleName($resource)
    {
        $entityPath = $resource;

        $splittedBundlePath = explode('\\', $entityPath);
        while(strpos(end($splittedBundlePath), 'Bundle') != true){
            array_pop($splittedBundlePath);
        }
        $lowercaseArray = [];
        $lowercaseArray[] = strtolower($splittedBundlePath[count($splittedBundlePath)-3]);
        $pieces = preg_split('/(?=[A-Z])/',end($splittedBundlePath));
        foreach(array_filter($pieces) as $piece){
            $lowercaseArray[] = strtolower($piece);
        }
        return implode('_', $lowercaseArray);
    }

    public function getEntityName($entityPath)
    {
        $splittedBundlePath = explode('\\', $entityPath);
        $entityName = array_pop($splittedBundlePath);
        return strtolower($entityName);
    }


}