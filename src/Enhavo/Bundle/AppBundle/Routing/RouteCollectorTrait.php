<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Routing;

trait RouteCollectorTrait
{
    protected function inGroup($value, string|array|bool|null $groups): bool
    {
        if (null === $groups) {
            return true;
        } elseif (is_string($groups)) {
            $groups = [$groups];
        } elseif (is_bool($groups)) {
            $groups = [];
        }

        if (is_string($value)) {
            return in_array($value, $groups);
        } elseif (is_bool($value) && $value && empty($groups)) {
            return true;
        } elseif (is_array($value)) {
            foreach ($value as $singleValue) {
                if (in_array($singleValue, $groups)) {
                    return true;
                }
            }
        }

        return false;
    }
}
