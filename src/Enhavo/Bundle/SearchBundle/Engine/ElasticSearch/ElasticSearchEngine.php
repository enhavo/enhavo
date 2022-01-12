<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 22:07
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Engine\Filter\BetweenQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\ContainsQuery;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\Filter\MatchQuery;
use Enhavo\Bundle\SearchBundle\Exception\FilterQueryNotSupportedException;
use Enhavo\Bundle\SearchBundle\Exception\IndexException;
use Enhavo\Bundle\SearchBundle\Filter\FilterData;
use Enhavo\Bundle\SearchBundle\Metadata\Filter as MetadataFilter;
use Pagerfanta\Pagerfanta;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Extractor\Extractor;
use Enhavo\Bundle\SearchBundle\Indexer\Indexer;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Elastica\Client;
use Elastica\Document;
use Elastica\Search;
use Elastica\Query;
use Elastica\Type\Mapping;

class ElasticSearchEngine implements EngineInterface
{
    const ELASTIC_SEARCH_INDEX = 'elastic_search';
    const ELASTIC_SEARCH_TYPE = 'document';

    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Extractor
     */
    private $extractor;

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
        EntityManager $em,
        Client $client,
        Extractor $extractor,
        FilterData $filterData,
        $classes,
        $indexing
    ) {
        $this->indexer = $indexer;
        $this->metadataRepository = $metadataRepository;
        $this->em = $em;
        $this->client = $client;
        $this->extractor = $extractor;
        $this->filterData = $filterData;
        $this->classes = $classes;
        $this->indexing = $indexing;
    }

    public function initialize()
    {
        $index = $this->getIndex();
        if(!$index->exists()) {
            $index->create();
        }

        $mapping = new Mapping();
        $mapping->setType($this->getType());

        $indexData = [];
        for ($i = 0; $i <= 100; $i++) {
            $indexData['value.' . $i] = array('type' => 'text', 'boost' => $i);
        }

        $filterData = [];
        foreach($this->classes as $class) {
            /** @var Metadata $metadata */
            $metadata = $this->metadataRepository->getMetadata($class);
            $filters = $metadata->getFilters();

            /** @var MetadataFilter $filter */
            foreach($filters as $filter) {
                $filterData[$filter->getKey()] = [
                    'type' => $this->mapDataType($filter->getDataType())
                ];
            }
        }

        $mapping->setProperties(array(
            'id' => array('type' => 'keyword'),
            'className' => array('type' => 'keyword'),
            'indexData' => array(
                'type' => 'object',
                'properties' => $indexData
            ),
            'filterData' => array(
                'type' => 'object',
                'properties' => $filterData
            ),
        ));

        $mapping->send();
    }

    private function mapDataType($type)
    {
        if($type == null) {
            return 'keyword';
        }

        return $type;
    }

    private function getType()
    {
        $type = $this->getIndex()->getType(self::ELASTIC_SEARCH_TYPE);
        return $type;
    }

    /**
     * @param Filter $filter
     * @return Query
     */
    private function createQuery(Filter $filter)
    {
        $query = new Query\BoolQuery();

        if($filter->getTerm()) {
            for($i = 1; $i <= 100; $i++) {
                $query->addShould(new Query\Match(sprintf('indexData.value.%s', $i), $filter->getTerm()));
            }
            $query->setMinimumShouldMatch(1);
        }

        foreach($filter->getQueries() as $key => $filterQuery) {
            $fieldName = sprintf('filterData.%s', $key);
            $query->addFilter($this->createFilterQuery($fieldName, $filterQuery));
        }

        if($filter->getClass()) {
            $termQuery = new Query\Term();
            $termQuery->setTerm('className', $filter->getClass());
            $query->addFilter($termQuery);
        }

        $masterQuery =  new Query($query);
        if($filter->getLimit() !== null) {
            $masterQuery->setSize(intval($filter->getLimit()));
        }
        if($filter->getOrderBy() !== null) {
            $direction = $filter->getOrderDirection();
            if(empty($direction)) {
                $direction = 'ASC';
            }
            $masterQuery->addSort([sprintf('filterData.%s', $filter->getOrderBy()) => $direction]);
        }

        return $masterQuery;
    }

    private function createFilterQuery($fieldName, $filterQuery)
    {
        if($filterQuery instanceof MatchQuery) {
            if($filterQuery->getOperator() == MatchQuery::OPERATOR_EQUAL) {
                $query = new Query\Term();
                $query->setTerm($fieldName, $filterQuery->getValue());
                return $query;
            } elseif($filterQuery->getOperator() == MatchQuery::OPERATOR_NOT) {
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
        } elseif($filterQuery instanceof BetweenQuery) {
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
        } elseif($filterQuery instanceof ContainsQuery) {
            $boolQuery = new Query\BoolQuery();
            foreach ($filterQuery->getValues() as $value) {
                $term = new Query\Term([$fieldName => $value]);
                $boolQuery->addShould($term);
            }
            return $boolQuery;
        }
        throw new FilterQueryNotSupportedException(sprintf('Filter query from type "%s" is not supported', get_class($filterQuery)));
    }

    public function search(Filter $filter)
    {
        $search = new Search($this->client);
        $search->addIndex(self::ELASTIC_SEARCH_INDEX);
        $search->setQuery($this->createQuery($filter));

        $result = [];
        foreach($search->search() as $document) {
            $data = $document->getData();
            $id = $data['id'];
            $className = $data['className'];
            $result[] = $this->em->getRepository($className)->find($id);
        }
        return $result;
    }

    public function searchPaginated(Filter $filter)
    {
        $query = $this->createQuery($filter);
        $searchable = $this->getIndex();
        return new Pagerfanta(new ElasticaORMAdapter($searchable, $query, $this->em));
    }

    public function index($resource)
    {
        if (!$this->indexing) {
            return;
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);
        if($metadata && in_array($metadata->getClassName(), $this->classes)) {
            $index = $this->getIndex();
            $document = $this->createDocument($resource);
            $type = $this->getType();
            $type->addDocument($document);
            $index->refresh();
        }
    }

    /**
     * @return \Elastica\Index
     */
    private function getIndex()
    {
        $index = $this->client->getIndex(self::ELASTIC_SEARCH_INDEX);
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
        $indexes = $this->indexer->getIndexes($resource);

        $id = $resource->getId();
        $className = $metadata->getClassName();
        $documentId = sha1($id.$className);

        $indexData = [];
        foreach($indexes as $index) {
            $weight = intval($index->getWeight());
            $key = 'value.'.$weight;
            if(!array_key_exists($key, $indexData)) {
                $indexData[$key] = [];
            }
            $indexData[$key][] = $index->getValue();
        }

        $filterData = [];
        foreach($this->filterData->getData($resource) as $data) {
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
        if($this->metadataRepository->hasMetadata($resource)) {
            /** @var Metadata $metadata */
            $metadata = $this->metadataRepository->getMetadata($resource);

            $id = $resource->getId();
            $className = $metadata->getClassName();
            $documentId = sha1($id.$className);

            $type = $this->getType();
            $type->deleteById($documentId);

            $index = $this->getIndex();
            $index->refresh();
        }
    }

    public function reindex()
    {
        $this->getIndex()->delete();
        $this->initialize();
        foreach ($this->classes as $class) {
            $repository = $this->em->getRepository($class);
            $entities = $repository->findAll();
            foreach($entities as $entity) {
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
}
