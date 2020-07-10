<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 00:17
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Enhavo\Component\Type\AbstractContainerType;

class Translation extends AbstractContainerType
{
    /** @var TranslationTypeInterface */
    protected $type;

    public function getTranslation($data, $property, $locale)
    {
        return $this->type->getTranslation($this->options, $data, $property, $locale);
    }

    public function setTranslation($data, $property, $locale, $value)
    {
        return $this->type->setTranslation($this->options, $data, $property, $locale, $value);
    }

    public function getValidationConstraints($data, $property, $locale)
    {
        return $this->type->getValidationConstraints($this->options, $data, $property, $locale);
    }
}
