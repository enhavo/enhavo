<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 22:07
 */

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\AppBundle\Metadata\MetadataRepository;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Indexer\Indexer;
use Elastica\Client;
use Elastica\Document;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;

class ElasticSearchEngine implements EngineInterface
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
     * @var Client
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Indexer $indexer, MetadataRepository $metadataRepository, EntityManager $em)
    {
        $this->indexer = $indexer;
        $this->metadataRepository = $metadataRepository;
        $this->em = $em;
        $this->client = new Client(array(
            'host' => 'localhost',
            'port' => 9200
        ));
    }

    public function search(Filter $filter)
    {
        $search = new \Elastica\Search($this->client);
        $search->addIndex('enhavo_search');
        $search->addType('document');

        $query = new \Elastica\Query();

        $query
            ->setExplain(true)
            ->setVersion(true)
            ->setMinScore(0.5);

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
        $index = $this->getIndex();
        $document = $this->createDocument($resource);
        $type = $index->getType('document');
        $type->addDocument($document);
        $index->refresh();
    }

    /**
     * @return \Elastica\Index
     */
    private function getIndex()
    {
        $index = $this->client->getIndex('enhavo_search');
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
            if(!array_key_exists($weight, $indexData)) {
                $indexData[$weight] = [];
            }
            $indexData[$weight][] = $index->getValue();
        }

        $data = [
            'id' => $id,
            'className' => $className,
            'indexData' => $indexData
        ];

        $document = new Document($documentId, $data);
        return $document;
    }

    public function removeIndex($resource)
    {
        
    }

    public function reindex()
    {

    }
}