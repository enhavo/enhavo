<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class TwigRouter implements RouterInterface
{
    private ?UrlGenerator $generator = null;
    private ?RouteCollection $routes = null;
    private ?RequestContext $context;

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

        if ($this->context === null) {
            $this->context = new RequestContext();
            $request = $this->requestStack->getCurrentRequest();
            if ($request) {
                $this->context->fromRequest($request);
            }
        }

        $this->routes = new RouteCollection();
        $this->generator = new UrlGenerator($this->routes, $this->context);
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $this->init();
        return $this->generator->generate($name, $parameters, $referenceType);
    }

    public function addRouteCollection(RouteCollection $routeCollection): void
    {
        $this->init();
        $this->routes->addCollection($routeCollection);
    }

    public function getRouteCollection(): RouteCollection
    {
        $this->init();
        return $this->routes;
    }

    public function exists($name): bool
    {
        $this->init();
        return !!$this->routes->get($name);
    }

    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    public function getContext(): RequestContext
    {
        return $this->context;
    }

    public function match(string $pathinfo): array
    {
        $routes = [];

        foreach ($this->getRouteCollection() as $route) {
            if (str_contains($pathinfo, $route->getPath())) {
                $routes[] = $route;
            }
        }

        return $routes;
    }
}
