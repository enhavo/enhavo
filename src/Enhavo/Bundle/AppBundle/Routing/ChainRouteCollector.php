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

class ChainRouteCollector implements RouteCollectorInterface
{
    /** @var RouteCollectorInterface[] */
    private array $routeCollectors = [];

    public function addRouteCollector(RouteCollectorInterface $routeCollector): void
    {
        $this->routeCollectors[] = $routeCollector;
    }

    public function getRouteCollection(array|string|bool|null $groups = null): RouteCollection
    {
        $routeCollection = new RouteCollection();
        foreach ($this->routeCollectors as $collector) {
            $routeCollection->addCollection($collector->getRouteCollection($groups));
        }

        return $routeCollection;
    }
}
