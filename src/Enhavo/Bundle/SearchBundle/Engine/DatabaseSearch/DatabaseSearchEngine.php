<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.08.18
 * Time: 01:55
 */

namespace Enhavo\Bundle\SearchBundle\Engine\DatabaseSearch;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Model\Database\DataSet;
use Enhavo\Bundle\SearchBundle\Model\Database\Index;
use Enhavo\Bundle\SearchBundle\Extractor\Extractor;
use Enhavo\Bundle\SearchBundle\Indexer\Indexer;
use Enhavo\Bundle\SearchBundle\Model\Database\Total;
use Enhavo\Bundle\SearchBundle\Repository\IndexRepository;
use Enhavo\Bundle\SearchBundle\Repository\TotalRepository;
use Enhavo\Bundle\SearchBundle\Util\TextSimplify;
use Enhavo\Bundle\SearchBundle\Util\TextToWord;
use Enhavo\Component\Metadata\MetadataRepository;
use Pagerfanta\Pagerfanta;

class DatabaseSearchEngine implements EngineInterface
{
    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * @var TextToWord
     */
    private $splitter;

    /**
     * @var TextSimplify
     */
    private $simplifier;

    /**
     * @var EntityResolverInterface
     */
    private $entityResolver;

    /**
     * @var FilterData
     */
    private $filterData;

    /**
     * @var string[]
     */
    private $classes;

    /** @var bool */
    private $indexing;

    public function __construct(
        Indexer $indexer,
        MetadataRepository $metadataRepository,
        EntityManagerInterface $em,
        Extractor $extractor,
        TextToWord $splitter,
        TextSimplify $simplifier,
        EntityResolverInterface $entityResolver,
        FilterData $filterData,
        $classes,
        $indexing
    ) {
        $this->indexer = $indexer;
        $this->metadataRepository = $metadataRepository;
        $this->em = $em;
        $this->extractor = $extractor;
        $this->splitter = $splitter;
        $this->simplifier = $simplifier;
        $this->entityResolver = $entityResolver;
        $this->filterData = $filterData;
        $this->classes = $classes;
        $this->indexing = $indexing;
    }

    public function search(Filter $filter)
    {
        $searchFilter = $this->createSearchFilter($filter);
        $repository = $this->em->getRepository(Index::class);

        $searchResults = $repository->getSearchResults($searchFilter);
        $result = [];
        foreach($searchResults as $searchResult) {
            $result[] = $this->entityResolver->getEntity($searchResult['id'], $searchResult['class']);
        }

        return $result;
    }

    public function searchPaginated(Filter $filter)
    {
        $searchFilter = $this->createSearchFilter($filter);
        $repository = $this->em->getRepository(Index::class);
        $searchQuery = $repository->createSearchQuery($searchFilter);
        return new Pagerfanta(new DatabaseSearchAdapter($searchQuery, $this->entityResolver));
    }

    private function createSearchFilter(Filter $filter)
    {
        $searchFilter = new SearchFilter();

        $words = $filter->getTerm();
        $words = $this->simplifier->simplify($words);
        $words = $this->splitter->split($words);
        $searchFilter->setWords($words);

        if($filter->getClass()) {
            $class = $this->entityResolver->getName($filter->getClass());
            $searchFilter->setContentClass($class);
        }

        $searchFilter->setQueries($filter->getQueries());
        $searchFilter->setOrderBy($filter->getOrderBy());
        $searchFilter->setOrderDirection($filter->getOrderDirection());
        $searchFilter->setLimit($filter->getLimit());

        return $searchFilter;
    }

