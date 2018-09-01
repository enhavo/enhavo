<?php

namespace Enhavo\Bundle\SearchBundle\Metadata;

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

        $metadata->setBundleName($this->getBundleName($className));
        $metadata->setHumanizedBundleName($this->getHumanizedBundleName($className));
        $metadata->setClassName($className);
        $metadata->setEntityName($this->getEntityName($className));

        if(isset($configuration['properties'])) {
            $properties = [];
            foreach($configuration['properties'] as $property => $config) {
                $propertyNode = new PropertyNode();
                $propertyNode->setType($config['type']);
                $propertyNode->setOptions($config);
                $propertyNode->setProperty($property);
                $properties[] = $propertyNode;
            }
            $metadata->setProperties($properties);
        }

        if(isset($configuration['filter'])) {
            $filters = [];
            foreach($configuration['filter'] as $key => $options) {
                $filter = new Filter();
                $filter->setKey($key);
                $filter->setType($options['type']);
                unset($options['type']);
                $filter->setOptions($options);
                $filters[] = $filter;
            }
            $metadata->setFilters($filters);
        }

        return $metadata;
    }

    private function getBundleName($className)
    {
        $bundles = $this->kernel->getBundles();

        foreach($bundles as $bundle) {
            $class = get_class($bundle);
            $classParts = explode('\\', $class);
            $bundleName = array_pop($classParts);
            $bundlePath = implode('\\', $classParts);
            if(strpos($className, $bundlePath) === 0) {
                return $bundleName;
            }
        }
        return null;
    }

    private function getHumanizedBundleName($className)
    {
        $bundleName = $this->getBundleName($className);
        $splitClassName = preg_split('/([[:upper:]][[:lower:]]+)/', $bundleName, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
        return strtolower(implode('_', $splitClassName));
    }

    private function getEntityName($className)
    {
        $splitClassName = preg_split('/\\\\|\//', $className);
        $entityName = array_pop($splitClassName);
        return $entityName;
    }
}