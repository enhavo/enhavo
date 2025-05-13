<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Locale;

class ConfigurationLocaleProvider implements LocaleProviderInterface
{
    /** @var string[] */
    private $locales;

    /** @var string */
    private $defaultLocale;

    /**
     * ConfigurationLocalProvider constructor.
     *
     * @param string[] $locales
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
