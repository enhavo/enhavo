<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver;

class ChainResolverEntry
{
    /**
     * @var EntityResolverInterface
     */
    private $resolver;

    /**
     * @var int
     */
    private $priority;

    /**
     * ChainResolverEntry constructor.
     */
    public function __construct(EntityResolverInterface $resolver, int $priority)
    {
        $this->resolver = $resolver;
        $this->priority = $priority;
    }

    public function getResolver(): EntityResolverInterface
    {
        return $this->resolver;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
