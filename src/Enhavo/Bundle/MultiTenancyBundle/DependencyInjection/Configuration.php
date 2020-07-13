<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 21.11.17
 * Time: 10:35
 */

namespace Bundle\MultiTenancyBundle\DependencyInjection;

use Bundle\MultiTenancyBundle\Factory\ConfigurationFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('multiTenancy');
        $rootNode
            ->children()
                ->arrayNode('configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('factory')->defaultValue(ConfigurationFactory::class)->end()
                        ->scalarNode('parameters_path')->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }

    public static function addMultiTenancyConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('multiTenancys')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('role')->end()
                            ->scalarNode('base_url')->end()
                            ->arrayNode('domains')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $node;
    }
}
