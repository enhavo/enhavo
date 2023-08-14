<?php

namespace Enhavo\Bundle\RoutingBundle\Request;

use Enhavo\Bundle\RoutingBundle\Condition\ConditionResolverInterface;

/**
 * We want to use the resolver in static routes. Unlike in cmf routing, the static routes getting compiled to php
 * for faster execution. So it is hard to inject further variables to the condition language expression. Thus, we abuse
 * the request context to inject our condition resolver to make it available in any static routing condition expression.
 */
class RequestContext extends \Symfony\Component\Routing\RequestContext
{
    private ?ConditionResolverInterface $resolver;

    public function resolve()
    {
        return $this->resolver->resolve();
    }

    public function getResolver(): ?ConditionResolverInterface
    {
        return $this->resolver;
    }

    public function setResolver(?ConditionResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }
}
