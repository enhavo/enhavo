<?php

namespace Enhavo\Bundle\AppBundle\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait as BaseMicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

trait MicroKernelTrait
{
    use BaseMicroKernelTrait {
        configureRoutes as baseConfigureRoutes;
        configureContainer as baseConfigureContainer;
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

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $this->baseConfigureContainer($container, $loader, $builder);

        $configDir = $this->getConfigDir();

        $container->import($configDir.'/resources/*.{php,yaml}');
    }
}
