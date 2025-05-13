<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Doctrine\ORM\EntityManager;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Mapping;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\ConstantScore;
use Elastica\Query\MatchPhrasePrefix;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Search;
use Enhavo\Bundle\AppBundle\Output\OutputLoggerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\BetweenQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\ContainsQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\MatchQuery;
use Enhavo\Bundle\SearchBundle\Engine\Result\EntitySubjectLoader;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultEntry;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Exception\FilterQueryNotSupportedException;
use Enhavo\Bundle\SearchBundle\Exception\IndexException;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataProvider;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Enhavo\Bundle\SearchBundle\Index\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Pagerfanta\Pagerfanta;

class ElasticSearchEngine implements SearchEngineInterface
{
    private readonly ?Client $client;
    private readonly ?string $indexName;

    public function __construct(
        private readonly IndexDataProvider $indexDataProvider,
        private readonly MetadataRepository $metadataRepository,
        private readonly EntityManager $em,
        private readonly ElasticSearchIndexRemover $indexRemover,
        private readonly ElasticSearchDocumentIdGenerator $documentIdGenerator,
        private readonly FilterDataProvider $filterDataProvider,
        private readonly EntityResolverInterface $entityResolver,
        private readonly array $classes,
        ClientFactory $clientFactory,
        $dsn,
        private readonly ?array $indexSettings,
        private readonly int $pageSize = 100,
    ) {
        if (!self::supports($dsn)) {
            return;
        }

        $this->indexName = $clientFactory->getIndexName($dsn);
        $this->client = $clientFactory->create($dsn);
    }

    public function initialize($force = false): void
    {
        $createIndexArguments = [];
        if (null !== $this->indexSettings) {
            $createIndexArguments['settings'] = $this->indexSettings;
        }

        $index = $this->getIndex();
        if (!$index->exists()) {
            $index->create($createIndexArguments);
        } elseif ($index->exists() && $force) {
            $index->delete();
            $index->create($createIndexArguments);
        }

        $mapping = new Mapping();

        $indexData = [];
        for ($i = 0; $i <= 100; ++$i) {
            $indexData['value.'.$i] = ['type' => 'text'];
        }

        $filterData = [];
        foreach ($this->classes as $class) {
            $fields = $this->filterDataProvider->getFields($class);

            foreach ($fields as $field) {
                $filterData[$field->getKey()] = [
                    'type' => $this->mapDataType($field->getFieldType()),
                ];
            }
        }

        $mapping->setProperties([
            'id' => ['type' => 'keyword'],
            'className' => ['type' => 'keyword'],
            'indexData' => [
                'type' => 'object',
                'properties' => $indexData,
            ],
            'filterData' => [
                'type' => 'object',
                'properties' => $filterData,
            ],
        ]);

        $mapping->send($this->getIndex());
    }

    private function mapDataType($type)
    {
        if (null == $type) {
            return 'keyword';
        } elseif ('string' === $type) {
            return 'keyword';
        } elseif ('bool' === $type) {
            return 'boolean';
        }

        return $type;
    }

    /**
     * @return Query
     */
    private function createQuery(Filter $filter)
    {
        $query = new BoolQuery();
        $this->applyTermQueries($filter, $query);
        $this->applyFilterQueries($filter, $query);

        return $this->createMasterQuery($filter, $query);
    }

    private function createSuggestQuery(Filter $filter)
    {
        $query = new BoolQuery();
        $this->applySuggestQueries($filter, $query);
        $this->applyFilterQueries($filter, $query);

        return $this->createMasterQuery($filter, $query);
    }

    private function applyTermQueries(Filter $filter, BoolQuery $query)
    {
        if ($filter->getTerm()) {
            for ($i = 1; $i <= 100; ++$i) {
                $fieldName = sprintf('indexData.value.%s', $i);

                $termQuery = new Query\MatchQuery($fieldName, [
                    'query' => $filter->getTerm(),
                ]);

                if ($filter->isFuzzy()) {
                    $termQuery->setFieldParam($fieldName, 'fuzziness', 2);
                }

                $constantScoreQuery = new ConstantScore($termQuery);
                $constantScoreQuery->setBoost($i);

                $query->addShould($constantScoreQuery);
            }
            $query->setMinimumShouldMatch(1);
        }
    }

    private function applySuggestQueries(Filter $filter, BoolQuery $query)
    {
        if ($filter->getTerm()) {
            for ($i = 1; $i <= 100; ++$i) {
                $fieldName = sprintf('indexData.value.%s', $i);

                $termQuery = new MatchPhrasePrefix($fieldName, [
                    'query' => $filter->getTerm(),
                ]);

                $constantScoreQuery = new ConstantScore($termQuery);
                $constantScoreQuery->setBoost($i);

                $query->addShould($constantScoreQuery);
            }
            $query->setMinimumShouldMatch(1);
        }
    }

