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
    public function setTranslation(array $options, $data, string $property, string $locale, $value);

    public function getTranslation(array $options, $data, string $property, string $locale);

    public function getDefaultValue(array $options, $data, string $property);

    public function translate($object, string $property, string $locale, array $options): void;

    public function detach($object, string $property, string $locale, array $options);

    public function delete($object, string $property);
}
