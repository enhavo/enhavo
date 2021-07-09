<?php

namespace Enhavo\Bundle\TranslationBundle\Locale;

interface LocaleProviderInterface
{
    public function getLocales();

    public function getDefaultLocale();
}
