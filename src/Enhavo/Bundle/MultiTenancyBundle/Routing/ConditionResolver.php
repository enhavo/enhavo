<?php

namespace Enhavo\Bundle\MultiTenancyBundle\Routing;

use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Enhavo\Bundle\RoutingBundle\Condition\ConditionResolverInterface;

class ConditionResolver implements ConditionResolverInterface
{
    /** @var ResolverInterface */
    private $resolver;

    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    public function resolve()
    {
        $this->resolver->getTenant()->getKey();
    }
}
