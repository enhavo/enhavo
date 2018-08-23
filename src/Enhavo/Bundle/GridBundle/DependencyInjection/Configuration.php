<?php

namespace Enhavo\Bundle\GridBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_grid');

        $rootNode
            ->children()

                ->arrayNode('render')
                    ->children()
                        ->arrayNode('sets')
                        ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable_columns')->defaultValue(true)->end()
                        ->scalarNode('enable_items')->defaultValue(true)->end()
                    ->end()
                ->end()

                ->arrayNode('items')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')->end()
                            ->scalarNode('form')->end()
                            ->scalarNode('repository')->end()
                            ->scalarNode('template')->end()
                            ->scalarNode('form_template')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('translationDomain')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('factory')->end()
                            ->arrayNode('options')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('groups')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                 ->end()

            ->end();

        return $treeBuilder;
    }
}
