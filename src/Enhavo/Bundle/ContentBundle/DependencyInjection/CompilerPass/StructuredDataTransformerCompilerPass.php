<?php

namespace Enhavo\Bundle\ContentBundle\DependencyInjection\CompilerPass;

use Enhavo\Bundle\ContentBundle\StructuredData\StructuredDataTransformer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class StructuredDataTransformerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $transformer = $container->findDefinition(StructuredDataTransformer::class);

        $services = [];
        foreach ($container->findTaggedServiceIds('enhavo_content.structured_data_transformer') as $id => $tag) {
            $transformerDefinition = $container->findDefinition($id);
            $class = $transformerDefinition->getClass();
            $method = 'getName';
            $services[$class] = new Reference($id);
            $name = $class::$method();
            if ($name !== null) {
                $services[$name] = new Reference($id);
            }
            $services[$transformerDefinition->getClass()] = new Reference($id);
        }

        $transformer->addMethodCall('setContainer', [ServiceLocatorTagPass::register($container, $services)]);
    }
}
