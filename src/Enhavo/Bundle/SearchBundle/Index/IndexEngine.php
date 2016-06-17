<?php

namespace Enhavo\Bundle\SearchBundle\Index;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\SearchBundle\Entity\Dataset;
use Enhavo\Bundle\SearchBundle\Entity\Index;
use Enhavo\Bundle\SearchBundle\Entity\Total;
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

    public function __construct(Container $container, $kernel, EntityManager $em, $strategy, SearchUtil $util)
    {
        $this->container = $container;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->strategy = $strategy;
        $this->util = $util;
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

            $this->indexingData($resource, $dataSet);
        } else if($this->strategy == self::INDEX_STRATEGY_INDEX_NEW){

            //indexing the first time otherwise set reindex
            if($newDataset){

                //indexing
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
                    $this->indexingPlain($text, $value['weight'], $value['type'], $dataSet);
                } else if ($key == 'Html') {

                    //type Html
                    if (array_key_exists('weights', $value)) {
                        if($text != null){
                            $this->indexingHtml($text, $value['type'], $dataSet, $value['weights']);
                        }
                    } else {
                        if($text != null) {
                            $this->indexingHtml($text, $value['type'], $dataSet);
                        }
                    }
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
                                $this->indexingCollectionEntity($text, $value['entity'], $currentCollectionSearchYaml, $dataSet);
                            }
                        }
                    } else if (array_key_exists(0, $value)) {
                        foreach($text as $currentText){
                            if(key($value[0]) == 'Plain'){
                                $this->indexingPlain($currentText, $value[0]['Plain']['weight'],$value[0]['Plain']['type'], $dataSet);

                            } else if (key($value[0]) == 'Html'){
                                $this->indexingHtml($currentText, $value[0]['Html']['type'], $dataSet, $value[0]['Html']['weight']);
                            }
                        }
                    }

                }
            }
        } else {
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
                    $this->indexingModel($model, $currentModelSearchYaml, $dataSet, $text);
                }
            }
        }
    }

    public function indexingPlain($text, $score, $type, $dataset) {

        //indexing plain text and save in DB
        //get seperated words
        $words = $this->searchIndexSplit($text);
        $scoredWords = array();

        //set focus to 1 at the beginning
        $focus = 1;

        //get the right score for every word
        foreach($words as $word) {
            if (is_numeric($word) || strlen($word) >= $this->minimumWordSize) {

                //check if the word is already in the list of scored words
                if (!isset($scoredWords[$word])) {
                    $scoredWords[$word] = 0;
                }

                //add score (this means if a word is already in the list of scoresWords we just add the score multiplied with the focus)
                $scoredWords[$word] += $score * $focus;

                //the focus is getting less if a word is at the end of a long text and so the next score gets less
                $focus = min(1, .01 + 3.5 / (2 + count($scoredWords) * .015));
            }
        }

        $this->addWordsToSearchIndex($scoredWords, $dataset, $type);
    }

    public function indexingHtml($text, $type, $dataset, $weights = null) {

        //indexing html text and save in DB
        //get weights of words
        $tagYaml = $this->util->getMainPath().'/Enhavo/Bundle/SearchBundle/Resources/config/tag_weights.yml';
        $yaml = new Parser();
        $tags = $yaml->parse(file_get_contents($tagYaml));
        if($weights != null) //set given weights to default weights
        {
            foreach ($weights as $key => $value) {
                if(array_key_exists($key, $tags)) {
                    $tags[$key] = $value;
                } else {
                    $tags[$key] = $value;
                }
            }
        }

        // Strip off all ignored tags, insert space before and after them to keep word boundaries.
        $text = str_replace(array('<', '>'), array(' <', '> '), $text);
        $text = strip_tags($text, '<' . implode('><', array_keys($tags)) . '>');

        // Split HTML tags from plain text.
        $split = preg_split('/\s*<([^>]+?)>\s*/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        $tag = FALSE; // Odd/even counter. Tag or no tag.
        $score = 1; // Starting score per word
        $accum = ' '; // Accumulator for cleaned up data
        $tagstack = array(); // Stack with open tags
        $tagwords = 0; // Counter for consecutive words
        $focus = 1; // Focus state
        $scoredWords = array();

        //go trough the array of text and tags
        foreach ($split as $value) {

            //if tag is true we are handling the tags in the array, if tag is false we are handling text between the tags
            if ($tag) {
                // Increase or decrease score per word based on tag
                list($tagname) = explode(' ', $value, 2);
                $tagname = strtolower($tagname);
                // Closing or opening tag?
                if ($tagname[0] == '/') {
                    $tagname = substr($tagname, 1);
                    // If we encounter unexpected tags, reset score to avoid incorrect boosting.
                    if (!count($tagstack) || $tagstack[0] != $tagname) {
                        $tagstack = array();
                        $score = 1;
                    }
                    else {
                        // Remove from tag stack and decrement score
                        $score = max(1, $score - $tags[array_shift($tagstack)]);
                    }
                }
                else {
                    if (isset($tagstack[0]) && $tagstack[0] == $tagname) {
                        // None of the tags we look for make sense when nested identically.
                        // If they are, it's probably broken HTML.
                        $tagstack = array();
                        $score = 1;
                    }
                    else {
                        // Add to open tag stack and increment score
                        array_unshift($tagstack, $tagname);
                        $score += $tags[$tagname];
                    }
                }
                // A tag change occurred, reset counter.
                $tagwords = 0;
            }
            else {
                // Note: use of PREG_SPLIT_DELIM_CAPTURE above will introduce empty values
                if ($value != '') {
                    $words = $this->searchIndexSplit($value);
                    foreach ($words as $word) {
                        if($word != "") {
                            // Add word to accumulator
                            $accum .= $word . ' ';
                            // Check wordlength
                            if (is_numeric($word) || strlen($word) >= $this->minimumWordSize) {
                                if (!isset($scoredWords[$word])) {
                                    $scoredWords[$word] = 0;
                                }
                                $scoredWords[$word] += $score * $focus;
                                // Focus is a decaying value in terms of the amount of unique words up to this point.
                                // From 100 words and more, it decays, to e.g. 0.5 at 500 words and 0.3 at 1000 words.
                                $focus = min(1, .01 + 3.5 / (2 + count($scoredWords) * .015));
                            }
                            $tagwords++;
                            // Too many words inside a single tag probably mean a tag was accidentally left open.
                            if (count($tagstack) && $tagwords >= 15) {
                                $tagstack = array();
                                $score = 1;
                            }
                        }
                    }
                }
            }
            $tag = !$tag;
        }

        $this->addWordsToSearchIndex($scoredWords, $dataset, $type);
    }

    public function indexingCollectionEntity($text, $model, $yamlFile, $dataSet) {
        if(array_key_exists($model, $yamlFile)){
            $colProperties = $yamlFile[$model]['properties'];
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($text as $singleText){
                foreach($colProperties as $key => $value){
                    $this->switchToIndexingType($accessor->getValue($singleText, $key), $value, $dataSet);
                }
            }
        }
    }

    public function indexingModel($model, $yamlFile, $dataSet, $text) {
        if(array_key_exists($model, $yamlFile)){
            $colProperties = $yamlFile[$model]['properties'];
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($colProperties as $key => $value){
                $currentText = $accessor->getValue($text, $key);
                $this->switchToIndexingType($currentText, $value, $dataSet);
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

    protected function addWordsToSearchIndex($scoredWords, $dataset, $type)
    {
        //add the scored words to search_index
        foreach ($scoredWords as $key => $value) {
            $newIndex = new Index();
            $newIndex->setDataset($dataset);
            $newIndex->setType(strtolower($type));
            $newIndex->setWord($key);
            $newIndex->setLocale($this->container->getParameter('locale'));
            $newIndex->setScore($value);
            $dataset->addData($key);
            $dataset->setReindex(0);
            $this->em->persist($dataset);
            $this->em->persist($newIndex);
            $this->em->flush();
            $this->searchDirty($key);
        }
    }    /**
     * Simplifies and splits a string into words for indexing
     */
    function searchIndexSplit($text) {
        $text = $this->util->searchSimplify($text);
        $words = explode(' ', $text);
        return $words;
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