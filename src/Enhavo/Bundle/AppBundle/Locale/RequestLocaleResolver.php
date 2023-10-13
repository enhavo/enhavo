<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-26
 * Time: 00:54
 */

namespace Enhavo\Bundle\AppBundle\Locale;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestLocaleResolver implements LocaleResolverInterface
{
    /**
     * @var string
     */
    private $requestStack;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * FixLocaleResolver constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, $defaultLocale = 'en')
    {
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
    }

    public function resolve()
    {
        $locale = $this->requestStack->getMainRequest()->getLocale();

        if($locale === null) {
            return $this->defaultLocale;
        }

        return $locale;
    }
}
