<?php

/**
 * TranslationLocaleProvider.php
 *
 * @since 15/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Provider;

use Sylius\Component\Resource\Provider\LocaleProviderInterface;

class TranslationLocaleProvider implements LocaleProviderInterface
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function getCurrentLocale()
    {
        return $this->locale;
    }

    public function getFallbackLocale()
    {
        return $this->locale;
    }

    public function getDefaultLocale()
    {
        return $this->locale;
    }
}