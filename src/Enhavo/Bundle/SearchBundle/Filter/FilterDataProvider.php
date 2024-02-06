<?php

namespace Enhavo\Bundle\SearchBundle\Filter;

use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Component\Type\FactoryInterface;

class FilterDataProvider
{
    public function __construct(
        private MetadataRepository $metadataRepository,
        private FactoryInterface $factory,
    )
    {
    }

    /** @return FilterData[] */
    public function getFilterData($resource): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        if ($metadata === null) {
            return [];
        }

        $data = [];
        foreach ($metadata->getFilter() as $config) {
            /** @var Filter $filter */
            $filter = $this->factory->create($config->getConfig(), $config->getKey());
            $filterData = $filter->getFilterData($resource);

            foreach ($filterData as $filterEntry) {
                $data[] = $filterEntry;
            }
        }

        return $data;
    }

    /** @return FilterField[] */
    public function getFields($class): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($class);

        if ($metadata === null) {
            return [];
        }

        $data = [];
        foreach ($metadata->getFilter() as $config) {
            /** @var Filter $filter */
            $filter = $this->factory->create($config->getConfig(), $config->getKey());
            $fields = $filter->getFilterFields();

            foreach ($fields as $field) {
                $data[] = $field;
            }
        }

        return $data;
    }
}
