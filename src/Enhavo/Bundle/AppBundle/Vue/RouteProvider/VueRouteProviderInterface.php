<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

interface VueRouteProviderInterface
{
    /** @return VueRoute[] */
    public function getRoutes(null|string|array $groups = null): array;

    public function getRoute($path, null|string|array $groups = null): ?VueRoute;
}
