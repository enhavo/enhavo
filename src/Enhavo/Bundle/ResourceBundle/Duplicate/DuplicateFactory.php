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

    public function duplicate(object $value, $context = []): object
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($value);

        if ($metadata->getClass()) {
            /** @var Duplicate $duplicate */
            $duplicate = $this->duplicateFactory->create($metadata->getClass());
            return $duplicate->duplicate($value, $context);
        }

        $object = new ($metadata->getClassName());

        $reflection = new \ReflectionClass($value);

        foreach ($metadata->getProperties() as $property => $config) {

            $property = $reflection->getProperty($property);
            $property->setAccessible(true);

            $originalValue = $property->getValue($value);

            /** @var Duplicate $duplicate */
            $duplicate = $this->duplicateFactory->create($config);
            $newValue = $duplicate->duplicate($originalValue, $context);
            $property->setValue($object, $newValue);
        }

        return $object;
    }
}
