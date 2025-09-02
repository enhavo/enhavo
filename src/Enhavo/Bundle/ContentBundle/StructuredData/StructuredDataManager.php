<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\StructuredData;

use Enhavo\Bundle\ContentBundle\StructuredData\Metadata\Metadata;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class StructuredDataManager
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $structuredDataFactory,
    )
    {
    }

    public function getData(object $model, array|string|null $groups = null): array
    {
        $bag = new StructuredDataBag();
        $this->buildData($model, $bag, $groups);
        return $bag->toArray();
    }

    public function buildData(object $model, StructuredDataBag $bag, array|string|null $groups = null): void
    {
        if (null === $groups) {
            $groups = ['Default'];
        }

        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($model);

        foreach ($metadata->getClasses() as $config) {
            /** @var StructuredDataContainer $structuredDataContainer */
            $structuredDataContainer = $this->structuredDataFactory->create($config);

            if ($this->inGroup($structuredDataContainer->getGroups(), $groups)) {
                $context = new Context(Context::ATTRIBUTE_CLASS, $structuredDataContainer->getTypeName(), groups: $groups);
                $structuredDataContainer->buildData($model, $bag, $context);
            }
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($metadata->getProperties() as $propertyName => $property) {
            $propertyValue = $propertyAccessor->getValue($model, $propertyName);
            foreach ($property as $config) {
                /** @var StructuredDataContainer $structuredDataContainer */
                $structuredDataContainer = $this->structuredDataFactory->create($config);

                if ($this->inGroup($structuredDataContainer->getGroups(), $groups)) {
                    $context = new Context(Context::ATTRIBUTE_PROPERTY, $structuredDataContainer->getTypeName(), $propertyName, $propertyValue, $groups);
                    $structuredDataContainer->buildData($model, $bag, $context);
                }
            }
        }

        foreach ($metadata->getMethods() as $methodName => $method) {
            $methodValue = $propertyAccessor->getValue($model, $methodName);
            foreach ($method as $config) {
                /** @var StructuredDataContainer $structuredDataContainer */
                $structuredDataContainer = $this->structuredDataFactory->create($config);

                if ($this->inGroup($structuredDataContainer->getGroups(), $groups)) {
                    $context = new Context(Context::ATTRIBUTE_METHOD, $structuredDataContainer->getTypeName(), $methodName, $methodValue, $groups);
                    $structuredDataContainer->buildData($model, $bag, $context);
                }
            }
        }
    }

    private function inGroup(array $typeGroups, array|string|null $contextGroups): bool
    {
        if (is_string($contextGroups)) {
            $contextGroups = [$contextGroups];
        } elseif ($contextGroups === null) {
            $contextGroups = [];
        }

        if (count($contextGroups) === 0 && count($typeGroups) === 0) {
            return true;
        }

        if (count($typeGroups) === 0) {
            return true;
        }

        return count(array_intersect($typeGroups, $contextGroups)) > 0;
    }
}
