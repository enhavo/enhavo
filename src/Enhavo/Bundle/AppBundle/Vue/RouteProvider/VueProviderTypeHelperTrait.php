<?php

namespace Enhavo\Bundle\AppBundle\Vue\RouteProvider;

trait VueProviderTypeHelperTrait
{
    protected function isGroupSelected($options, $group): bool
    {
        if ($options['groups'] === null && $group === null) {
            return true;
        }

        if (isset($options['groups']) && $options['groups'] === true) {
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
        $path = preg_replace_callback('/\{([0-9A-Za-z_-]+)\}/', function($matches) {
            return ':' . $matches[1];
        }, $path);

        return $path;
    }
}
