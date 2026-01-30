<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FrameworkBundle\Twig;

use Enhavo\Bundle\FrameworkBundle\Locale\LocaleResolverInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class LocaleExtension extends AbstractExtension
{
    /**
     * @var LocaleResolverInterface
     */
    private $localResolver;

    /**
     * LocaleExtension constructor.
     */
    public function __construct(LocaleResolverInterface $localResolver)
    {
        $this->localResolver = $localResolver;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_locale', [$this, 'getLocale']),
        ];
    }

    public function getLocale()
    {
        return $this->localResolver->resolve();
    }
}