    private function applyFilterQueries(Filter $filter, BoolQuery $query)
    {
        foreach ($filter->getQueries() as $key => $filterQuery) {
            $fieldName = sprintf('filterData.%s', $key);
            $query->addFilter($this->createFilterQuery($fieldName, $filterQuery));
        }

        if ($filter->getClass()) {
            $termQuery = new Term();
            $termQuery->setTerm('className', $filter->getClass());
            $query->addFilter($termQuery);
        }
    }

    private function createMasterQuery(Filter $filter, BoolQuery $query)
    {
        $masterQuery = new Query($query);

        if (null !== $filter->getLimit()) {
            $masterQuery->setSize(intval($filter->getLimit()));
        }
        if (null !== $filter->getOrderBy()) {
            $direction = $filter->getOrderDirection();
            if (empty($direction)) {
                $direction = 'ASC';
            }
            $masterQuery->addSort([sprintf('filterData.%s', $filter->getOrderBy()) => $direction]);
        }

        return $masterQuery;
    }

    private function createFilterQuery($fieldName, $filterQuery)
    {
        if ($filterQuery instanceof MatchQuery) {
            if (MatchQuery::OPERATOR_EQUAL == $filterQuery->getOperator()) {
                $query = new Term();
                $query->setTerm($fieldName, $filterQuery->getValue());

                return $query;
            } elseif (MatchQuery::OPERATOR_NOT == $filterQuery->getOperator()) {
                $query = new Term();
                $query->setTerm($fieldName, $filterQuery->getValue());
                $boolQuery = new BoolQuery();
                $boolQuery->addMustNot($query);

                return $query;
            }
            $operatorMap = [
                MatchQuery::OPERATOR_GREATER => 'gt',
                MatchQuery::OPERATOR_GREATER_EQUAL => 'gte',
                MatchQuery::OPERATOR_LESS => 'lt',
                MatchQuery::OPERATOR_LESS_EQUAL => 'lte',
            ];
            $query = new Range($fieldName, [$operatorMap[$filterQuery->getOperator()] => $filterQuery->getValue()]);

            return $query;
        } elseif ($filterQuery instanceof BetweenQuery) {
            $operatorFromMap = [
                BetweenQuery::OPERATOR_THAN => 'gt',
                BetweenQuery::OPERATOR_EQUAL_THAN => 'gte',
            ];
            $operatorToMap = [
                BetweenQuery::OPERATOR_THAN => 'lt',
                BetweenQuery::OPERATOR_EQUAL_THAN => 'lte',
            ];
            $query = new Range($fieldName, [
                $operatorFromMap[$filterQuery->getOperatorFrom()] => $filterQuery->getFrom(),
                $operatorToMap[$filterQuery->getOperatorTo()] => $filterQuery->getTo(),
            ]);

            return $query;
        } elseif ($filterQuery instanceof ContainsQuery) {
            $boolQuery = new BoolQuery();
            foreach ($filterQuery->getValues() as $value) {
                $term = new Term([$fieldName => $value]);
                $boolQuery->addShould($term);
            }

            return $boolQuery;
        }
        throw new FilterQueryNotSupportedException(sprintf('Filter query from type "%s" is not supported', get_class($filterQuery)));
    }

    public function search(Filter $filter): ResultSummary
    {
        $search = new Search($this->client);
        $search->addIndex($this->getIndex());
        $search->setQuery($this->createQuery($filter));

        $entries = $this->getSearchEntries($search);
        $summary = new ResultSummary($entries, count($entries));

        return $summary;
    }

    private function getSearchEntries(Search $search)
    {
        $entries = [];
        foreach ($search->search() as $document) {
            $data = $document->getData();
            $id = $data['id'];
            $className = $data['className'];
            $entries[] = new ResultEntry(new EntitySubjectLoader($this->entityResolver, $className, $id), $data['filterData'], $document->getScore());
        }

        return $entries;
    }

    public function count(Filter $filter): int
    {
        $search = new Search($this->client);
        $search->addIndex($this->getIndex());
        $search->setQuery($this->createQuery($filter));

        return $search->count();
    }

