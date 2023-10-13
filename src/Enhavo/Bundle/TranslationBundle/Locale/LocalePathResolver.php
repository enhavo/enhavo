<?php
/**
 * LocalResolver.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Locale;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use League\Uri\Components\HierarchicalPath;

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
        if ($request !== null) {
            $path = new HierarchicalPath($request->getPathInfo());
            $segment = $path->segments()[0];
            if (!empty($segment) && in_array($segment, $locales)) {
                $this->locale = $segment;
            }
        }
    }
}
