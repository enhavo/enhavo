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
use Enhavo\Bundle\AppBundle\Behat\Client\ClientAwareInitializer;
use Enhavo\Bundle\AppBundle\Behat\Client\ClientManager;
use Enhavo\Bundle\AppBundle\Behat\Client\Subscriber;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class PantherExtension implements ExtensionInterface
{
    public function getConfigKey()
    {
        return 'panther';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('env')->end()
            ->end();
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $this->buildParameters($container, $config);
        $this->loadClientManager($container);
        $this->loadContextInitializer($container);
        $this->loadSubscriber($container);
    }

    private function buildParameters(ContainerBuilder $container, $config)
    {
        $env = is_array($config['env']) ? $config['env'] : [];
        $container->setParameter('panther.env', $env);

        foreach ($env as $name => $value) {
            if (!isset($_SERVER[$name])) {
                $_SERVER[$name] = $value;
            }
        }
    }

    public function process(ContainerBuilder $container)
    {
    }

    private function loadClientManager(ContainerBuilder $container)
    {
        $definition = new Definition(ClientManager::class);
        $container->setDefinition(ClientManager::class, $definition);
    }

    private function loadContextInitializer(ContainerBuilder $container)
    {
        $definition = new Definition(ClientAwareInitializer::class, [
            new Reference(ClientManager::class),
        ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);
        $container->setDefinition(ClientAwareInitializer::class, $definition);
    }

    private function loadSubscriber(ContainerBuilder $container)
    {
        $definition = new Definition(Subscriber::class, [
            new Reference(ClientManager::class),
        ]);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $container->setDefinition(Subscriber::class, $definition);
    }
}
