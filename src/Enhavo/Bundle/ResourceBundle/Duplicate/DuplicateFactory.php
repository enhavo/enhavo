<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Component\Type\FactoryInterface;

class DuplicateFactory
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $duplicateFactory,
    )
    {
    }

    public function duplicate(object $source, object $target = null, $context = []): object
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($source);

        if ($metadata->getClass()) {
            /** @var Duplicate $duplicate */
            $duplicate = $this->duplicateFactory->create($metadata->getClass());
            return $duplicate->duplicate($source, $target, $context);
        }

        $target = $target ?? new ($metadata->getClassName());

        $reflection = new \ReflectionClass($source);

        foreach ($metadata->getProperties() as $property => $config) {

            $property = $reflection->getProperty($property);
            $property->setAccessible(true);

            $sourceValue = $property->getValue($source);
            $targetValue = $target ? $property->getValue($target) : null;

            /** @var Duplicate $duplicate */
            $duplicate = $this->duplicateFactory->create($config);
            $newValue = $duplicate->duplicate($sourceValue, $targetValue, $context);
            $property->setValue($target, $newValue);
        }

        return $target;
    }
}