    public function suggest(Filter $filter): array
    {
        $filter->setFuzzy(false);
        $filter->setLimit(10);

        $search = new Search($this->client);
        $index = $this->getIndex();
        $search->addIndex($index);
        $search->setQuery($this->createSuggestQuery($filter));

        $canonicalTerm = strtolower($filter->getTerm());

        // build a map with all words and add their weights
        $suggestionMap = [];
        foreach ($search->search() as $document) {
            $data = $document->getData();
            foreach ($data['indexData'] as $key => $value) {
                $text = join(' ', $value);
                $weight = intval(substr($key, 6));

                $tokens = $index->analyze([
                    'analyzer' => 'standard',
                    'text' => $text,
                ]);

                foreach ($tokens as $token) {
                    $canonicalToken = strtolower($token['token']);
                    if (str_starts_with($canonicalToken, $canonicalTerm)) {
                        if (!isset($suggestionMap[$canonicalToken])) {
                            $suggestionMap[$canonicalToken] = [];
                        }
                        $suggestionMap[$canonicalToken][] = $weight;
                    }
                }
            }
        }

        $suggestionScore = [];
        foreach ($suggestionMap as $key => $value) {
            $max = max($value);
            $total = array_sum($value);
            $occur = count($value);

            // we want the maximum score to count as most important, but we have to rank the words against each other,
            // so we use the average weight, but give them less impact to the result
            $score = $max + ($total / $occur / 100);

            $suggestionScore[] = [
                'term' => $key,
                'score' => $score,
            ];
        }

        usort($suggestionScore, function ($a, $b) {
            return $b['score'] > $a['score'] ? 1 : -1;
        });

        $suggestions = [];
        foreach ($suggestionScore as $suggestion) {
            $suggestions[] = $filter->getTerm().substr($suggestion['term'], strlen($canonicalTerm));
        }

        return $suggestions;
    }

    public function searchPaginated(Filter $filter): ResultSummary
    {
        $search = new Search($this->client);
        $search->addIndex($this->getIndex());
        $search->setQuery($this->createQuery($filter));

        $pagerfanta = new Pagerfanta(new ElasticaORMAdapter($search, $this->entityResolver));

        return new ResultSummary($pagerfanta, $search->count());
    }

    public function index($resource): void
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        if ($metadata && in_array($metadata->getClassName(), $this->classes)) {
            $index = $this->getIndex();
            $document = $this->createDocument($resource);
            $index->addDocument($document);
            $index->refresh();
        }
    }

    private function getIndex(): Index
    {
        return $this->client->getIndex($this->indexName);
    }

    private function createDocument($resource): Document
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        $indexes = $this->indexDataProvider->getIndexData($resource);

        $id = $resource->getId();
        $className = $metadata->getClassName();
        $documentId = $this->documentIdGenerator->generateDocumentId($className, $id);

        $indexData = [];
        foreach ($indexes as $index) {
            $weight = intval($index->getWeight());
            $key = 'value.'.$weight;
            if (!array_key_exists($key, $indexData)) {
                $indexData[$key] = [];
            }
            $indexData[$key][] = $index->getValue();
        }

        $filterData = [];
        foreach ($this->filterDataProvider->getFilterData($resource) as $data) {
            $filterData[$data->getKey()] = $data->getValue();
        }

        $data = [
            'id' => $id,
            'className' => $className,
            'indexData' => $indexData,
            'filterData' => $filterData,
        ];

        return new Document($documentId, $data);
    }

    public function removeIndex($resource): void
    {
        $this->indexRemover->removeIndex($resource);
    }

    public function reindex(bool $force = false, ?string $class = null, ?OutputLoggerInterface $logger = null): void
    {
        // only delete index if we are going to reindex all
        if (null === $class) {
            $this->getIndex()->delete();
            $this->initialize();
        }

        if (null !== $class && !in_array($class, $this->classes)) {
            $logger?->error(sprintf('Class "%s" is not configured for indexing', $class));

            return;
        }

        foreach ($this->classes as $indexClass) {
            if (null !== $class && $class !== $indexClass) {
                continue;
            }

            $repository = $this->em->getRepository($indexClass);
            $amount = $repository->count([]);

            $logger?->info(sprintf('Indexing "%s" found "%s"', $indexClass, $amount));
            $logger?->progressStart($amount);

            $pages = ceil($amount / $this->pageSize);
            for ($page = 0; $page < $pages; ++$page) {
                $entities = $repository->findBy([], [], $this->pageSize, $page * $this->pageSize);
                foreach ($entities as $entity) {
                    try {
                        $logger?->progressAdvance(1);
                        $this->index($entity);
                    } catch (\Exception $e) {
                        $message = sprintf(
                            'Can\'t index class "%s" with id "%s". Error: %s',
                            get_class($entity),
                            $entity->getId(),
                            $e->getMessage()
                        );

                        $logger?->error($message);

                        if (!$force) {
                            throw new IndexException();
                        }
                    }
                    $this->em->detach($entity);
                    unset($entity);
                }
            }

            $logger?->progressFinish();
        }
    }

    public static function supports($dsn): bool
    {
        if (str_starts_with($dsn, 'elastic://')) {
            return true;
        }

        return false;
    }
}
