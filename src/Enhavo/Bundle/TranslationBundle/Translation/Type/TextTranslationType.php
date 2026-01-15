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

use Enhavo\Bundle\TranslationBundle\Client\TranslationClientInterface;
use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class TextTranslationType extends AbstractTranslationType
{
    private PropertyAccessor $propertyAccessor;

    public function __construct(
        private TranslatorInterface $translator,
        private TranslationClientInterface $translationClient,
        private ?string $defaultLanguage,
    )
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function translate($object, string $property, string $locale, array $options): void
    {
        $this->translator->translate($object, $property, $locale, $options);
    }

    public function detach($object, string $property, string $locale, array $options): void
    {
        $this->translator->detach($object, $property, $locale, $options);
    }

    public function delete($object, string $property): void
    {
        $this->translator->delete($object, $property);
    }

    public function setTranslation(array $options, $data, string $property, string $locale, $value)
    {
        $this->translator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, string $property, string $locale)
    {
        return $this->translator->getTranslation($data, $property, $locale);
    }

    public function getDefaultValue(array $options, $data, string $property)
    {
        return $this->translator->getDefaultValue($data, $property);
    }

    public function autoTranslate($object, string $property, string $locale, array $options): void
    {
        $value = $this->propertyAccessor->getValue($object, $property);
        $translatedValue = $this->translator->getTranslation($object, $property, $locale);

        $isEmpty = $options['html'] ? empty(strip_tags($translatedValue)) : empty($translatedValue);
        if ($value && $isEmpty || $options['overwrite']) {
            $translatedValue = $this->translationClient->translate($value, $this->defaultLanguage, $locale, [
                'html' => $options['html'],
            ]);

            $this->translator->setTranslation($object, $property, $locale, $translatedValue);
        }
    }

    public static function getName(): ?string
    {
        return 'text';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_fallback' => false,
            'html' => false,
            'overwrite' => false,
        ]);
    }
}
