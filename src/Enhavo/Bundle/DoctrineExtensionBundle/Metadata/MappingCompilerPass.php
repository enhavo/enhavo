<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 22:25
 */

namespace Enhavo\Component\DoctrineExtension\Mapping;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MappingCompilerPass implements CompilerPassInterface
{
    const TAG_NAME = 'doctrine.mapping_extension';

    /** @var string */
    private $service;

    /** @var string[] */
    private $extensions;

    public function __construct(array $extensions = [], $service = 'doctrine.orm.default_metadata_driver')
    {
        $this->service = $service;
        $this->extensions = $extensions;
    }

    public function process(ContainerBuilder $container)
    {
        $this->addExtensions($container);
        $this->addExtensionRegistry($container);
        $this->addTaggedExtensionToRegistry($container);
        $this->addMappingExtensionDriver($container);
    }

    private function addExtensions(ContainerBuilder $container)
    {
        foreach ($this->extensions as $extension) {
            $definition = new Definition();
            $definition->setClass($extension);
            $definition->addTag(self::TAG_NAME);

            $container->addDefinitions([
                $extension => $definition
            ]);
        }
    }

    private function addExtensionRegistry(ContainerBuilder $container)
    {
        $definition = new Definition();
        $definition->setClass(ExtensionRegistry::class);

        $container->addDefinitions([
            ExtensionRegistry::class => $definition
        ]);
    }

    private function addTaggedExtensionToRegistry(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(self::TAG_NAME);
        $registryDefinition = $container->getDefinition(ExtensionRegistry::class);

        foreach ($taggedServices as $id => $tagAttributes) {
            $tagServiceDefinition = $container->getDefinition($id);
            $registryDefinition->addMethodCall(
                'register',
                array($tagServiceDefinition)
            );
        }
    }

    private function addMappingExtensionDriver(ContainerBuilder $container)
    {
        $renamedService = sprintf('%s.inner', $this->service);
        $definition = new Definition();
        $definition->setClass(MappingExtensionDriver::class);
        $definition->setDecoratedService($this->service, $renamedService);
        $definition->addArgument(new Reference($renamedService));
        $definition->addArgument(new Reference(ExtensionRegistry::class));

        $container->addDefinitions([
            MappingExtensionDriver::class => $definition
        ]);
    }
}
