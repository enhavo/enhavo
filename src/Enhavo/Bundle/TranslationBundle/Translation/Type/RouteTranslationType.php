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
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteTranslationType extends AbstractTranslationType
{
    /** @var TranslatorInterface */
    protected $translator;

    /**
     * TextTranslationType constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
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

    public static function getName(): ?string
    {
        return 'route';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_null' => false,
        ]);
    }
}
