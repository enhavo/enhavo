<?php

namespace Enhavo\Bundle\AppBundle\Routing;

trait RouteCollectorTrait
{
    protected function inGroup($value, null|string|array $groups): bool
    {
        if ($groups === null) {
            return true;
        } else if (is_string($groups)) {
            $groups = [$groups];
        }

        if (is_string($value)) {
            return in_array($value, $groups);
        } else if (is_bool($value) && empty($groups)) {
            return true;
        } else if (is_array($value)) {
            foreach($value as $singleValue) {
                if (in_array($singleValue, $groups)) {
                    return true;
                }
            }
        }

        return false;
    }
}
