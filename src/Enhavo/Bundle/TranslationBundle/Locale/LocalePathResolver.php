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
use League\Uri\Components\HierarchicalPath;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalePathResolver implements LocaleResolverInterface
{
    /** @var string */
    private $locale;

    /** @var RequestStack */
    private $requestStack;

    /** @var LocaleProviderInterface */
    private $localeProvider;

    public function __construct(RequestStack $requestStack, LocaleProviderInterface $localeProvider)
    {
        $this->localeProvider = $localeProvider;
        $this->requestStack = $requestStack;
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
            $path = new HierarchicalPath($request->getPathInfo());
            $segment = $path->segments()[0];
            if (!empty($segment) && in_array($segment, $locales)) {
                $this->locale = $segment;
            }
        }
    }
}
