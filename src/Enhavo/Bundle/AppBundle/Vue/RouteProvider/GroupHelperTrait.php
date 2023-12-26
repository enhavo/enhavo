<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

trait GroupHelperTrait
{
    public function isGroupSelected($options, $group): bool
    {
        if ($options['groups'] === null && $group === null) {
            return true;
        }

        $groups = match (gettype($options['groups'])) {
            'array' => $options['groups'],
            'string' => [$options['groups']],
            default => [],
        };

        $targetGroups = match (gettype($group)) {
            'array' => $group,
            'string' => [$group],
            default => [],
        };

        foreach ($targetGroups as $targetGroup) {
            if (in_array($targetGroup, $groups)) {
                return true;
            }
        }

        return false;
    }
}