    public function index($resource, $locale = null)
    {
        if (!$this->indexing) {
            return;
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        if($metadata && in_array($metadata->getClassName(), $this->classes)) {
            $dataSet = $this->findDataSetOrCreateNew($resource);
            $dataSet->resetIndex();
            $dataSet->resetFilter();
            $indexes = $this->indexer->getIndexes($resource);

            $dataSetIndexes = $this->createIndexes($indexes);
            foreach($dataSetIndexes as $index) {
                $dataSet->addIndex($index);
                $index->setLocale($locale);
            }

            $filters = $this->createFilters($resource);
            foreach($filters as $filter) {
                $dataSet->addFilter($filter);
            }

            $this->em->flush();
            $this->updateTotals($dataSet);
            $this->em->flush();
        }
    }

    /**
     * @param \Enhavo\Bundle\SearchBundle\Indexer\Index[] $indexes
     * @return Index[]
     */
    private function createIndexes(array $indexes)
    {
        $dataSetIndexes = [];
        foreach($indexes as $index) {
            $words = $index->getValue();
            $words = $this->simplifier->simplify($words);
            $words = $this->splitter->split($words);
            $indexes = [];
            foreach($words as $word) {
                if(!isset($dataSetIndexes[$word])) {
                    $searchIndex = new Index();
                    $dataSetIndexes[$word] = $searchIndex;
                } else {
                    $searchIndex = $dataSetIndexes[$word];
                }
                $searchIndex->setWord($word);
                $searchIndex->setWeight($index->getWeight());
                $indexes[] = $searchIndex;
            }
            $this->updateIndexes($indexes);
        }
        return $dataSetIndexes;
    }

    private function createFilters($resource)
    {
        $results = [];
        $filterData = $this->filterData->getData($resource);
        foreach($filterData as $data) {
            $filter = new \Enhavo\Bundle\SearchBundle\Model\Database\Filter();
            $filter->setKey($data->getKey());
            $filter->setValue($data->getValue());
            $results[] = $filter;
        }
        return $results;
    }

    /**
     * @param Index[] $indexes
     */
    private function updateIndexes(array &$indexes)
    {
        $focus = 1;
        $minimumWordSize = 3;
        $i = 0;
        foreach($indexes as $index) {
            if (is_numeric($index->getWord()) || strlen($index->getWord()) >= $minimumWordSize) {
                $i++;
                $index->setScore($index->getScore() + ($index->getWeight() * $focus));
                // Focus is a decaying value in terms of the amount of words up to this point.
                // From 100 words and more, it decays, to e.g. 0.5 at 500 words and 0.3 at 1000 words.
                $focus = min(1, .01 + 3.5 / (2 + $i * .015));
            }
        }
    }

    private function findDataSetOrCreateNew($resource)
    {
        $className = $this->entityResolver->getName($resource);
        $dataSet = $this->em->getRepository(DataSet::class)->findOneBy([
            'contentId' => $resource->getId(),
            'contentClass' => $className
        ]);

        if($dataSet === null) {
            $dataSet = new DataSet();
            $dataSet->setContent($resource);
            $this->em->persist($dataSet);
        }

        return $dataSet;
    }

    private function updateTotals(DataSet $dataSet)
    {
        /** @var IndexRepository $indexRepository */
        $indexRepository = $this->em->getRepository(Index::class);
        $words = [];
        /** @var Index $index */
        foreach($dataSet->getIndexes() as $index) {
            $sumScore = $indexRepository->getSumScoreOfWord($index->getWord());
            $count = log10(1 + 1 / (max(1, current($sumScore))));
            $words[$index->getWord()] = $count;
        }

        /** @var TotalRepository $totalRepository */
        $totalRepository = $this->em->getRepository(Total::class);
        /** @var Total[] $totalWords */
        $totalWords = $totalRepository->findWords(array_keys($words));
        foreach($totalWords as $totalWord) {
            if(isset($words[$totalWord->getWord()])) {
                $totalWord->setCount($words[$totalWord->getWord()]);
                unset($words[$totalWord->getWord()]);
            }
        }

        foreach($words as $word => $count) {
            $totalWord = new Total();
            $totalWord->setWord($word);
            $totalWord->setCount($count);
            $this->em->persist($totalWord);
        }

        $totalWords = $totalRepository->findWordsToRemove();
        foreach($totalWords as $totalWord) {
            $this->em->remove($totalWord);
        }
    }

    public function removeIndex($resource)
    {
        $className = $this->entityResolver->getName($resource);
        $dataSet = $this->em->getRepository(DataSet::class)->findOneBy([
            'contentId' => $resource->getId(),
            'contentClass' => $className
        ]);

        if($dataSet) {
            $this->em->remove($dataSet);
            $this->em->flush();
        }
    }

    public function reindex()
    {
        foreach ($this->classes as $class) {
            $repository = $this->em->getRepository($class);
            $entities = $repository->findAll();
            foreach($entities as $entity) {
                $this->index($entity);
            }
        }
    }

    public function initialize()
    {
        // nothing to do here
    }
}
