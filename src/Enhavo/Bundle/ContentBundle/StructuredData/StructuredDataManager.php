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
use Symfony\Component\PropertyAccess\PropertyAccess;

class StructuredDataManager
{
    public function __construct(
        private readonly MetadataRepository $metadataRepository,
        private readonly FactoryInterface $structuredDataFactory,
    )
    {
    }

    public function getData(object $model): array
    {
        $bag = new StructuredDataBag();
        $this->buildData($model, $bag);
        return $bag->toArray();
    }

    public function buildData(object $model, StructuredDataBag $bag): void
    {
        /** @var Metadata $metadata */
        $metadata = $this->metadataRepository->getMetadata($model);

        foreach ($metadata->getClasses() as $config) {
            /** @var StructuredDataContainer $structuredDataContainer */
            $structuredDataContainer = $this->structuredDataFactory->create($config);

            $context = new Context(Context::ATTRIBUTE_CLASS, $structuredDataContainer->getTypeName());
            $structuredDataContainer->buildData($model, $bag, $context);
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($metadata->getProperties() as $propertyName => $property) {
            $propertyValue = $propertyAccessor->getValue($model, $propertyName);
            foreach ($property as $config) {
                /** @var StructuredDataContainer $structuredDataContainer */
                $structuredDataContainer = $this->structuredDataFactory->create($config);
                $context = new Context(Context::ATTRIBUTE_PROPERTY, $structuredDataContainer->getTypeName(), $propertyName, $propertyValue);
                $structuredDataContainer->buildData($model, $bag, $context);
            }
        }

        foreach ($metadata->getMethods() as $methodName => $method) {
            $methodValue = $propertyAccessor->getValue($model, $methodName);
            foreach ($method as $config) {
                /** @var StructuredDataContainer $structuredDataContainer */
                $structuredDataContainer = $this->structuredDataFactory->create($config);
                $context = new Context(Context::ATTRIBUTE_METHOD, $structuredDataContainer->getTypeName(), $methodName, $methodValue);
                $structuredDataContainer->buildData($model, $bag, $context);
            }
        }
    }
}
