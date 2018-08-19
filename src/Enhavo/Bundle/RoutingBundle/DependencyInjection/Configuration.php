<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('enhavo_routing');
        $rootNode
            ->children()
                ->scalarNode('condition_resolver')->defaultValue(null)->end()
                ->arrayNode('classes')
                    ->useAttributeAsKey('class')
                    ->prototype('array')
                        ->children()
                            ->variableNode('router')->end()
    //                        ->arrayNode('router')
    //                            ->useAttributeAsKey('type')
    //                            ->prototype('variable')->end()
    //                        ->end()
                            ->variableNode('generators')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
