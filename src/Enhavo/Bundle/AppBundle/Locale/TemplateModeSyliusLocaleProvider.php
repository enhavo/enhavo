<?php

namespace Enhavo\Bundle\AppBundle\Locale;

use Sylius\Component\Locale\Provider\LocaleProviderInterface;

class TemplateModeSyliusLocaleProvider implements LocaleProviderInterface
{
    public function getAvailableLocalesCodes(): array
    {
        return [];
    }

    public function getDefaultLocaleCode(): string
    {
        return 'en';
    }
}
