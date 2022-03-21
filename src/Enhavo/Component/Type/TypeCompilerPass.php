<?php

/**
 * AbstractCollectorPass.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Component\Type;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class TypeCompilerPass implements CompilerPassInterface
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $tagName;

    /** @var string */
    private $class;

    /** @var string */
    private $alias;

    public function __construct($namespace, $tagName, $class)
    {
        $this->namespace = $namespace;
        $this->tagName = $tagName;
        $this->class = $class;
    }

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $this->createRegistryDefinition();
        $factoryDefinition = $this->createFactoryDefinition( $this->class, $registryDefinition);

        $container->addDefinitions([
            sprintf('%s[%s]', RegistryInterface::class, $this->namespace) => $registryDefinition,
            sprintf('%s[%s]', FactoryInterface::class, $this->namespace) => $factoryDefinition,
        ]);


        $taggedServices = $container->findTaggedServiceIds($this->tagName);

        foreach ($taggedServices as $id => $tagAttributes) {
            $tagServiceDefinition = $container->getDefinition($id);
            $tagServiceDefinition->setPublic(true);
            $registryDefinition->addMethodCall(
                'register',
                array($tagServiceDefinition->getClass() ? $tagServiceDefinition->getClass(): $id, $id)
            );
        }
    }

    private function createRegistryDefinition(): Definition
    {
        $definition = new Definition();
        $definition->setClass(Registry::class);
        $definition->setArguments([$this->namespace]);
        $definition->addMethodCall('setContainer', [new Reference('service_container')]);
        return $definition;
    }

    private function createFactoryDefinition($class, $registryDefinition): Definition
    {
        $definition = new Definition();
        $definition->setClass(Factory::class);
        $definition->setArguments([$class, $registryDefinition]);
        return $definition;
    }
}
