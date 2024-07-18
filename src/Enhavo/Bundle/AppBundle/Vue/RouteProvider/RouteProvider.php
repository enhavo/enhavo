<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property VueRouteProviderTypeInterface $type
 * @property VueRouteProviderTypeInterface[] $parents
 */
class RouteProvider extends AbstractContainerType
{
    public function getRoutes(null|string|array $groups = null): array
    {
        return $this->type->getRoutes($this->options, $groups);
    }

    public function getRoute($path, null|string|array $groups = null): ?VueRoute
    {
        return $this->type->getRoute($this->options, $path, $groups);
    }
}
