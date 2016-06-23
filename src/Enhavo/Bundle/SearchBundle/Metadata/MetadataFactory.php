<?php

namespace Enhavo\Bundle\SearchBundle\Metadata;

use Doctrine\Common\Persistence\Proxy;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * MetadataFactory.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class MetadataFactory
{
    /**
     * @var MetadataCollector
     */
    protected $collector;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel, MetadataCollector $collector)
    {
        $this->collector = $collector;
        $this->kernel = $kernel;
    }

    protected function getBundleName($className)
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

    protected function getConfiguration($className)
    {
        $configurations = $this->collector->getConfigurations();
        if(isset($configurations[$className])) {
            return $configurations[$className];
        }
        return null;
    }

    public function getClassName($resource)
    {
        $resourceClassName = get_class($resource);
        if ($resource instanceof Proxy) {
            $resourceClassName = get_parent_class($resource);
        }
        return $resourceClassName;
    }

    public function create($resource)
    {
        $className = $this->getClassName($resource);

        $configuration = $this->getConfiguration($className);
        if($configuration === null) {
            return null;
        }

        $metadata = new Metadata();
        $metadata->setBundleName($this->getBundleName($className));
        $metadata->setClassName($className);

        if($configuration['properties']) {
            $properties = [];
            foreach($configuration['properties'] as $property => $config) {
                foreach($config as $type => $options) {
                    $propertyNode = new PropertyNode();
                    $propertyNode->setType($type);
                    $propertyNode->setOptions($options);
                    $propertyNode->setProperty($property);
                    $properties[] = $propertyNode;
                }
            }
            $metadata->setProperties($properties);
        }

        return $metadata;
    }
}