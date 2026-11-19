<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Duplicate;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Proxy\DefaultProxyClassNameResolver;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Diff;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\DiffValueFormatter;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;

/**
 * Compares two states of a resource. Like the DuplicateFactory it walks through the duplicate metadata, and every
 * property with an applicable duplicate config (e.g. group "revision") is compared by its duplicate type.
 */
class DiffFactory
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $duplicateFactory,
        private readonly DiffValueFormatter $formatter,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function diff(?object $old, ?object $new, array $context = []): Diff
    {
        $diff = new Diff($this, $this->formatter);
        $this->diffObject($diff, $old, $new, $context);

        return $diff;
    }

    public function diffObject(Diff $diff, ?object $old, ?object $new, array $context = []): void
    {
        $subject = $new ?? $old;
        if (null === $subject) {
            return;
        }

        if (null !== $old && null !== $new && DefaultProxyClassNameResolver::getClass($old) !== DefaultProxyClassNameResolver::getClass($new)) {
            // different models can't be compared property by property, show them as removed and added
            $this->diffObject($diff, $old, null, $context);
            $this->diffObject($diff, null, $new, $context);

            return;
        }

        // properties are read by reflection, which doesn't load lazy doctrine proxies
        $this->initialize($old);
        $this->initialize($new);

        /** @var Metadata[] $metadataList */
        $metadataList = [];
        foreach ($this->getClasses($subject) as $class) {
            $metadataList[$class] = $this->metadataRepository->getMetadata($class);
        }

        foreach ($metadataList as $metadata) {
            if ($metadata?->getClass()) {
                /** @var Duplicate $duplicate */
                $duplicate = $this->duplicateFactory->create($metadata->getClass());
                $oldValue = new SourceValue($old);
                $newValue = new SourceValue($new);
                if ($duplicate->isApplicable($newValue, new TargetValue(), $context)) {
                    $duplicate->diff($oldValue, $newValue, $diff, $context);
                }

                return;
            }
        }

        $done = [];
        foreach ($metadataList as $class => $metadata) {
            if (null === $metadata) {
                continue;
            }

            $reflection = new \ReflectionClass($class);
            foreach ($metadata->getProperties() as $property => $configs) {
                // a property can have one config per group, compare it only once
                if (isset($done[$property])) {
                    continue;
                }

                $reflectionProperty = $reflection->getProperty($property);
                $oldValue = new SourceValue($old ? $reflectionProperty->getValue($old) : null, $old, $property);
                $newValue = new SourceValue($new ? $reflectionProperty->getValue($new) : null, $new, $property);

                foreach ($configs as $config) {
                    /** @var Duplicate $duplicate */
                    $duplicate = $this->duplicateFactory->create($config);
                    if ($duplicate->isApplicable($newValue, new TargetValue(null, $new, $property), $context)) {
                        $duplicate->diff($oldValue, $newValue, $diff, $context);
                        $done[$property] = true;
                        break;
                    }
                }
            }
        }
    }

    private function initialize(?object $object): void
    {
        if (null !== $object) {
            $this->entityManager->initializeObject($object);
        }
    }

    private function getClasses(object $subject): array
    {
        $class = DefaultProxyClassNameResolver::getClass($subject);

        return array_merge([$class], array_values(class_parents($class)));
    }
}
