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
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class Translator
 *
 * The Translator is responsible for the translation data
 *
 * @package Enhavo\Bundle\TranslationBundle\Translator
 */
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

    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct(MetadataCollection $metadataCollection, StrategyResolver $strategyResolver, $defaultLocale)
    {
        $this->metadataCollection = $metadataCollection;
        $this->strategyResolver = $strategyResolver;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * The translation data that was added before, will be prepared to store now. Because that should be used
     * inside the preFlush hook, this function don't use any flush.
     *
     * @param $entity
     * @throws \Exception
     */
    public function storeTranslationData($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return;
        }

        $strategies = $this->strategyResolver->getStrategies();
        foreach($strategies as $strategy) {
            $strategy->storeTranslationData($entity, $metadata);
        }
    }

    /**
     * Add translation data, but does not store it.
     *
     * @param $entity
     * @param string $propertyPath Property of the entity, that should be added
     * @param mixed $data
     * @throws \Exception
     */
    public function addTranslationData($entity, $propertyPath, $data)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        $property = $metadata->getProperty($propertyPath);
        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        $strategy->addTranslationData($entity, $property, $data, $metadata);
    }

    /**
     * Normalize the form data. The data that a form contains after submit is a mix of the model data and the translation data.
     * This function will return only the translation data.
     *
     * @param $entity
     * @param $propertyPath
     * @param $formData
     * @return mixed
     * @throws \Exception
     */
    public function normalizeToTranslationData($entity, $propertyPath, $formData)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        $property = $metadata->getProperty($propertyPath);
        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        return $strategy->normalizeToTranslationData($entity, $property, $formData, $metadata);
    }

    /**
     * Normalize the form data. The data that a form contains after submit is a mix of the model data and the translation data.
     * This function will return only the model data.
     *
     * @param $entity
     * @param $propertyPath
     * @param $formData
     * @return mixed
     * @throws \Exception
     */
    public function normalizeToModelData($entity, $propertyPath, $formData)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        $property = $metadata->getProperty($propertyPath);
        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        return $strategy->normalizeToModelData($entity, $property, $formData, $metadata);
    }

    /**
     * Prepare deleting all translation data. Because this function should be called inside a doctrine hook, it contains
     * no flush.
     *
     * @param $entity
     * @return null
     * @throws \Exception
     */
    public function deleteTranslationData($entity)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $strategies = $this->strategyResolver->getStrategies();
        foreach($strategies as $strategy) {
            $strategy->deleteTranslationData($entity, $metadata);
        }
    }

    /**
     * Return the translation data, that is already stored in the database
     *
     * @param $entity
     * @param Property $property
     * @return null
     * @throws \Exception
     */
    public function getTranslationData($entity, Property $property)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $property = $metadata->getProperty($property->getName());

        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        return $strategy->getTranslationData($entity, $property, $metadata);
    }

    /**
     * Check is a property is translatable
     *
     * @param $entity
     * @param $propertyPath
     * @return bool
     * @throws \Exception
     */
    public function isPropertyTranslatable($entity, $propertyPath)
    {
        if($entity === null) {
            return false;
        }

        if(!is_string($propertyPath)) {
            return false;
        }

        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return false;
        }

        $property = $metadata->getProperty($propertyPath);
        if($property === null) {
            return false;
        }

        return true;
    }

    /**
     * This function should be called after the flush was executed.
     *
     */
    public function postFlush()
    {
        $strategies = $this->strategyResolver->getStrategies();
        foreach($strategies as $strategy) {
            $strategy->postFlush();
        }
    }

    /**
     * Translate an entity. All stored translation data will be applied to this object. It should be called only inside the
     * doctrine postLoad hook, because it doesn't handle recursive connections.
     *
     * @param $entity
     * @param $locale
     * @return null
     * @throws \Exception
     */
    public function translate($entity, $locale)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        //translation data is stored inside the object
        if($locale === $this->defaultLocale) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();   
        if($metadata !== null) {
            foreach($metadata->getProperties() as $property) {
                $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
                $value = $strategy->getTranslation($entity, $property, $locale, $metadata);
                $accessor->setValue($entity, $property->getName(), $value);
            }
        }
    }

    /**
     * Translate a single property of an entity.
     *
     * @param $entity
     * @param $property
     * @param $locale
     * @return null
     * @throws \Exception
     */
    public function getTranslation($entity, $property, $locale)
    {
        $metadata = $this->metadataCollection->getMetadata($entity);
        if($metadata === null) {
            return null;
        }

        $property = $metadata->getProperty($property);
        if($property === null) {
            return null;
        }

        $strategy = $this->strategyResolver->getStrategy($property->getStrategy());
        return $strategy->getTranslation($entity, $property, $locale, $metadata);
    }
}