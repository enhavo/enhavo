<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class ExposeRouteCollector implements RouteCollectorInterface
{
    use RouteCollectorTrait;

    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function getRouteCollection(string|array|bool|null $groups = null): RouteCollection
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
