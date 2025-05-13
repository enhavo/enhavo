<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(RequestStack $requestStack, $defaultLocale = 'en')
    {
        $this->requestStack = $requestStack;
        $this->defaultLocale = $defaultLocale;
    }

    public function resolve()
    {
        $locale = $this->requestStack->getMainRequest()->getLocale();

        if (null === $locale) {
            return $this->defaultLocale;
        }

        return $locale;
    }
}
