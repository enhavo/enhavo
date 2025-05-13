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

use Enhavo\Component\Type\TypeInterface;

interface VueRouteProviderTypeInterface extends TypeInterface
{
    public function getRoutes($options, string|array|null $groups = null): array;

    public function getRoute($options, $path, string|array|null $groups = null): ?VueRoute;
}
