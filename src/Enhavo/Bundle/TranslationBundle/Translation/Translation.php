<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 00:17
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property TranslationTypeInterface $type
 * @property TranslationTypeInterface[] $parents
 */
class Translation extends AbstractContainerType
{
    public function getTranslation($data, $property, $locale)
    {
        return $this->type->getTranslation($this->options, $data, $property, $locale);
    }

    public function setTranslation($data, $property, $locale, $value)
    {
        return $this->type->setTranslation($this->options, $data, $property, $locale, $value);
    }

    public function getDefaultValue($data, $property)
    {
        return $this->type->getDefaultValue($this->options, $data, $property);
    }

    public function translate($data, string $property, string $locale)
    {
        $this->type->translate($data, $property, $locale, $this->options);
    }

    public function detach($data, string $property, string $locale)
    {
        $this->type->detach($data, $property, $locale, $this->options);
    }

    public function delete($data, string $property)
    {
        $this->type->delete($data, $property);
    }
}
