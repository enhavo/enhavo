<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property VueRouteProviderTypeInterface   $type
 * @property VueRouteProviderTypeInterface[] $parents
 */
class RouteProvider extends AbstractContainerType
{
    public function getRoutes(string|array|null $groups = null): array
    {
        return $this->type->getRoutes($this->options, $groups);
    }

    public function getRoute($path, string|array|null $groups = null): ?VueRoute
    {
        return $this->type->getRoute($this->options, $path, $groups);
    }
}
