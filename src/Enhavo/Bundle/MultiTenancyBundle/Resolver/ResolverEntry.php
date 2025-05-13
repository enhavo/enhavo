<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

class ResolverEntry
{
    /** @var int */
    private $priority;

    /** @var ResolverInterface */
    private $resolver;

    /**
     * ResolverEntry constructor.
     */
    public function __construct(ResolverInterface $resolver, int $priority = 100)
    {
        $this->priority = $priority;
        $this->resolver = $resolver;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getResolver(): ResolverInterface
    {
        return $this->resolver;
    }
}
