<?php

namespace Enhavo\Bundle\TranslationBundle\Metadata;

use Enhavo\Bundle\AppBundle\Metadata\MetadataFactoryInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * MetadataFactory.php
 *
 * @since 23/06/16
 * @author gseidel
 *
 * Creates the metadata for a given resource
 */
class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function create($className, array $configuration = [])
    {
        $metadata = new Metadata();

        $metadata->setClass($className);

        if(isset($configuration['properties'])) {
            $properties = [];
            foreach($configuration['properties'] as $property => $config) {
                $propertyNode = new PropertyNode();
                $propertyNode->setType($config['type']);
                unset($config['type']);
                $propertyNode->setOptions($config);
                $propertyNode->setProperty($property);
                $properties[] = $propertyNode;
            }
            $metadata->setProperties($properties);
        }

        return $metadata;
    }
}
