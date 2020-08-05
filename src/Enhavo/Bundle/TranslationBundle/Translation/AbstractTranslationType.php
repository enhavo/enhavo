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

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        $this->parent->setTranslation($options, $data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        $this->parent->getTranslation($options, $data, $property, $locale);
    }

    public function getValidationConstraints(array $options, $data, $property, $locale)
    {
        $this->parent->getValidationConstraints($options, $data, $property, $locale);
    }

    public static function getParentType(): ?string
    {
        return TranslationType::class;
    }
}
