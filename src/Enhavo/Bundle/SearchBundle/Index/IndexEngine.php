<?php

namespace Enhavo\Bundle\SearchBundle\Index;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\MediaBundle\Service\FileService;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\SearchBundle\Entity\Dataset;
use Enhavo\Bundle\SearchBundle\Entity\Index;
use Enhavo\Bundle\SearchBundle\Entity\Total;
use Enhavo\Bundle\SearchBundle\Index\Type\PlainType;
use Enhavo\Bundle\SearchBundle\Index\Type\HtmlType;
use Enhavo\Bundle\SearchBundle\Index\Type\CollectionType;
use Enhavo\Bundle\SearchBundle\Index\Type\ModelType;
use Enhavo\Bundle\SearchBundle\Index\Type\PdfType;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Enhavo\Bundle\SearchBundle\Index\IndexWalker;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.01.16
 * Time: 16:00
 */

class IndexEngine implements IndexEngineInterface {

    protected $container;
    protected $kernel;
    protected $em;
    protected $searchYamlPaths;
    protected $minimumWordSize = 2;
    protected $strategy;

    /**
     * index every time
     */
    const INDEX_STRATEGY_INDEX = 'index';

    /**
     * index only if resource is new to index
     */
    const INDEX_STRATEGY_INDEX_NEW = 'index_new';

    /**
     * add reference but don't index, just mark it for reindexing
     */
    const INDEX_STRATEGY_REINDEX = 'reindex';

    /**
     * never index
     */
    const INDEX_STRATEGY_NOINDEX = 'noindex';

    protected $util;
    protected $plainType;
    protected $htmlType;
    protected $fileService;

    protected $accum; //accumulator
    protected $indexWalker;
    protected $metadataFactory;

