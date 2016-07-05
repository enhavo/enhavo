<?php

namespace Enhavo\Bundle\SearchBundle\Util;
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 18.05.16
 * Time: 15:13
 */

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Yaml\Parser;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Doctrine\Common\Persistence\Proxy;

class SearchUtil
{
    protected $kernel;

    protected $mainPath;

    protected $container;

    protected $metadataFactory;

    protected $em;

    public function __construct($kernel, Container $container, MetadataFactory $metadataFactory, EntityManager $em)
    {
        $this->kernel = $kernel;
        $this->container = $container;
        $this->metadataFactory = $metadataFactory;
        $this->em = $em;
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

    public function getMainPath()
    {
        $this->getSearchYamls();
        return $this->mainPath; //Users/jhelbing/workspace/enhavo/src/
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

    public function getDataset($resource)
    {
        $metaData = $this->metadataFactory->create($resource);
        $entity = $metaData->getEntityName();
        $bundle = $metaData->getBundleName();
        $id = $resource->getId();
        $dataSet = $this->em->getRepository('EnhavoSearchBundle:Dataset')->findOneBy(array(
            'type' => strtolower($entity),
            'bundle' => $bundle,
            'reference' => $id
        ));
        if($dataSet == null){
            //create new dataset
            $dataSet = new Dataset();
            $dataSet->setType(strtolower($entity));
            $dataSet->setBundle($bundle);
            $dataSet->setReference($id);
            $dataSet->setReindex(1);
            $this->em->persist($dataSet);
            $this->em->flush();
        }
        return $dataSet;
    }
}