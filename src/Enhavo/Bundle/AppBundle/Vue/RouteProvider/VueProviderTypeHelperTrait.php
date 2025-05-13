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

trait VueProviderTypeHelperTrait
{
    protected function isGroupSelected($options, $group): bool
    {
        if (null === $options['groups'] && null === $group) {
            return true;
        }

        if (isset($options['groups']) && true === $options['groups']) {
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

    /**
     * @param VueRoute[] $routes
     */
    protected function search(string $path, array $routes): ?VueRoute
    {
        foreach ($routes as $route) {
            if ($route->getPath() === $path) {
                return $route;
            }

            if (count($route->getChildren()) > 0) {
                return $this->search($path, $route->getChildren());
            }
        }

        return null;
    }

    protected function convertPath($path)
    {
        $path = preg_replace_callback('/\{([0-9A-Za-z_-]+)\}/', function ($matches) {
            return ':'.$matches[1];
        }, $path);

        return $path;
    }
}
