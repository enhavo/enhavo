<?php
/**
 * TranslationTypeInterface.php
 *
 * @since 25/08/19
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Enhavo\Component\Type\TypeInterface;

interface TranslationTypeInterface extends TypeInterface
{
    public function setTranslation(array $options, $data, $property, $locale, $value);

    public function getTranslation(array $options, $data, $property, $locale);

    public function getValidationConstraints(array $options, $data, $property, $locale);

    public function translate($object, $locale): void;

    public function detach($object);

    public function delete($object);
}
