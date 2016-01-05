<?php

namespace Enhavo\Bundle\ContactBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_contact');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->arrayNode('contact')
                    ->children()
                        ->scalarNode('model')->end()
                        ->scalarNode('form')->end()
                            ->arrayNode('template')
                                ->children()
                                    ->scalarNode('form')->end()
                                    ->scalarNode('recipient')->end()
                                    ->scalarNode('sender')->end()
                                ->end()
                            ->end()
                        ->scalarNode('recipient')->end()
                        ->scalarNode('from')->end()
                        ->scalarNode('subject')->end()
                        ->scalarNode('send_to_sender')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
