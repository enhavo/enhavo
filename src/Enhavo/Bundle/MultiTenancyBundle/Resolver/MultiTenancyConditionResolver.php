<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.08.18
 * Time: 18:23
 */

namespace Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\RoutingBundle\Condition\ConditionResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MultiTenancyConditionResolver implements ConditionResolverInterface
{
    /**
     * @var MultiTenancyResolver
     */
    private $resolver;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(MultiTenancyResolver $resolver, RequestStack $requestStack)
    {
        $this->resolver = $resolver;
        $this->requestStack = $requestStack;
    }

    public function resolve()
    {
        if(!$this->resolver->isResolved()) {
            $this->resolver->resolve($this->requestStack->getCurrentRequest());
        }
        return $this->resolver->getKey();
    }
}
