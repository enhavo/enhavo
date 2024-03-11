<?php

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Elastica\Client;
use Elastica\Index;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;

class ElasticSearchIndexRemover
{
    private readonly Client $client;
    private readonly string $indexName;

    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly ElasticSearchDocumentIdGenerator $documentIdGenerator,
        ClientFactory $clientFactory,
        $dsn,
    ) {
        $this->indexName = $clientFactory->getIndexName($dsn);
        $this->client = $clientFactory->create($dsn);
    }

    public function removeIndex($resource)
    {
        if($this->metadataRepository->hasMetadata($resource)) {
            /** @var Metadata $metadata */
            $metadata = $this->metadataRepository->getMetadata($resource);

            $id = $resource->getId();
            $className = $metadata->getClassName();
            $this->removeIndexByClassNameAndId($className, $id);
        }
    }

    public function removeIndexByClassNameAndId($className, $id)
    {
        $documentId = $this->documentIdGenerator->generateDocumentId($className, $id);

        $index = $this->getIndex();
        $index->deleteById($documentId);
        $index->refresh();
    }

    /**
     * @return Index
     */
    private function getIndex()
    {
        $index = $this->client->getIndex($this->indexName);
        return $index;
    }
}
