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

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolver implements LocaleResolverInterface
{
    /** @var string */
    private $locale;

    /** @var RequestStack */
    private $localeProvider;

    /** @var RequestStack */
    private $requestStack;

    public function __construct(RequestStack $requestStack, LocaleProviderInterface $localeProvider)
    {
        $this->requestStack = $requestStack;
        $this->localeProvider = $localeProvider;
    }

    public function resolve()
    {
        if ($this->locale) {
            return $this->locale;
        }

        $this->resolveLocale();

        return $this->locale;
    }

    private function resolveLocale()
    {
        $locales = $this->localeProvider->getLocales();
        $this->locale = $this->localeProvider->getDefaultLocale();
        $request = $this->requestStack->getMainRequest();
        if (null !== $request) {
            $locale = $request->attributes->get('_locale');
            if ($locale && in_array($locale, $locales)) {
                $this->locale = $locale;
            }
        }
    }
}
