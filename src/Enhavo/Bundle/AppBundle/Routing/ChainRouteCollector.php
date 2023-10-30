<?php

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

    public function getRouteCollection(array|string|null $groups = null): RouteCollection
    {
        $routeCollection = new RouteCollection();
        foreach ($this->routeCollectors as $collector) {
            $routeCollection->addCollection($collector->getRouteCollection($groups));
        }
        return $routeCollection;
    }
}
