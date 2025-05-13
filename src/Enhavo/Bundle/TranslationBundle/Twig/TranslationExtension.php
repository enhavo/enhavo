<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Twig;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslationExtension extends AbstractExtension
{
    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * TranslationExtension constructor.
     */
    public function __construct(TranslationManager $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('translation_default_locale', [$this, 'getDefaultLocale']),
            new TwigFunction('translation_locales', [$this, 'getLocales']),
            new TwigFunction('translation_property', [$this, 'getProperty']),
        ];
    }

    public function getDefaultLocale()
    {
        return $this->translationManager->getDefaultLocale();
    }

    public function getLocales()
    {
        return $this->translationManager->getLocales();
    }

    public function getProperty($resource, $property, $locale)
    {
        return $this->translationManager->getProperty($resource, $property, $locale);
    }
}
