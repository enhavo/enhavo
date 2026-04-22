<?php

namespace Enhavo\Bundle\ApiBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\ApiBundle\Documentation\DocumentationGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DocumentationDescriberCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $gridFactory = $container->findDefinition(DocumentationGenerator::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_api.documentation_describer') as $id => $tag) {
            $services[$id] = new Reference($id);
        }

        $gridFactory->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
