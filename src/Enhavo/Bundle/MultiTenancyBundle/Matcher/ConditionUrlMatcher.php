<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.08.18
 * Time: 00:30
 */

namespace Enhavo\Bundle\RoutingBundle\Matcher;

use Enhavo\Bundle\MultiTenancyBundle\Request\RequestContext;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Symfony\Cmf\Component\Routing\NestedMatcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class ConditionUrlMatcher extends UrlMatcher
{
    public function __construct(
        RouteCollection $routes,
        RequestContext $context,
        private ResolverInterface $resolver,
    ){
        parent::__construct($routes, $context);
    }

    public function finalMatch(RouteCollection $collection, Request $request)
    {
        $this->routes = $collection;
        $context = new RequestContext();
        $context->fromRequest($request);
        $context->setResolver($this->resolver);
        $this->setContext($context);

        return $this->match($request->getPathInfo());
    }
}
