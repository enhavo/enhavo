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

        if(array_key_exists($className, $this->metadataCache)) {
            return  $this->metadataCache[$className];
        }

        $metadataArray = [];
        $this->findMetadata($className, $metadataArray);
        $metadata = $this->factory->create($className, $metadataArray);
        $this->metadataCache[$className] = $metadata;
        return $metadata;
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