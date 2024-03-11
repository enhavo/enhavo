<?php

namespace Enhavo\Bundle\SearchBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('enhavo_search');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('doctrine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable_database')->defaultValue(true)->end()
                    ->end()
                ->end()
                ->arrayNode('index')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('classes')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('search')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('dsn')->defaultValue(null)->end()
                        ->scalarNode('template')->defaultValue('EnhavoSearchBundle:Search:render.html.twig')->end()
                    ->end()
                ->end()
                ->arrayNode('elastic')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('version')->defaultValue('7.0.0-darwin-x86_64')->end()
                    ->end()
                ->end()
                ->arrayNode('metadata')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('filter')
                                ->useAttributeAsKey('name')
                                ->prototype('variable')->end()
                            ->end()
                            ->arrayNode('index')
                                ->useAttributeAsKey('name')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
