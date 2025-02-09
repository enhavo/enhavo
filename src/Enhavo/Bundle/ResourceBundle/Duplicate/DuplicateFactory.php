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
        $classes = $this->getClasses($source);

        /** @var Metadata[] $metadataList */
        $metadataList = [];
        foreach ($classes as $class) {
            $metadataList[$class] = $this->metadataRepository->getMetadata($class);
        }

        foreach ($metadataList as $metadata) {
            if ($metadata->getClass()) {
                /** @var Duplicate $duplicate */
                $duplicate = $this->duplicateFactory->create($metadata->getClass());
                return $duplicate->duplicate(new SourceValue($source), new TargetValue($target), $context);
            }
        }

        $target = $target ?? new (get_class($source));

        if ($source instanceof Proxy) {
            if (!$source->__isInitialized()) {
                $source->__load();
            }
        }

        foreach ($metadataList as $class => $metadata) {
            $reflection = new \ReflectionClass($class);

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
        }

        return $target;
    }

    private function getClasses(object $source)
    {
        $classes = [];
        $classes[] = get_class($source);

        $parentClass = get_class($source);
        do {
            $parentClass = get_parent_class($parentClass);
            if ($parentClass !== false) {
                $classes[] = $parentClass;
            }
        } while($parentClass !== false);

        return $classes;
    }
}