    public function __construct(Container $container, $kernel, EntityManager $em, $strategy, SearchUtil $util, FileService $fileService, IndexWalker $indexWalker, MetadataFactory $metadataFactory)
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->strategy = $strategy;
        $this->util = $util;
        $this->plainType = new PlainType($this->util, $container);
        $this->htmlType = new HtmlType($this->util, $container);
        $this->fileService = $fileService;
        $this->indexWalker = $indexWalker;
        $this->metadataFactory = $metadataFactory;
    }

    public function index($resource)
    {
        if($this->strategy == self::INDEX_STRATEGY_NOINDEX) {
            return;
        }

        //get Entity and Bundle names
        $entityName = $this->util->getEntityName($resource);
        $bundleName = $this->util->getBundleName($resource);

        //get DataSet
        $dataSetRepository = $this->em->getRepository('EnhavoSearchBundle:Dataset');
        $dataSet = $dataSetRepository->findOneBy(array('reference' => $resource->getId(), 'type' => $entityName, 'bundle' => $bundleName));
        $newDataset = false;
        if($dataSet == null){

            //create a new dataset
            $dataSet = new Dataset();
            $dataSet->setType(strtolower($entityName));
            $dataSet->setBundle($bundleName);
            $dataSet->setReference($resource->getId());
            $dataSet->setData(null);
            $dataSet->setReindex(1);
            $this->em->persist($dataSet);
            $this->em->flush();
            $newDataset = true;
        }

        //check stategy
        if($this->strategy == self::INDEX_STRATEGY_INDEX){

            //indexing always
            //check if update or new indexing
            if(!$newDataset){

                //update indexing
                //remove old content of dataset
                $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
                $wordsForDataset = $indexRepository->findBy(array('dataset' => $dataSet));
                $dataSet->removeData();
                foreach($wordsForDataset as $word){
                    $this->em->remove($word);
                }
                //set reindex
                $dataSet->setReindex(1);
                $this->em->persist($dataSet);
                $this->em->flush();
            }

            $this->accum = " ";
            $this->indexingData($resource, $dataSet);
        } else if($this->strategy == self::INDEX_STRATEGY_INDEX_NEW){

            //indexing the first time otherwise set reindex
            if($newDataset){

                //indexing
                $this->accum = " ";
                $this->indexingData($resource, $dataSet);
            } else {

                //set reindex
                $dataSet->setReindex(1);
                $this->em->persist($dataSet);
            }


        } else if($this->strategy == self::INDEX_STRATEGY_REINDEX && !$newDataset){

            //set reindex
            $dataSet->setReindex(1);
            $this->em->persist($dataSet);
        }
        $this->em->flush();
    }

    public function reindex()
    {
        $this->searchYamlPaths = $this->util->getSearchYamls();

        //get all datasets to reindex -> this means new datasets and updated datasets
        $changedOrNewDatasets = $this->em->getRepository('EnhavoSearchBundle:Dataset')->findBy(array(
            'reindex' => 1
        ));

        //update all entries in search_index of these changed datasets
        foreach($changedOrNewDatasets as $currentDataset) {

            //get current search yaml for dataset
            $currentBundle = $currentDataset->getBundle();
            $splittedBundleName = preg_split('/(?=[A-Z])/', $currentBundle, -1, PREG_SPLIT_NO_EMPTY);
            $currentSearchYaml = null;
            foreach($this->searchYamlPaths as $path){
                $allPartsOfBundleNameInPath = true;
                foreach($splittedBundleName as $partOfBundleName){
                    if(!is_numeric(strpos($path, $partOfBundleName))){
                        $allPartsOfBundleNameInPath = false;
                    }
                }
                if($allPartsOfBundleNameInPath == true && is_numeric(strpos($path, 'Enhavo')) && !is_numeric(strpos($currentBundle, 'Enhavo'))){
                    $allPartsOfBundleNameInPath = false;
                }
                if($allPartsOfBundleNameInPath == true){
                    $yaml = new Parser();
                    $currentSearchYaml = $yaml->parse(file_get_contents($path));
                    break;
                }
            }
            if($currentSearchYaml != null) {

                //remove old content of dataset
                $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
                $wordsForDataset = $indexRepository->findBy(array('dataset' => $currentDataset));
                $currentDataset->removeData();
                foreach($wordsForDataset as $word){
                    $this->em->remove($word);
                }
                $this->em->flush();

                foreach($currentSearchYaml as $key => $value){

                    //get properties form search.yml
                    $array = explode('\\', $key);
                    $entityName = array_pop($array);
                    $resource = $this->em->getRepository($currentDataset->getBundle().':'.$entityName)->find($currentDataset->getReference());

                    $this->accum = " ";
                    $this->indexingData($resource, $currentDataset);
                }
            }
        }
    }

    public function unindex($resource)
    {
        //get the BundleName
        $array = explode('\\', get_class($resource));
        $bundle = null;
        foreach($array as $key => $value) {
            if(strpos($value, 'Bundle', 1)){
                $bundle = $value;
                if($array[$key-2] == 'Enhavo'){
                    $bundle = $array[$key-2].$bundle;
                }
                break;
            }
        }
        $entity = array_pop($array);

        //find the right dataset
        $datasetRepository = $this->em->getRepository('EnhavoSearchBundle:Dataset');
        $dataset = $datasetRepository->findOneBy(array(
            'bundle' => $bundle,
            'reference' => $resource->getId(),
            'type' => strtolower($entity)
        ));

        //find the belonging indexes
        $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
        $index = $indexRepository->findBy(array(
            'dataset' => $dataset
        ));

        //remove indexed
        foreach($index as $currentIndexToDelete) {
            $word = $currentIndexToDelete->getWord();
            $this->em->remove($currentIndexToDelete);
            $this->searchDirty($word);
        }

        //remove dataset
        $this->em->remove($dataset);
        $this->em->flush();
        $this->searchUpdateTotals();
    }

    protected function indexingData($resource, $dataSet)
    {
        $properties = $this->util->getProperties($resource);
        $this->searchYamlPaths = $this->util->getSearchYamls();

        //indexing words (go through all the fields that can be indexed according to the search yml)
        foreach($properties as $indexingField => $value) {

            //look if there is a field (indexingField) in the request (currentRequest) that can get indexed
            $accessor = PropertyAccess::createPropertyAccessor();
            if(property_exists($resource, $indexingField)) {
                $text = $accessor->getValue($resource, $indexingField);

            }
        }
        $metaData = $this->metadataFactory->create($resource);

        $indexItem = $this->indexWalker->getIndexItems($resource, $metaData);

        $this->addWordsToSearchIndex($indexItem, $resource);
        //update the total scores
        $this->searchUpdateTotals();
    }

    public function addWordsToSearchIndex($indexItem, $resource)//($scoredWords, $dataset, $type, $accum = null)
    {
        //dataset über resource -->function schreiben getDataset die dataset holt oder erzeugt
        $dataSet = $this->getDataset($resource);
        $dataSet->setData($indexItem->getData());
        //add the scored words to search_index
        foreach ($indexItem->getScoredWords() as $scoredWord) {
            //$wordData = array('word' => ..., 'locale' => ..., 'type' => ..., 'score' => ...)
            $newIndex = new Index();
            $newIndex->setDataset($dataSet);
            $newIndex->setType(strtolower($scoredWord['type']));
            $newIndex->setWord($scoredWord['word']);
            $newIndex->setLocale($scoredWord['locale']);
            $newIndex->setScore($scoredWord['score']);
            $this->em->persist($newIndex);
            $this->em->flush();
            $this->searchDirty($scoredWord['word']);
        }
        $dataSet->setReindex(0);
    }

    protected function getDataset($resource)
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
            $this->em->persist($dataSet);
            $this->em->flush();
        }
        return $dataSet;
    }

    /**
     * Marks a word as "dirty" (changed), or retrieves the list of dirty words.
     *
     * This is used during indexing (cron). Words that are dirty have outdated
     * total counts in the search_total table, and need to be recounted.
     */
    function searchDirty($word = NULL) {
        global $dirty;
        if ($word !== NULL) {
            $dirty[$word] = TRUE;
        }
        else {
            return $dirty;
        }
    }

    /**
     * Updates the score column in the search_total table
     */
    function searchUpdateTotals() {

        //get all of the saved words from seach_dirty
        if($this->searchDirty() != null) {
            foreach ($this->searchDirty() as $word => $dummy) {

                // Get total count for the word
                $searchIndexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
                $total = $searchIndexRepository->sumScoresOfWord($word);

                //get the total count: Normalization according Zipf's law --> the word's value to the search index is inversely proportionate to its overall frequency therein
                $total = log10(1 + 1 / (max(1, current($total))));

                //save new score
                $searchTotalRepository = $this->em->getRepository('EnhavoSearchBundle:Total');
                $currentWord = $searchTotalRepository->findBy(array('word' => $word));

                foreach ($currentWord as $cWord) {
                    //if the word is already stored in search_total -> remove it and store it with the new score
                    $this->em->remove($cWord);
                }

                $newTotal = new Total();
                $newTotal->setWord($word);
                $newTotal->setCount($total);
                $this->em->persist($newTotal);
                $this->em->flush();
            }

            //remove words that are removed vom search_index but are still in search_total
            $searchTotalRepository = $this->em->getRepository('EnhavoSearchBundle:Total');
            $wordsToRemove = $searchTotalRepository->getWordsToRemove();
            foreach ($wordsToRemove as $word) {
                $currentWordsToRemove = $searchTotalRepository->findBy(array('word' => $word['realword']));
                if ($currentWordsToRemove != null) {
                    foreach ($currentWordsToRemove as $currentWordToRemove) {
                        $this->em->remove($currentWordToRemove);
                        $this->em->flush();
                    }
                }
            }
        }
    }
}