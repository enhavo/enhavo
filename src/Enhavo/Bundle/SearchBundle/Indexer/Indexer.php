<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 17:24
 */

namespace Enhavo\Bundle\SearchBundle\Indexer;

use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Indexer implements IndexerInterface
{
    /**
     * @var MetadataRepository
     */
    private $metadataRepository;

    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * Extractor constructor.
     *
     * @param MetadataRepository $metadataRepository
     * @param TypeCollector $collector
     */
    public function __construct(MetadataRepository $metadataRepository, TypeCollector $collector)
    {
        $this->metadataRepository = $metadataRepository;
        $this->collector = $collector;
    }

    /**
     * @param $resource
     * @return Index[]
     */
    public function getIndexes($resource, array $options = [])
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        if ($metadata === null) {
            return [];
        }

        $data = [];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach($metadata->getProperties() as $property) {
            $indexer = $this->collector->getType($property->getType());
            $value = $propertyAccessor->getValue($resource, $property->getProperty());
            $indexes = $indexer->getIndexes($value, $property->getOptions());
            foreach($indexes as $index) {
                $data[] = $index;
            }
        }
        return $data;
    }
}
