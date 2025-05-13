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

class FixLocaleResolver implements LocaleResolverInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * FixLocaleResolver constructor.
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function resolve()
    {
        return $this->locale;
    }
}
