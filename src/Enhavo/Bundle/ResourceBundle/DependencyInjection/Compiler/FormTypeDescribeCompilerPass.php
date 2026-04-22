<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\ResourceBundle\Form\FormDescriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormTypeDescribeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $gridFactory = $container->findDefinition(FormDescriber::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_resource.form_type_describer') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $gridFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
