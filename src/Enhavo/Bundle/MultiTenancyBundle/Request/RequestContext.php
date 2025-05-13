<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Request;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

/**
 * We want to use the tenant in static routes condition. Unlike in cmf routing, the static routes getting compiled to php
 * for faster execution. So it is hard to inject further variables to the condition language expression. Thus, we abuse
 * the request context to inject our resolver to make it available in any static routing condition expression.
 */
class RequestContext extends \Symfony\Component\Routing\RequestContext
{
    private ?ResolverInterface $resolver;

    public function getTenant(): ?string
    {
        return $this->resolver->getTenant()?->getKey();
    }

    public function getResolver(): ?ResolverInterface
    {
        return $this->resolver;
    }

    public function setResolver(?ResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }
}
