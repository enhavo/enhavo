<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Behat\Extension;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Behat\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Enhavo\Bundle\AppBundle\Behat\Kernel\KernelAwareInitializer;
use Enhavo\Bundle\AppBundle\Behat\Kernel\KernelManager;
use Enhavo\Bundle\AppBundle\Behat\Kernel\Subscriber;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class KernelExtension implements ExtensionInterface
{
    public function getConfigKey(): string
    {
        return 'enhavo_kernel';
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('class')->defaultValue('App\Kernel')->end()
                ->scalarNode('environment')->defaultValue('test')->end()
                ->booleanNode('debug')->defaultTrue()->end()
                ->scalarNode('dotenv')->defaultNull()->end()
            ->end();
    }

    public function load(ContainerBuilder $container, array $config): void
    {
        $container->setDefinition(KernelManager::class, new Definition(KernelManager::class, [
            $config['class'],
            $config['environment'],
            $config['debug'],
            $config['dotenv'],
        ]));

        $definition = new Definition(KernelAwareInitializer::class, [new Reference(KernelManager::class)]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition(KernelAwareInitializer::class, $definition);

        $definition = new Definition(Subscriber::class, [new Reference(KernelManager::class)]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $container->setDefinition(Subscriber::class, $definition);
    }

    public function process(ContainerBuilder $container): void
    {
    }
}
