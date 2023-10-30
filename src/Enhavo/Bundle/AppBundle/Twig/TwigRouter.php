<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class TwigRouter
{
    private ?UrlGenerator $generator = null;
    private ?RouteCollection $routes = null;

    public function __construct(
        private readonly RequestStack $requestStack
    )
    {
    }

    private function init(): void
    {
        if ($this->generator !== null) {
            return;
        }

        $context = new RequestContext();

        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $context->fromRequest($request);
        }

        $this->routes = new RouteCollection();
        $this->generator = new UrlGenerator($this->routes, $context);
    }

    public function generate(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        $this->init();
        return $this->generator->generate($name, $parameters, $referenceType);
    }

    public function addRouteCollection(RouteCollection $routeCollection): void
    {
        $this->init();
        $this->routes->addCollection($routeCollection);
    }
}
