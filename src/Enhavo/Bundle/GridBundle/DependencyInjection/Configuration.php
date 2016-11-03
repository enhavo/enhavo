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
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
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
                            ->scalarNode('label')->end()
                            ->scalarNode('translationDomain')->end()
                            ->scalarNode('type')->end()
                            ->scalarNode('parent')->end()
                            ->scalarNode('factory')->end()
                            ->arrayNode('options')->end()
                        ->end()
                    ->end()
                 ->end()

            ->end();

        return $treeBuilder;
    }
}
