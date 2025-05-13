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

use Enhavo\Component\Type\FactoryInterface;

class ChainVueRouteProvider implements VueRouteProviderInterface
{
    /** @var RouteProvider[] */
    private ?array $providers = null;

    public function __construct(
        private array $config,
        private FactoryInterface $factory,
    ) {
    }

    public function getRoutes(array|string|null $groups = null): array
    {
        $this->init();

        $routes = [];

        foreach ($this->providers as $provider) {
            foreach ($provider->getRoutes($groups) as $route) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

    public function getRoute($path, array|string|null $groups = null): ?VueRoute
    {
        $this->init();

        foreach ($this->providers as $provider) {
            $route = $provider->getRoute($path, $groups);
            if (null !== $route) {
                return $route;
            }
        }

        return null;
    }

    private function init(): void
    {
        if (null !== $this->providers) {
            return;
        }

        $this->providers = [];
        foreach ($this->config as $providerConfig) {
            $this->providers[] = $this->factory->create($providerConfig);
        }
    }
}
