<?php

namespace Enhavo\Bundle\AppBundle\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait as BaseMicroKernelTrait;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

trait MicroKernelTrait
{
    use BaseMicroKernelTrait {
        configureRoutes as baseConfigureRoutes;
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        // load only template routes in template environment
        if ($this->environment === 'template') {
            $configDir = $this->getConfigDir();
            $routes->import($configDir.'/{routes}/template/*.{php,yaml}');
        } else {
            $this->baseConfigureRoutes($routes);
        }
    }
}
