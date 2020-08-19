<?php
/**
 * LocalResolver.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Locale;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolver implements LocaleResolverInterface
{
    use ContainerAwareTrait;

    /**
     * @var string[]
     */
    private $locales;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack, $locales, $defaultLocale)
    {
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
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
        $this->locale = $this->defaultLocale;
        $request = $this->requestStack->getMasterRequest();
        if ($request !== null) {
            $locale = $request->attributes->get('_locale');
            if ($locale) {
                $this->locale = $locale;
            }
        }
    }
}
