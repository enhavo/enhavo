<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    private ?RequestContext $context = null;

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    private function init(): void
    {
        if (null !== $this->generator) {
            return;
        }

        if (null === $this->context) {
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

        return (bool) $this->routes->get($name);
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
