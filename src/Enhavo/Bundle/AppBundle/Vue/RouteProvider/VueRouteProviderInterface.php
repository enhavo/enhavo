<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

interface VueRouteProviderInterface
{
    public function getRoutes(null|string|array $groups = null): array;

    public function getRoute($path, null|string|array $groups = null): ?VueRoute;
}
