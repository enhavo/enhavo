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

interface VueRouteProviderInterface
{
    /** @return VueRoute[] */
    public function getRoutes(string|array|null $groups = null): array;

    public function getRoute($path, string|array|null $groups = null): ?VueRoute;
}
