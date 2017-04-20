<?php
/**
 * TranslationStrategyInterface.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;

interface TranslationStrategyInterface
{
    /**
     * The translation data that was added before, will be prepared to store now. Because that should be used
     * inside the preFlush hook, this function don't use any flush.
     *
     * @param $entity
     * @param Metadata $metadata
     * @throws \Exception
     */
    public function storeTranslationData($entity, Metadata $metadata);

    /**
     * Add translation data, but does not store it.
     *
     * @param $entity
     * @param Property $property Property of the entity, that should be added
     * @param Metadata $metadata
     * @param mixed $data
     * @throws \Exception
     */
    public function addTranslationData($entity, Property $property, $data, Metadata $metadata);

    /**
     * Normalize the form data. The data that a form contains after submit is a mix of the model data and the translation data.
     * This function will return only the translation data.
     *
     * @param $entity
     * @param Property $property
     * @param $formData
     * @param Metadata $metadata
     * @return mixed
     * @throws \Exception
     */
    public function normalizeToTranslationData($entity, Property $property, $formData, Metadata $metadata);

    /**
     * Normalize the form data. The data that a form contains after submit is a mix of the model data and the translation data.
     * This function will return only the model data.
     *
     * @param $entity
     * @param $property
     * @param $formData
     * @param Metadata $metadata
     * @return mixed
     * @throws \Exception
     */
    public function normalizeToModelData($entity, Property $property, $formData, Metadata $metadata);

    /**
     * Prepare deleting all translation data. Because this function should be called inside a doctrine hook, it contains
     * no flush.
     *
     * @param $entity
     * @param Metadata $metadata
     * @return mixed
     * @throws \Exception
     */
    public function deleteTranslationData($entity, Metadata $metadata);

    /**
     * Return the translation data, that is already stored in the database
     *
     * @param $entity
     * @param Property $property
     * @param Metadata $metadata
     * @return mixed
     * @throws \Exception
     */
    public function getTranslationData($entity, Property $property, Metadata $metadata);
    
    /**
     * This function should be called after the flush was executed.
     *
     */
    public function postFlush();

    /**
     * Translate a property. Return the translated data
     *
     * @param $entity
     * @param $locale
     * @param Metadata $metadata
     * @return mixed
     * @throws \Exception
     */
    public function getTranslation($entity, Property $property, $locale, Metadata $metadata);
}