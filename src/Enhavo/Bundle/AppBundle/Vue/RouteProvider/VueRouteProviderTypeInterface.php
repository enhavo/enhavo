<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

use Enhavo\Component\Type\TypeInterface;

interface VueRouteProviderTypeInterface extends TypeInterface
{
    public function getRoutes($options, null|string|array $groups = null): array;

    public function getRoute($options, $path, null|string|array $groups = null): ?VueRoute;
}
