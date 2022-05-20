<?php

namespace Enhavo\Bundle\SearchBundle\Filter;

use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\SearchBundle\Metadata\Filter;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * FilterData
 */
class FilterData
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
     * @return Data[]
     */
    public function getData($resource)
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        $result = [];
        /** @var Filter $filter */
        foreach ($metadata->getFilters() as $filter) {
            /** @var DataProviderInterface $dataProvider */
            $dataProvider = $this->collector->getType($filter->getType());
            $optionResolver = new OptionsResolver();
            $dataProvider->configureOptions($optionResolver);
            $options = $optionResolver->resolve($filter->getOptions());
            $data = $dataProvider->getData($resource, $options);
            if (is_array($data)) {
                foreach ($data as $dataItem) {
                    if ($dataItem->getKey() === null) {
                        throw new \InvalidArgumentException('If array is given a key must be provided');
                    }
                    $result[] = $dataItem;
                }
            } elseif ($data->getKey() === null) {
                $data->setKey($filter->getKey());
                $result[] = $data;
            }
        }
        return $result;
    }
}
