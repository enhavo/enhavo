<?php

namespace Enhavo\Bundle\AppBundle\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class ExposeRouteCollector implements RouteCollectorInterface
{
    public function __construct(
        private RouterInterface $router,
    )
    {
    }

    public function getRouteCollection(null|string|array $groups): RouteCollection
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

    private function inGroup($value, null|string|array $groups): bool
    {
        if ($groups === null) {
            return true;
        } else if (is_string($groups)) {
            $groups = [$groups];
        }

        if (is_string($value)) {
            return in_array($value, $groups);
        } else if (is_bool($value) && empty($groups)) {
            return true;
        } else if (is_array($value)) {
            foreach($value as $singleValue) {
                if (in_array($singleValue, $groups)) {
                    return true;
                }
            }
        }

        return false;
    }
}
