<?php

namespace Enhavo\Bundle\AppBundle\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class ExposeRouteCollector implements RouteCollectorInterface
{
    use RouteCollectorTrait;

    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function getRouteCollection(null|string|array|bool $groups = null): RouteCollection
    {
        $routes = $this->router->getRouteCollection();

        $routeCollection = new RouteCollection();
        foreach ($routes as $key => $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_expose']) && $this->inGroup($defaults['_expose'], $groups)) {
                $routeCollection->add($key, $route);
            }

        }

        return $routeCollection;
    }
}
