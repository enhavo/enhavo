<?php

namespace Enhavo\Bundle\RoutingBundle\Metadata;

use Enhavo\Bundle\AppBundle\Metadata\MetadataFactoryInterface;

/**
 * MetadataFactory.php
 *
 * @since 18/08/18
 * @author gseidel
 *
 * Creates the metadata for a given resource
 */
class MetadataFactory implements MetadataFactoryInterface
{
    /**
     * @param string $className
     * @param array $configuration
     * @return Metadata
     */
    public function create($className, array $configuration = [])
    {
        $metadata = new Metadata();
        $metadata->setClassName($className);

        if(isset($configuration['generators'])) {
            $generators = [];
            foreach($configuration['generators'] as $property => $config) {
                $generator = new Generator();
                $generator->setType($config['type']);
                unset($config['type']);
                $generator->setOptions($config);
                $generators[] = $generator;
            }
            $metadata->setGenerators($generators);
        }

        return $metadata;
    }
}