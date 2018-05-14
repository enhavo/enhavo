<?php

namespace Enhavo\Bundle\SearchBundle\Index;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Entity\Dataset;
use Enhavo\Bundle\SearchBundle\Entity\Index;
use Enhavo\Bundle\SearchBundle\Entity\Total;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.01.16
 * Time: 16:00
 * Does the indexing
 */

class IndexEngine implements IndexEngineInterface
{
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

    /**
     * @var IndexWalker
     */
    protected $indexWalker;

    /**
     * @var MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $strategy;

    /**
     * @var SearchUtil
     */
    protected $util;

    public function __construct(EntityManager $em, $strategy, IndexWalker $indexWalker, MetadataFactory $metadataFactory, SearchUtil $util)
    {
        $this->em = $em;
        $this->strategy = $strategy;
        $this->indexWalker = $indexWalker;
        $this->metadataFactory = $metadataFactory;
        $this->util = $util;
    }

    public function index($resource)
    {
        //just return nothing when indexing is off
        if($this->strategy == self::INDEX_STRATEGY_NOINDEX) {
            return;
        }

        $metadata = $this->metadataFactory->create($resource);
        if($metadata == null) {
            return;
        }

        //get DataSet
        $dataSet = $this->util->getDataset($resource);

        //get words in search_index of dataset
        $wordsForDataset = $this->getIndexedWords($dataSet);

        //check stategy
        if($this->strategy == self::INDEX_STRATEGY_INDEX){

            //indexing always
            //check if update or new indexing
            if($wordsForDataset){

                //update indexing
                //remove old content of dataset
                $dataSet->removeData();
                foreach($wordsForDataset as $word){
                    $this->em->remove($word);
                }
                //set reindex
                $dataSet->setReindex(1);
                $this->em->persist($dataSet);
                $this->em->flush();
            }
            $this->indexingData($resource);
        } else if($this->strategy == self::INDEX_STRATEGY_INDEX_NEW) {

            //indexing the first time otherwise set reindex
            if(!$wordsForDataset){

                //indexing
                $this->indexingData($resource);
            } else {

                //set reindex
                $dataSet->setReindex(1);
                $this->em->persist($dataSet);
            }
        } else if($this->strategy == self::INDEX_STRATEGY_REINDEX) {

            //set reindex
            $dataSet->setReindex(1);
            $this->em->persist($dataSet);
        }
        $this->em->flush();
    }

    public function reindex()
    {
        //get all datasets to reindex -> this means new datasets and updated datasets
        $changedOrNewDatasets = $this->em->getRepository('EnhavoSearchBundle:Dataset')->findBy(array(
            'reindex' => 1
        ));

        //update all entries in search_index of these changed datasets
        foreach($changedOrNewDatasets as $currentDataset) {
            $resource = $this->getResource($currentDataset);

            //remove old content of dataset
            $wordsForDataset = $this->getIndexedWords($currentDataset);
            $currentDataset->removeData();
            foreach($wordsForDataset as $word){
                $this->em->remove($word);
            }

            if($resource === null) {
                $this->em->remove($currentDataset);
                continue;
            }

            $this->em->flush();

            //index new resource
            $this->indexingData($resource);
        }
    }

    public function unindex($resource)
    {
        //find the right dataset
        $dataset = $this->util->getDataset($resource);

        if($dataset){
            //find the belonging indexes
            $index = $this->getIndexedWords($dataset);

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
    }

    protected function indexingData($resource)
    {
        //get metadata
        $metaData = $this->metadataFactory->create($resource);

        //get items to index
        $indexItems = $this->indexWalker->getIndexItems($resource, $metaData);

        //index words
        $this->addWordsToSearchIndex($indexItems, $resource);

        //update the total scores
        $this->searchUpdateTotals();
    }

    public function addWordsToSearchIndex($indexItems, $resource)
    {
        //get dataset of resource
        $dataSet = $this->util->getDataset($resource);
        $dataSet->setRawdata('');

        //set rawData, data and scoredWords
        foreach($indexItems as $indexItem){

            //collect all raw data for the dataset
            if($dataSet->getRawdata() == '' || $dataSet->getRawdata() == null){
                $dataSet->setRawdata($indexItem->getRawData());
            } else {
                $dataSet->setRawData($dataSet->getRawData()."\n ".$indexItem->getRawData());
                $this->em->flush();
            }

            //collect all data for the dataset
            if($dataSet->getData() == '' || $dataSet->getData() == null){
                $dataSet->setData($indexItem->getData());
            } else {
                $dataSet->setData($dataSet->getData().$indexItem->getData());
                $this->em->flush();
            }

            //write into search_index
            foreach ($indexItem->getScoredWords() as $scoredWord) {
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
        }

        //set data in dataset
        $dataSet->setData($dataSet->getData().' ');

        //set reindex ot 0 in dataset
        $dataSet->setReindex(0);
    }

    protected function getResource(Dataset $dataset)
    {
        //get ressource of dataset
        return $this->em->getRepository($dataset->getBundle().':'.ucfirst($dataset->getType()))->find($dataset->getReference());
    }

    protected function getIndexedWords($dataset)
    {
        //get the indexed words of a given dataset
        $indexRepository = $this->em->getRepository('EnhavoSearchBundle:Index');
        return $indexRepository->findBy(array(
            'dataset' => $dataset
        ));
    }

    /**
     * Marks a word as "dirty" (changed), or retrieves the list of dirty words.
     *
     * This is used during indexing (cron). Words that are dirty have outdated
     * total counts in the search_total table, and need to be recounted.
     *
     * This is the same as in drupal
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
     * The functionality is similar to drupal
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