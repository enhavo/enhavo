<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return $this->resolver->getTenant()->getKey();
    }
}
