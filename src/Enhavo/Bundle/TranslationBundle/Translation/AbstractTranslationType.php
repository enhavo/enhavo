<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use Enhavo\Component\Type\AbstractType;

abstract class AbstractTranslationType extends AbstractType implements TranslationTypeInterface
{
    /** @var TranslationTypeInterface */
    protected $parent;

    public function translate($object, string $property, string $locale, array $options): void
    {
        $this->parent->translate($object, $property, $locale, $options);
    }

    public function detach($object, string $property, string $locale, array $options): void
    {
        $this->parent->detach($object, $property, $locale, $options);
    }

    public function delete($object, string $property): void
    {
        $this->parent->delete($object, $property);
    }

    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {
        $this->parent->setTranslation($options, $data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {
        return $this->parent->getTranslation($options, $data, $property, $locale);
    }

    public function getDefaultValue(array $options, $data, string $property)
    {
        return $this->parent->getDefaultValue($options, $data, $property);
    }

    public static function getParentType(): ?string
    {
        return TranslationType::class;
    }
}
