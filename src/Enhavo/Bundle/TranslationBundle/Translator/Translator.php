<?php
/**
 * Translator.php
 *
 * @since 03/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Enhavo\Bundle\TranslationBundle\Metadata\MetadataCollection;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Translator
{
    use ContainerAwareTrait;

    /**
     * @var MetadataCollection
     */
    private $metadataCollection;

    /**
     * @var StrategyResolver
     */
    private $strategyResolver;

    public function __construct(MetadataCollection $metadataCollection, StrategyResolver $strategyResolver)
    {
        $this->metadataCollection = $metadataCollection;
        $this->strategyResolver = $strategyResolver;
    }

    public function store($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        if($metadata !== null) {
            foreach($metadata->getProperties() as $property) {
                $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
                $strategy->storeValue($entity, $metadata, $property);
            }
        }
    }

    public function delete($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $strategies = $this->strategyResolver->getStrategies();
        foreach($strategies as $strategy) {
            $strategy->delete($entity, $metadata);
        }
    }

    public function getTranslations($entity, Property $property)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $property = $metadata->getProperty($property->getName());

        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        return $strategy->getTranslations($entity, $metadata, $property);
    }

    public function updateReferences()
    {
        $strategies = $this->strategyResolver->getStrategies();
        foreach($strategies as $strategy) {
            $strategy->updateReferences();
        }
    }

    public function translate($entity, $locale)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        if($metadata !== null) {
            foreach($metadata->getProperties() as $property) {
                $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
                $strategy->translate($entity, $metadata, $property, $locale);
            }
        }
    }
}