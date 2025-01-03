<?php

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Doctrine\Common\Proxy\Proxy;
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
            return $duplicate->duplicate(new SourceValue($source), new TargetValue($target), $context);
        }

        $target = $target ?? new ($metadata->getClassName());

        $reflection = new \ReflectionClass($source);

        if ($source instanceof Proxy) {
            if (!$source->__isInitialized()) {
                $source->__load();
            }
            $reflection = $reflection->getParentClass();
        }

        foreach ($metadata->getProperties() as $property => $configs) {
            foreach ($configs as $config) {
                $reflectionProperty = $reflection->getProperty($property);
                $reflectionProperty->setAccessible(true);

                $sourceValue = new SourceValue($reflectionProperty->getValue($source), $source, $property);
                $targetValue = new TargetValue($target ? $reflectionProperty->getValue($target) : null, $target, $property);

                /** @var Duplicate $duplicate */
                $duplicate = $this->duplicateFactory->create($config);
                $newValue = $duplicate->duplicate($sourceValue, $targetValue, $context);
                $reflectionProperty->setValue($target, $newValue);
            }
        }

        return $target;
    }
}
