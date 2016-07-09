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
use Enhavo\Bundle\SearchBundle\Entity\Dataset;

/*
 * This class has all the helper functions for the search bundle
 */
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
        //simplifies a text
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

    public function getContentOfSearchYaml($searchYamlPath)
    {
        //get the content of a given search yaml
        if (file_exists($searchYamlPath)) {
            $yaml = new Parser();
            return $yaml->parse(file_get_contents($searchYamlPath));
        } else {
            return null;
        }
    }

    public function getDataset($resource)
    {
        //get the dataset to a resource
        $metaData = $this->metadataFactory->create($resource);
        $entity = $metaData->getEntityName();
        $bundle = $metaData->getBundleName();
        $id = $resource->getId();
        $dataSet = $this->em->getRepository('EnhavoSearchBundle:Dataset')->findOneBy(array(
            'type' => strtolower($entity),
            'bundle' => $bundle,
            'reference' => $id
        ));

        //if there is no dataset create a new one
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