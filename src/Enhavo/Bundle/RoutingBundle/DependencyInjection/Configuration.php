<?php

namespace Enhavo\Bundle\RoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_routing');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->useAttributeAsKey('class')
                    ->prototype('array')
                        ->children()
                            ->variableNode('router')->end()
                            ->variableNode('generators')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        ;

        return $treeBuilder;
    }
}
