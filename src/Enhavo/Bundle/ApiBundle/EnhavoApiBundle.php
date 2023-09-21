<?php

namespace Enhavo\Bundle\ApiBundle;

use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeExtensionInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Enhavo\Bundle\AppBundle\View\EndpointFactoryAwareInterface;
use Enhavo\Component\Type\TypeCompilerPass;
use Enhavo\Component\Type\TypeExtensionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoApiBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(
            new TypeCompilerPass('Endpoint', 'enhavo_api.endpoint', Endpoint::class)
        );

        $container->addCompilerPass(
            new TypeExtensionCompilerPass('Endpoint', 'enhavo_api.endpoint_extension')
        );

        $container->registerForAutoconfiguration(EndpointTypeInterface::class)
            ->addTag('enhavo_api.endpoint')
        ;

        $container->registerForAutoconfiguration(EndpointTypeExtensionInterface::class)
            ->addTag('enhavo_api.endpoint_extension')
        ;

        $container->registerForAutoconfiguration(EndpointFactoryAwareInterface::class)
            ->addMethodCall('setEndpointFactory', [new Reference('Enhavo\Component\Type\FactoryInterface[Endpoint]')])
        ;
    }
}
