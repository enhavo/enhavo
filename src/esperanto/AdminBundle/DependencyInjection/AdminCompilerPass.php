<?php

namespace esperanto\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;


class AdminCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('esperanto_admin.admin_register')) {
            return;
        }

        $definition = $container->getDefinition(
            'esperanto_admin.admin_register'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'esperanto_admin.admin'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'registerAdmin',
                    array(new Reference($id))
                );
            }
        }
    }
} 