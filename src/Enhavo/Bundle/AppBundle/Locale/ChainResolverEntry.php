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

class ChainResolverEntry
{
    /** @var int */
    private $priority;

    /** @var LocaleResolverInterface */
    private $resolver;

    /**
     * ResolverEntry constructor.
     */
    public function __construct(LocaleResolverInterface $resolver, int $priority = 100)
    {
        $this->priority = $priority;
        $this->resolver = $resolver;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getResolver(): LocaleResolverInterface
    {
        return $this->resolver;
    }
}
