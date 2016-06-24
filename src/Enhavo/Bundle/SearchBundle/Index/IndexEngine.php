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

    public function __construct(Container $container, $kernel, EntityManager $em, $strategy, SearchUtil $util, FileService $fileService)
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->strategy = $strategy;
        $this->util = $util;
        $this->plainType = new PlainType($this->util, $this);
        $this->htmlType = new HtmlType($this->util, $this);
        $this->fileService = $fileService;
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
                $this->switchToIndexingType($text, $value, $dataSet);
            }
        }
        //update the total scores
        $this->searchUpdateTotals();
    }

    public function switchToIndexingType($text, $type, $dataSet)
    {
        //check what kind of indexing should happen with the text, that means check what type it has (plain, html, ...)
        if (is_array($type[0])) {
            foreach ($type[0] as $key => $value) {
                if ($key == 'Plain') {

                    //type Plain
                    $options = array(
                        'weight' => $value['weight'],
                        'minimumWordSize' => $this->minimumWordSize,
                        'accum' => $this->accum
                    );
                    list($scoredWords, $newAccum) = $this->plainType->index($text, $options);
                    $this->accum = $newAccum;
                    $this->addWordsToSearchIndex($scoredWords, $dataSet, $value['type']);
                } else if ($key == 'Html') {

                    //type Html
                    $options = array(
                        'minimumWordSize' => $this->minimumWordSize,
                        'accum' => $this->accum
                    );
                    if (array_key_exists('weights', $value)) {
                        if($text != null){
                            $options['weights'] = $value['weights'];
                        }
                    }
                    list($scoredWords, $newAccum) = $this->htmlType->index($text, $options);
                    $this->accum = $newAccum;
                    $this->addWordsToSearchIndex($scoredWords, $dataSet, $value['type']);
                } else if ($key == 'Collection') {

                    //type Collection
                    //get the right yaml file for collection
                    if (array_key_exists('entity', $value)) {
                        $bundlePath = null;
                        $splittedBundlePath = explode('\\', $value['entity']);
                        while (strpos(end($splittedBundlePath), 'Bundle') != true) {
                            array_pop($splittedBundlePath);
                        }
                        $bundlePath = implode('/', $splittedBundlePath);
                        $collectionPath = null;
                        foreach ($this->searchYamlPaths as $path) {
                            if (strpos($path, $bundlePath)) {
                                $collectionPath = $path;
                                break;
                            }
                        }
                        $yaml = new Parser();
                        if($collectionPath != null) {
                            $currentCollectionSearchYaml = $yaml->parse(file_get_contents($collectionPath));

                            if ($text != null) {
                                $collectionType = new CollectionType($this->util, $this);
                                $options = array(
                                    'model' => $value['entity'],
                                    'yaml' => $currentCollectionSearchYaml,
                                    'dataSet' => $dataSet
                                );
                                $collectionType->index($text,$options);
                            }
                        }
                    } else if (array_key_exists(0, $value)) {
                        foreach($text as $currentText){
                            if(key($value[0]) == 'Plain'){
                                $options = array(
                                    'weight' => $value[0]['Plain']['weight'],
                                    'minimumWordSize' => $this->minimumWordSize,
                                    'accum' => $this->accum
                                );
                                list($scoredWords, $newAccum) = $this->plainType->index($currentText, $options);
                                $this->accum = $newAccum;
                                $this->addWordsToSearchIndex($scoredWords, $dataSet, $value[0]['Plain']['type']);

                            } else if (key($value[0]) == 'Html'){
                                $options = array(
                                    'minimumWordSize' => $this->minimumWordSize,
                                    'accum' => $this->accum
                                );
                                if (array_key_exists('weights', $value[0]['Html'])) {
                                    $options['weights'] = $value[0]['Html']['weights'];
                                }
                                list($scoredWords, $newAccum) = $this->htmlType->index($currentText, $options);
                                $this->accum = $newAccum;
                                $this->addWordsToSearchIndex($scoredWords, $dataSet, $value[0]['Html']['type']);
                            }
                        }
                    }
                } else if($key == 'PDF'){
                    //get content of PDF
                    $pdfType = new PdfType($this->util, $this, $this->fileService);
                    $options = array(
                        'weight' => $value['weight'],
                        'minimumWordSize' => $this->minimumWordSize,
                        'dataSet' => $dataSet,
                        'type' => $value['type'],
                        'accum' => $this->accum
                    );
                    $pdfType->index($text, $options);
                }
            }
        } else if($type[0] == 'Model') {
            //Model
            $model = get_class($text);
            $splittedModelPath = explode('\\', $model);
            if($splittedModelPath[0] == 'Proxies')
            {
                array_shift($splittedModelPath);
                array_shift($splittedModelPath);
            }
            $model = implode('\\',$splittedModelPath);
            while (strpos(end($splittedModelPath), 'Bundle') != true) {
                array_pop($splittedModelPath);
            }

            $bundlePath = implode('/', $splittedModelPath);
            $modelPath = null;
            foreach ($this->searchYamlPaths as $path) {
                if (strpos($path, $bundlePath)) {
                    $modelPath = $path;
                    break;
                }
            }
            $yaml = new Parser();
            if($modelPath != null){
                $currentModelSearchYaml = $yaml->parse(file_get_contents($modelPath));
                if($text != null) {

                    $modelType = new ModelType($this->util, $this);
                    $options = array(
                        'model' => $model,
                        'yaml' => $currentModelSearchYaml,
                        'dataSet' => $dataSet
                    );
                    $modelType->index($text,$options);
                }
            }
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

    public function addWordsToSearchIndex($scoredWords, $dataset, $type, $accum = null)
    {
        //add the scored words to search_index
        foreach ($scoredWords as $key => $value) {
            $newIndex = new Index();
            $newIndex->setDataset($dataset);
            $newIndex->setType(strtolower($type));
            $newIndex->setWord($key);
            $newIndex->setLocale($this->container->getParameter('locale'));
            $newIndex->setScore($value);
            $this->em->persist($dataset);
            $this->em->persist($newIndex);
            $this->em->flush();
            $this->searchDirty($key);
        }
        if(!$accum == null){
            $this->accum = $accum;
        }
        $dataset->setData($this->accum);
        $dataset->setReindex(0);
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
}