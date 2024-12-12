<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 22:07
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Doctrine\ORM\EntityManager;
use Elastica\Client;
use Elastica\Index;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\BetweenQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\ContainsQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\MatchQuery;
use Enhavo\Bundle\SearchBundle\Engine\Result\EntitySubjectLoader;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultEntry;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;
use Enhavo\Bundle\SearchBundle\Exception\FilterQueryNotSupportedException;
use Enhavo\Bundle\SearchBundle\Exception\IndexException;
use Enhavo\Bundle\SearchBundle\Filter\FilterDataProvider;
use Pagerfanta\Pagerfanta;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Elastica\Document;
use Elastica\Search;
use Elastica\Query;
use Elastica\Mapping;

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
        private readonly ?array $indexSettings
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
        if ($this->indexSettings !== null) {
            $createIndexArguments['settings'] = $this->indexSettings;
        }

        $index = $this->getIndex();
        if (!$index->exists()) {
            $index->create($createIndexArguments);
        } else if ($index->exists() && $force) {
            $index->delete();
            $index->create($createIndexArguments);
        }

        $mapping = new Mapping();

        $indexData = [];
        for ($i = 0; $i <= 100; $i++) {
            $indexData['value.' . $i] = ['type' => 'text'];
        }

        $filterData = [];
        foreach ($this->classes as $class) {
            /** @var Metadata $metadata */

            $fields = $this->filterDataProvider->getFields($class);

            foreach ($fields as $field) {
                $filterData[$field->getKey()] = [
                    'type' => $this->mapDataType($field->getFieldType())
                ];
            }
        }

        $mapping->setProperties([
            'id' => ['type' => 'keyword'],
            'className' => ['type' => 'keyword'],
            'indexData' => [
                'type' => 'object',
                'properties' => $indexData
            ],
            'filterData' => [
                'type' => 'object',
                'properties' => $filterData
            ],
        ]);

        $mapping->send($this->getIndex());
    }

    private function mapDataType($type)
    {
        if ($type == null) {
            return 'keyword';
        } else if ($type === 'string') {
            return 'keyword';
        } else if ($type === 'bool') {
            return 'boolean';
        }

        return $type;
    }

    /**
     * @param Filter $filter
     * @return Query
     */
    private function createQuery(Filter $filter)
    {
        $query = new Query\BoolQuery();
        $this->applyTermQueries($filter, $query);
        $this->applyFilterQueries($filter, $query);
        return $this->createMasterQuery($filter, $query);
    }

    private function createSuggestQuery(Filter $filter)
    {
        $query = new Query\BoolQuery();
        $this->applySuggestQueries($filter, $query);
        $this->applyFilterQueries($filter, $query);
        return $this->createMasterQuery($filter, $query);
    }

    private function applyTermQueries(Filter $filter, Query\BoolQuery $query)
    {
        if ($filter->getTerm()) {
            for ($i = 1; $i <= 100; $i++) {
                $fieldName = sprintf('indexData.value.%s', $i);

                $termQuery = new Query\MatchQuery($fieldName, [
                    'query' => $filter->getTerm(),
                ]);

                if ($filter->isFuzzy()) {
                    $termQuery->setFieldParam($fieldName, 'fuzziness', 2);
                }

                $constantScoreQuery = new Query\ConstantScore($termQuery);
                $constantScoreQuery->setBoost($i);

                $query->addShould($constantScoreQuery);
            }
            $query->setMinimumShouldMatch(1);
        }
    }

    private function applySuggestQueries(Filter $filter, Query\BoolQuery $query)
    {
        if ($filter->getTerm()) {
            for ($i = 1; $i <= 100; $i++) {
                $fieldName = sprintf('indexData.value.%s', $i);

                $termQuery = new Query\MatchPhrasePrefix($fieldName, [
                    'query' => $filter->getTerm(),
                ]);

                $constantScoreQuery = new Query\ConstantScore($termQuery);
                $constantScoreQuery->setBoost($i);

                $query->addShould($constantScoreQuery);
            }
            $query->setMinimumShouldMatch(1);
        }
    }

    private function applyFilterQueries(Filter $filter, Query\BoolQuery $query)
    {
        foreach ($filter->getQueries() as $key => $filterQuery) {
            $fieldName = sprintf('filterData.%s', $key);
            $query->addFilter($this->createFilterQuery($fieldName, $filterQuery));
        }

        if ($filter->getClass()) {
            $termQuery = new Query\Term();
            $termQuery->setTerm('className', $filter->getClass());
            $query->addFilter($termQuery);
        }
    }

    private function createMasterQuery(Filter $filter, Query\BoolQuery $query)
    {
        $masterQuery = new Query($query);

        if ($filter->getLimit() !== null) {
            $masterQuery->setSize(intval($filter->getLimit()));
        }
        if ($filter->getOrderBy() !== null) {
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
            if ($filterQuery->getOperator() == MatchQuery::OPERATOR_EQUAL) {
                $query = new Query\Term();
                $query->setTerm($fieldName, $filterQuery->getValue());
                return $query;
            } elseif ($filterQuery->getOperator() == MatchQuery::OPERATOR_NOT) {
                $query = new Query\Term();
                $query->setTerm($fieldName, $filterQuery->getValue());
                $boolQuery = new Query\BoolQuery();
                $boolQuery->addMustNot($query);
                return $query;
            } else {
                $operatorMap = [
                    MatchQuery::OPERATOR_GREATER => 'gt',
                    MatchQuery::OPERATOR_GREATER_EQUAL => 'gte',
                    MatchQuery::OPERATOR_LESS => 'lt',
                    MatchQuery::OPERATOR_LESS_EQUAL => 'lte',
                ];
                $query = new Query\Range($fieldName, [$operatorMap[$filterQuery->getOperator()] => $filterQuery->getValue()]);
                return $query;
            }
        } elseif ($filterQuery instanceof BetweenQuery) {
            $operatorFromMap = [
                BetweenQuery::OPERATOR_THAN => 'gt',
                BetweenQuery::OPERATOR_EQUAL_THAN => 'gte',
            ];
            $operatorToMap = [
                BetweenQuery::OPERATOR_THAN => 'lt',
                BetweenQuery::OPERATOR_EQUAL_THAN => 'lte',
            ];
            $query = new Query\Range($fieldName, [
                $operatorFromMap[$filterQuery->getOperatorFrom()] => $filterQuery->getFrom(),
                $operatorToMap[$filterQuery->getOperatorTo()] => $filterQuery->getTo()
            ]);
            return $query;
        } elseif ($filterQuery instanceof ContainsQuery) {
            $boolQuery = new Query\BoolQuery();
            foreach ($filterQuery->getValues() as $value) {
                $term = new Query\Term([$fieldName => $value]);
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
                    "analyzer" => "standard",
                    "text" => $text
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
                'score' => $score
            ];
        }

        usort($suggestionScore, function ($a, $b) {
            return $b['score'] > $a['score'] ? 1 : -1;
        });

        $suggestions = [];
        foreach ($suggestionScore as $suggestion) {
            $suggestions[] = $filter->getTerm() . substr($suggestion['term'], strlen($canonicalTerm));
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

    public function index($resource)
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

    /**
     * @return Index
     */
    private function getIndex()
    {
        $index = $this->client->getIndex($this->indexName);
        return $index;
    }

    /**
     * @param $resource
     * @return Document
     */
    private function createDocument($resource)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        $indexes = $this->indexDataProvider->getIndexData($resource);

        $id = $resource->getId();
        $className = $metadata->getClassName();
        $documentId = $this->documentIdGenerator->generateDocumentId($className, $id);

        $indexData = [];
        foreach($indexes as $index) {
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
            'filterData' => $filterData
        ];

        $document = new Document($documentId, $data);
        return $document;
    }

    public function removeIndex($resource)
    {
        $this->indexRemover->removeIndex($resource);
    }

    public function reindex()
    {
        $this->getIndex()->delete();
        $this->initialize();
        foreach ($this->classes as $class) {
            $repository = $this->em->getRepository($class);
            $entities = $repository->findAll();
            foreach ($entities as $entity) {
                try {
                    $this->index($entity);
                } catch (\Exception $e) {
                    throw new IndexException(sprintf(
                        'Can\'t index class "%s" with id "%s". Error: %s',
                        get_class($entity),
                        $entity->getId(),
                        $e->getMessage()
                    ));
                }
            }
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
