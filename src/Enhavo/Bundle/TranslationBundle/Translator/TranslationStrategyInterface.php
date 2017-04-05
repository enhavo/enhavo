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
    public function storeTranslationData($entity, $metadata);

    public function addTranslationData($entity, $metadata, Property $property, $data);

    public function getTranslationData($entity, $metadata, Property $property);

    public function storeValue($entity, Metadata $metadata, Property $property);

    public function delete($entity, Metadata $metadata);

    public function getTranslations($entity, Metadata $metadata, Property $property);

    public function updateReferences();

    public function translate($entity, Metadata $metadata, Property $property, $locale);

    public function normalizeTranslationData($data);

    public function normalizeFormData($data);
}