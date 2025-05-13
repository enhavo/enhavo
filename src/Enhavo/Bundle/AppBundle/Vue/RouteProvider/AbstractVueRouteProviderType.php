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

use Enhavo\Component\Type\AbstractType;

abstract class AbstractVueRouteProviderType extends AbstractType implements VueRouteProviderTypeInterface
{
    public function getRoutes($options, array|string|null $groups = null): array
    {
        return $this->parent->getRoutes($options, $groups);
    }

    public function getRoute($options, $path, array|string|null $groups = null): ?VueRoute
    {
        return $this->parent->getRoute($options, $groups);
    }
}
