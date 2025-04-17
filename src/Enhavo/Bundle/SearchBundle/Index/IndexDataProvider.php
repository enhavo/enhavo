<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 10.05.18
 * Time: 17:24
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Bundle\SearchBundle\Index\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;

class IndexDataProvider
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface   $factory,
    )
    {
    }

    /** @return IndexData[] */
    public function getIndexData($resource): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        if ($metadata === null) {
            return [];
        }

        $data = [];
        foreach ($metadata->getIndex() as $config) {
            /** @var Index $index */
            $index = $this->factory->create($config->getConfig(), $config->getKey());
            $indexData = $index->getIndexData($resource);

            foreach($indexData as $indexEntry) {
                $data[] = $indexEntry;
            }
        }
        return $data;
    }

    public function getRawData($resource): array
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($resource);

        if ($metadata === null) {
            return [];
        }

        $raw = [];
        foreach ($metadata->getIndex() as $key => $config) {
            /** @var Index $index */
            $index = $this->factory->create($config->getConfig(), $key);
            $rawData = $index->getRawData($resource);

            foreach($rawData as $rawEntry) {
                if (trim($rawEntry) !== '') {
                    $raw[] = $rawEntry;
                }
            }
        }

        return $raw;
    }
}
