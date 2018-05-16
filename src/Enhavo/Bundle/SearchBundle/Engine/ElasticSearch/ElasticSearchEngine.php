<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 22:07
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Event\Events;
use Enhavo\Bundle\SearchBundle\Event\IndexEvent;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(Indexer $indexer, MetadataRepository $metadataRepository, EntityManager $em, Client $client, Extractor $extractor, EventDispatcherInterface $eventDispatcher)
    {
        $this->indexer = $indexer;
        $this->metadataRepository = $metadataRepository;
        $this->em = $em;
        $this->client = $client;
        $this->extractor = $extractor;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function initialize()
    {
        $index = $this->getIndex();
        if(!$index->exists()) {
            $index->create();
        }

        $type = $this->getType();

        $mapping = new Mapping();
        $mapping->setType($type);

        $indexData = [];
        for($i = 0; $i <= 100; $i++) {
            $indexData['value'.$i] = array('type' => 'text', 'boost' => $i);
        }

        $mapping->setProperties(array(
            'className' => array('type' => 'text'),
            'indexData' => array(
                'type' => 'object',
                'properties' => $indexData
            ),
            'filterData' => array(
                'type' => 'object',
            ),
        ));

        $mapping->send();
    }

    public function search(Filter $filter)
    {
        $search = new Search($this->client);
        $search->addIndex(self::ELASTIC_SEARCH_INDEX);
        $search->addType(self::ELASTIC_SEARCH_TYPE);

        $query = new Query\BoolQuery();

        $filterQuery = new Query\QueryString();
        $filterQuery->setQuery($filter->getTerm());
        $filterQuery->setDefaultField('indexData.*');
        $query->addFilter($filterQuery);

        foreach($filter->getFilter() as $key => $value) {
            $fieldName = sprintf('filterData.%s', $key);

            $filterQuery = new Query\QueryString();
            $filterQuery->setQuery($value);
            $filterQuery->setDefaultField($fieldName);

            $query->addFilter($filterQuery);
        }

        $search->setQuery($query);

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

    }

    public function index($resource)
    {
        if($this->metadataRepository->hasMetadata($resource)) {
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
     * @return \Elastica\Type
     */
    private function getType()
    {
        $type = $this->client->getIndex(self::ELASTIC_SEARCH_INDEX)->getType(self::ELASTIC_SEARCH_TYPE);
        return $type;
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
        $filter = $metadata->getFilter();

        $event = new IndexEvent($resource);
        $event->setIndexes($indexes);
        $event->setFilters($filter);
        $this->eventDispatcher->dispatch(Events::INDEX_EVENT, $event);

        $indexes = $event->getIndexes();
        $filter = $event->getFilters();

        $id = $resource->getId();
        $className = $metadata->getClassName();
        $documentId = sha1($id.$className);

        $indexData = [];
        foreach($indexes as $index) {
            $weight = intval($index->getWeight());
            $key = 'value'.$weight;
            if(!array_key_exists($key, $indexData)) {
                $indexData[$key] = [];
            }
            $indexData[$key][] = $index->getValue();
        }

        $filterData = [];
        foreach($filter as $filterItem) {
            $filterData[$filterItem->getKey()] = $filterItem->getValue();
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

    }
}