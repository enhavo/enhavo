<?php

namespace Enhavo\Bundle\AppBundle\Routing;

use Symfony\Component\Routing\RouteCollection;

interface RouteCollectorInterface
{
    public function getRouteCollection(null|string|array $groups = null): RouteCollection;
}
