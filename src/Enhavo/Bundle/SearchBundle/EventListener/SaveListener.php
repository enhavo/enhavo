<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\SearchBundle\Entity\Dataset;
use Enhavo\Bundle\SearchBundle\Entity\Index;
use Symfony\Component\Yaml\Parser;
use Doctrine\ORM\EntityManager;

class SaveListener
{
    protected $em;
    protected $entityPath;
    protected $entityName;
    protected $bundleName;
    protected $kernel;
    protected $searchYamlPaths;

    public function __construct(EntityManager $em, $kernel)
    {
        $this->em = $em;
        $this->kernel = $kernel;
    }

    public function onSave($event)
    {
        //get the current search.yml for the entity
        $currentSearchYaml = $this->getCurrentSearchYaml(get_class($event->getSubject()));

        if($currentSearchYaml != null) {

            //get or create DataSet
            $dataSetRepository = $this->em->getRepository('EnhavoSearchBundle:Dataset');
            $dataSet = $dataSetRepository->findOneBy(array('reference' => $event->getSubject()->getId(), 'type' => $this->entityName));
            if($dataSet == null) {

                //create a new dataset
                $newDataSet = new Dataset();
                $newDataSet->setType(strtolower($this->entityName));
                $newDataSet->setBundle($this->bundleName);
                $newDataSet->setReindex(1);
                $newDataSet->setReference($event->getSubject()->getId());
                $newDataSet->setData(null);
                $this->em->persist($newDataSet);
                $this->em->flush();
            } else {

                //a dataset already exist
                $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
                $wordsForDataset = $indexRepository->findBy(array('dataset' => $dataSet));
                $dataSet->removeData();
                foreach($wordsForDataset as $word){
                    $this->em->remove($word);
                }
                $dataSet->setReindex(1);
                $this->em->persist($dataSet);
                $this->em->flush();
            }
        }
    }

    function getCurrentSearchYaml($class) {
        //get all bundles
        $bundles = $this->kernel->getBundles();
        //get all search.pml paths
        $this->searchYamlPaths = array();
        foreach($bundles as $bundle){
            if(file_exists($bundle->getPath().'/Resources/config/search.yml')){
                $this->searchYamlPaths[] = $this->kernel->locateResource('@'.$bundle->getName().'/Resources/config/search.yml');

            } else if($bundle->getName() == 'EnhavoSearchBundle'){
                $searchBundlePath = $bundle->getPath();
                $splittedPath = explode('/', $searchBundlePath);
                while(end($splittedPath) != 'src'){
                    array_pop($splittedPath);
                }
                $this->mainPath = implode('/', $splittedPath);
            }
        }


        $this->entityPath = $class;
        $splittedBundlePath = explode('\\', $this->entityPath);
        while(strpos(end($splittedBundlePath), 'Bundle') != true){
            array_pop($splittedBundlePath);
        }
        $this->bundleName = $splittedBundlePath[0].end($splittedBundlePath);
        $entityName = str_replace('Bundle', '', end($splittedBundlePath));
        $this->entityName = strtolower($entityName);
        $bundlePath = implode('/', $splittedBundlePath);
        $currentPath = null;
        foreach($this->searchYamlPaths as $path){
            if(strpos($path, $bundlePath)){
                $currentPath = $path;
                break;
            }
        }
        $searchYamlPath = $currentPath;
        if(file_exists($searchYamlPath)){
            $yaml = new Parser();
            return $yaml->parse(file_get_contents($searchYamlPath));
        } else {
            return null;
        }
    }

}