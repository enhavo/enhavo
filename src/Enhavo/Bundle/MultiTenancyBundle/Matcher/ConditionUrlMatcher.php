<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Matcher;

use Enhavo\Bundle\MultiTenancyBundle\Request\RequestContext as EnhavoRequestContext;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;
use Symfony\Cmf\Component\Routing\NestedMatcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;
use Symfony\Component\Routing\RouteCollection;

class ConditionUrlMatcher extends UrlMatcher
{
    public function __construct(
        RouteCollection $routes,
        SymfonyRequestContext $context,
        private ResolverInterface $resolver,
    ) {
        parent::__construct($routes, $context);
    }

    public function finalMatch(RouteCollection $collection, Request $request): array
    {
        $this->routes = $collection;
        $context = new EnhavoRequestContext();
        $context->fromRequest($request);
        $context->setResolver($this->resolver);
        $this->setContext($context);

        return $this->match($request->getPathInfo());
    }
}
