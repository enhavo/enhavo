<?php
/**
 * MetadataCollector.php
 *
 * @since 10/05/18
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Metadata;

use Doctrine\ORM\Proxy\Proxy;

class MetadataRepository
{
    /**
     * @var MetadataConfigurationInterface
     */
    private $configuration;

    /**
     * @var MetadataFactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $metadataCache = [];

    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    /**
     * MetadataRepository constructor.
     *
     * @param MetadataConfigurationInterface $configuration
     * @param MetadataFactoryInterface $factory
     */
    public function __construct(MetadataConfigurationInterface $configuration, MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
    }

    /**
     * @param $entity
     * @throws \Exception
     * @return object
     */
    public function getMetadata($entity)
    {
        $className = $this->getClassName($entity);

        if(array_key_exists($className, $this->metadataCache)) {
            return  $this->metadataCache[$className];
        }

        $metadataArray = [];
        $this->findMetadata($className, $metadataArray);
        $metadata = $this->factory->create($className, $metadataArray);
        $this->metadataCache[$className] = $metadata;
        return $metadata;
    }

    /**
     * @param $entity
     * @return bool
     */
    public function hasMetadata($entity)
    {
        $className = $this->getClassName($entity);

        $configuration = $this->configuration->getConfiguration();

        if(array_key_exists($className, $configuration)) {
            return true;
        }
        return false;
    }

    private function getClassName($entity)
    {
        if(is_string($entity)) {
            $className = $entity;
        } else if(is_object($entity)) {
            if($entity instanceof Proxy) {
                $className = get_parent_class($entity);
            } else {
                $className = get_class($entity);
            }
        } else {
            throw new \Exception('entity should be type of string or an object');
        }

        return $className;
    }

    public function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    private function findMetadata($className, array &$metadataArray)
    {
        $parentClass = get_parent_class($className);
        if($parentClass) {
            $this->findMetadata($parentClass, $metadataArray);
        }

        $configuration = $this->configuration->getConfiguration();

        if(array_key_exists($className, $configuration)) {
            foreach($this->parsers as $parser) {
                $parser->parse($metadataArray, $configuration[$className]);
            }
        }
    }
}