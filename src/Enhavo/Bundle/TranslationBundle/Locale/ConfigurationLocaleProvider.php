<?php

namespace Enhavo\Bundle\TranslationBundle\Locale;

class ConfigurationLocaleProvider implements LocaleProviderInterface
{
    /** @var string[] */
    private $locales;

    /** @var string */
    private $defaultLocale;

    /**
     * ConfigurationLocalProvider constructor.
     * @param string[] $locales
     * @param string $defaultLocale
     */
    public function __construct(array $locales, string $defaultLocale)
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }
}
