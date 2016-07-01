<?php

namespace Enhavo\Bundle\SearchBundle\Util;
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 18.05.16
 * Time: 15:13
 */

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Persistence\Proxy;

class SearchUtil
{
    protected $kernel;

    protected $mainPath;

    protected $container;

    public function __construct($kernel, Container $container)
    {
        $this->kernel = $kernel;
        $this->container = $container;
    }

    public function searchSimplify($text)
    {
        $textSimplifiy = new TextSimplify();
        return $textSimplifiy->simplify($text);
    }

    public function getSearchYamls()
    {
        //get all bundles
        $bundles = $this->kernel->getBundles();
        //get all search.pml paths
        $searchYamlPaths = array();
        foreach ($bundles as $bundle) {
            if (file_exists($bundle->getPath() . '/Resources/config/search.yml')) {
                $searchYamlPaths[] = $this->kernel->locateResource('@' . $bundle->getName() . '/Resources/config/search.yml');

            } else if ($bundle->getName() == 'EnhavoSearchBundle') {
                $searchBundlePath = $bundle->getPath();
                $splittedPath = explode('/', $searchBundlePath);
                while (end($splittedPath) != 'src') {
                    array_pop($splittedPath);
                }
                $this->mainPath = implode('/', $splittedPath);
            }
        }
        return $searchYamlPaths;
    }

    protected function getBundleNameOfResource($resource)
    {
        $resourceClassName = get_class($resource);
        if ($resource instanceof Proxy) {
            $resourceClassName = get_parent_class($resource);
        }

        $bundles = $this->container->get('kernel')->getBundles();

        foreach($bundles as $bundle) {
            $class = get_class($bundle);
            $classParts = explode('\\', $class);
            $bundleName = array_pop($classParts);
            $bundlePath = implode('\\', $classParts);
            if(strpos($resourceClassName, $bundlePath) === 0) {
                return $bundleName;
            }
        }
        return null;
    }

    public function getSearchYaml($resource)
    {
        $bundleName = $this->getBundleNameOfResource($resource);

        try {
            $file = $this->container->get('kernel')->locateResource(sprintf('@%s/Resources/config/search.yml', $bundleName));
        } catch(\Exception $e) {
            return null;
        }

        $parser = new Parser();
        return $parser->parse(file_get_contents($file));
    }

    public function getFieldsOfSearchYml($searchYaml, $resourceClass)
    {
        $fields = array();
        if (key_exists($resourceClass, $searchYaml)) {
            $properties = $searchYaml[$resourceClass]['properties'];
            foreach ($properties as $field => $value) {
                $fields[] = $field;
            }
        }
        return $fields;
    }

    public function getValueOfField($field, $searchYaml, $resourceClass)
    {
        $properties = $searchYaml[$resourceClass]['properties'];
        $value = $properties[$field];
        return $value;
    }

    public function getMainPath()
    {
        $this->getSearchYamls();
        return $this->mainPath; //Users/jhelbing/workspace/enhavo/src/
    }

    public function getEntityName($resource)
    {
        $entityPath = get_class($resource);
        $splittedBundlePath = explode('\\', $entityPath);
        $entityName = '';
        while(end($splittedBundlePath) != 'Entity'){
            $entityName = array_pop($splittedBundlePath);
        }
        return strtolower($entityName);
    }

    public function getBundleName($resource, $lowercase = false)
    {
        $entityPath = get_class($resource);
        $splittedBundlePath = explode('\\', $entityPath);
        while(strpos(end($splittedBundlePath), 'Bundle') != true){
            array_pop($splittedBundlePath);
        }
        if($lowercase){
            $lowercaseArray = [];
            if($splittedBundlePath[0] == 'Enhavo'){
                $lowercaseArray[] = strtolower($splittedBundlePath[0]);
            }
            $pieces = preg_split('/(?=[A-Z])/',end($splittedBundlePath));
            foreach(array_filter($pieces) as $piece){
                $lowercaseArray[] = strtolower($piece);
            }
            return implode('_', $lowercaseArray);
        }
        if($splittedBundlePath[0] == 'Enhavo'){
            return $splittedBundlePath[0].end($splittedBundlePath);
        } else {
            return end($splittedBundlePath);
        }
    }

    public function getProperties($resource)
    {
        $currentSearchYaml = $this->getSearchYaml($resource);

        return $currentSearchYaml[get_class($resource)]['properties'];
    }

    public function getEntityNamesOfSearchYamlPath($yamlPath)
    {
        $yamlContent = $this->getContentOfSearchYaml($yamlPath);
        $entities = array();
        foreach($yamlContent as $key => $value){
            if(!$this->isEntityCollection($value) && !$this->isEntityModel($value)) {
                $splittedKey = explode('\\', $key);
                $entities[] = array_pop($splittedKey);
            }
        }
        return $entities;
    }

    public function getContentOfSearchYaml($searchYamlPath)
    {
        if (file_exists($searchYamlPath)) {
            $yaml = new Parser();
            return $yaml->parse(file_get_contents($searchYamlPath));
        } else {
            return null;
        }
    }

    public function isEntityCollection($yamlEntity)
    {
        $collection = true;
        $prop = $yamlEntity['properties'];
        foreach($prop as $item){
            if(is_array($item[0])){
                if(key($item[0]) != 'Collection'){
                    $collection = false;
                }
            } else if($item[0] == 'Model'){
                $collection = false;
            }
        }
        return $collection;
    }

    public function isPropertyCollection($property)
    {
        $collection = true;
        if(is_array($property[0])){
            if(key($property[0]) != 'Collection'){
                $collection = false;
            }
        } else if($property[0] == 'Model'){
            $collection = false;
        }
        return $collection;
    }

    public function isEntityModel($yamlEntity)
    {
        $model = true;
        $prop = $yamlEntity['properties'];
        foreach($prop as $item){
            if(is_array($item[0])){
                $model = false;
            }
        }
        return $model;
    }

    public function isPropertyModel($property)
    {
        $model = true;
        if(is_array($property[0])){
            $model = false;
        }
        return $model;
    }

    public function getTypes($searchYaml, $resourceClass)
    {
        $types = array();
        if (key_exists($resourceClass, $searchYaml)) {
            $properties = $searchYaml[$resourceClass]['properties'];
            foreach ($properties as $property) {
                if(!$this->isPropertyCollection($property) && !$this->isPropertyModel($property)){
                    foreach ($property[0] as $key => $value) {
                        if(array_key_exists('type',$value)){
                            if(!in_array($value['type'], $types)){
                                $types[] = $value['type'];
                            }
                        }
                    }
                }
            }
        }
        return $types;
    }
}