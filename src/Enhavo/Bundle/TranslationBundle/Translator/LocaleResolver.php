<?php
/**
 * LocalResolver.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Symfony\Component\HttpFoundation\RequestStack;

class LocaleResolver
{
    /**
     * @var string[]
     */
    protected $locales;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack, $locales, $defaultLocale)
    {
        foreach($locales as $locale => $data) {
            $this->locales[] = $locale;
        }
        $this->defaultLocale = $defaultLocale;
        $this->requestStack = $requestStack;
    }

    public function getLocale()
    {
        if($this->locale) {
            return $this->locale;
        }

        $request = $this->requestStack->getCurrentRequest();
        if($request === null) {
            $this->locale = $this->defaultLocale;
            return $this->locale;
        }

        if(preg_match('#^/admin/#', $request->getPathInfo())) {
            $this->locale = $this->defaultLocale;
            return $this->locale;
        }

        if(!in_array($request->getLocale(), $this->locales)) {
            $this->locale = $this->defaultLocale;
            return $this->locale;
        }

        $this->locale = $request->getLocale();
        return $this->locale;
    }
}