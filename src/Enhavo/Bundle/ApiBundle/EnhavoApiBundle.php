<?php

namespace Enhavo\Bundle\ApiBundle;

use Enhavo\Bundle\ApiBundle\DependencyInjection\CompilerPass\DataNormalizerCompilerPass;
use Enhavo\Bundle\ApiBundle\Endpoint\Endpoint;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeExtensionInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointTypeInterface;
use Enhavo\Bundle\ApiBundle\Endpoint\EndpointFactoryAwareInterface;
use Enhavo\Bundle\ApiBundle\Normalizer\DataNormalizerInterface;
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
            new TypeExtensionCompilerPass('Endpoint', 'enhavo_api.endpoint_extension'),
        );

        $container->addCompilerPass(new DataNormalizerCompilerPass());

        $container->registerForAutoconfiguration(EndpointTypeInterface::class)
            ->addTag('enhavo_api.endpoint')
        ;

        $container->registerForAutoconfiguration(EndpointTypeExtensionInterface::class)
            ->addTag('enhavo_api.endpoint_extension')
        ;

        $container->registerForAutoconfiguration(EndpointFactoryAwareInterface::class)
            ->addMethodCall('setEndpointFactory', [new Reference('Enhavo\Component\Type\FactoryInterface[Endpoint]')])
        ;

        $container->registerForAutoconfiguration(DataNormalizerInterface::class)
            ->addTag('enhavo_api.data_normalizer')
        ;
    }
}
