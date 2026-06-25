<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ModelTranslationType extends AbstractTranslationType
{
    private TranslationManager $translationManager;

    public function setTranslationManager(TranslationManager $translationManager): void
    {
        $this->translationManager = $translationManager;
    }

    public function translate($object, string $property, string $locale, array $options): void
    {

    }

    public function detach($object, string $property, string $locale, array $options): void
    {

    }

    public function delete($object, string $property): void
    {

    }

    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {

    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {

    }

    public function getDefaultValue(array $options, $data, string $property)
    {

    }

    public function autoTranslate($object, string $property, string $locale, mixed $context, array $options): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $value = $propertyAccessor->getValue($object, $property);
        if (is_iterable($value)) {
            foreach ($value as $item) {
                $this->translationManager->applyAutoTranslation($item, $locale, null, $context);
            }
        } elseif (is_object($value)) {
            $this->translationManager->applyAutoTranslation($value, $locale, null, $context);
        }
    }

    public function isFormTranslatable($object, string $property, array $options): bool
    {
        return false;
    }

    public static function getName(): ?string
    {
        return 'model';
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
