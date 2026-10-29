<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Form\Transformer;

use Enhavo\Bundle\TranslationBundle\Form\Locale\FormLocaleResolverInterface;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Form\DataTransformerInterface;

class TranslationValueTransformer implements DataTransformerInterface
{
    /** @var TranslationManager */
    private $translationManager;

    /** @var FormLocaleResolverInterface */
    private $formLocaleResolver;

    /** @var string */
    private $property;

    /** @var object|string */
    private $data;

    /**
     * TranslationValueTransformer constructor.
     */
    public function __construct(TranslationManager $translationManager, FormLocaleResolverInterface $formLocaleResolver, object $data, string $property)
    {
        $this->translationManager = $translationManager;
        $this->formLocaleResolver = $formLocaleResolver;
        $this->property = $property;
        $this->data = $data;
    }

    public function transform($value)
    {
        $defaultLocale = $this->formLocaleResolver->getDefaultLocale();
        $data = [$defaultLocale => $value];

        $locales = array_diff($this->formLocaleResolver->getLocales(), [$defaultLocale]);
        if (empty($locales)) {
            return $data;
        }

        $translations = $this->translationManager->getTranslations($this->data, $this->property);
        foreach ($translations as $locale => $value) {
            if (!in_array($locale, $locales, true)) {
                continue;
            }
            $data[$locale] = $value;
        }

        return $data;
    }

    public function reverseTransform($value)
    {
        $data = $value;
        foreach ($data as $locale => $value) {
            if ($locale !== $this->formLocaleResolver->getDefaultLocale()) {
                $this->translationManager->setTranslation($this->data, $this->property, $locale, $value);
            }
        }

        return $data[$this->formLocaleResolver->getDefaultLocale()];
    }
}
