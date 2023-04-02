<?php

namespace Enhavo\Bundle\SearchBundle\Engine\ElasticSearch;

use Elastica\Client;
use Elastica\Index;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;

class ElasticSearchIndexRemover
{
    public function __construct(
        private Client $client,
        private MetadataRepository $metadataRepository,
        private ElasticSearchDocumentIdGenerator $documentIdGenerator,
        private string $indexName,
    ) {}

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
