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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_search');

        $rootNode
            ->children()
                ->arrayNode('search')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('indexing')->defaultValue(false)->end()
                        ->scalarNode('template')->defaultValue('EnhavoSearchBundle:Search:render.html.twig')->end()
                        ->scalarNode('engine')->defaultValue('enhavo_search.engine.elastic_search_engine')->end()
                    ->end()
                ->end()
                ->arrayNode('elastica')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue(9200)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
