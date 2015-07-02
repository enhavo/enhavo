<?php

namespace esperanto\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class RouteContentCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('esperanto_admin.route_content_collector')) {
            return;
        }

        $definition = $container->getDefinition(
            'esperanto_admin.route_content_collector'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'esperanto_route_content_loader'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'add',
                    array(new Reference($id))
                );
            }
        }
    }
} 