<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class TwigRouter
{
    private UrlGenerator $generator;
    private RouteCollection $routes;

    public function __construct(RequestStack $requestStack)
    {
        $context = new RequestContext();
        $context->fromRequest($requestStack->getCurrentRequest());
        $this->routes = new RouteCollection();
        $this->generator = new UrlGenerator($this->routes, $context);
    }

    public function generate(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        return $this->generator->generate($name, $parameters, $referenceType);
    }

    public function addRouteCollection(RouteCollection $routeCollection)
    {
        $this->routes->addCollection($routeCollection);
    }
}
