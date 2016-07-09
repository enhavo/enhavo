<?php

namespace Enhavo\Bundle\SearchBundle\Metadata;

use Doctrine\Common\Persistence\Proxy;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * MetadataFactory.php
 *
 * @since 23/06/16
 * @author gseidel
 *
 * Creates the metadata for a given resource
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

    protected function getHumanizedBundleName($className)
    {
        $bundleName = $this->getBundleName($className);
        $splittedBundleName = preg_split('/([[:upper:]][[:lower:]]+)/', $bundleName, null, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
        return strtolower(implode('_', $splittedBundleName));
    }

    protected function getConfiguration($className)
    {
        $configurations = $this->collector->getConfigurations();
        if(isset($configurations[$className])) {
            return $configurations[$className];
        } else if($className == 'Doctrine\ORM\PersistentCollection'){

        }
        return null;
    }

    protected function getClassName($resource)
    {
        $resourceClassName = get_class($resource);
        if ($resource instanceof Proxy) {
            $resourceClassName = get_parent_class($resource);
        }
        return $resourceClassName;
    }

    protected function getEntityName($resource)
    {
        $className = null;
        if(is_string($resource)){
            $className = $resource;
        } else {
            $className = $this->getClassName($resource);
        }
        $splittedClassName = preg_split('/\\\\|\//', $className);
        $entityName = array_pop($splittedClassName);
        return $entityName;
    }

    public function create($resource)
    {
        $className = null;
        if(is_string($resource)){
            $className = $resource;
        } else {
            $className = $this->getClassName($resource);// ...Entity/Article
        }

        $configuration = $this->getConfiguration($className);// array(properties => array(title, teaser, ...))
        if($configuration === null) {
            return null;
        }

        $metadata = new Metadata();
        $metadata->setBundleName($this->getBundleName($className));
        $metadata->setHumanizedBundleName($this->getHumanizedBundleName($className));
        $metadata->setClassName($className);
        $metadata->setEntityName($this->getEntityName($resource));

        if($configuration['properties']) {
            $properties = [];
            foreach($configuration['properties'] as $property => $config) {
                if(is_array($config[0])){
                    foreach($config[0] as $type => $options) {
                        $propertyNode = new PropertyNode();
                        $propertyNode->setType($type);// type: Plain
                        $propertyNode->setOptions($options);// weight, type
                        $propertyNode->setProperty($property);//property: title
                        $properties[] = $propertyNode;
                    }
                } else {
                    $propertyNode = new PropertyNode();
                    $propertyNode->setType($config[0]);
                    $propertyNode->setOptions(null);
                    $propertyNode->setProperty($property);
                    $properties[] = $propertyNode;
                }
            }
            $metadata->setProperties($properties);
        }

        return $metadata;
    }
}