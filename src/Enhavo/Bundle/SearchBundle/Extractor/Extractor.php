<?php

namespace Enhavo\Bundle\SearchBundle\Extractor;

use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Extractor.php
 * Gets the raw data of a resource
 */
class Extractor
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
     * @return array
     */
    public function extract($resource)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        $data = [];
        if ($metadata) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            foreach ($metadata->getProperties() as $property) {
                $propertyExtractor = $this->collector->getType($property->getType());
                $value = $propertyAccessor->getValue($resource, $property->getProperty());
                $extractions = $propertyExtractor->extract($value, $property->getOptions());
                foreach($extractions as $extraction) {
                    $data[] = $extraction;
                }
            }
        }
        return $data;
    }
}
