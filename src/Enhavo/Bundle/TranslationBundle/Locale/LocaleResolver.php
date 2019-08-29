<?php
/**
 * LocalResolver.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use League\Uri\Components\HierarchicalPath;

class LocaleResolver
{
    use ContainerAwareTrait;

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

        $this->resolveLocale();
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function resolveLocale()
    {
        $this->setLocale($this->defaultLocale);

        $request = $this->requestStack->getMasterRequest();
        if($request !== null) {
            $path = new HierarchicalPath($request->getPathInfo());
            $segment = $path->getSegment(0);
            if(!empty($segment) && in_array($segment, $this->locales)) {
                $this->setLocale($segment);
            }
        }
    }

    public function isDefaultLocale()
    {
        return $this->getLocale() === $this->defaultLocale;
    }
}